## Employee Attendance API

Backend-only REST API for managing employees, attendance, and daily attendance reports, built with **Laravel 12**, **PHP 8.3**, **Sanctum**, **Pest**, **Laravel Excel**, and **laravel-snappy**.

## Run the project with Sail (Docker) (recommended)

### Repository location & terminal path

All commands below assume you have the project cloned here:

```bash
~/employee-attendance-api
```

From any terminal, first move into the project root:

```bash
cd ~/employee-attendance-api
```

You should see files like `composer.json`, `docker-compose.yml`, and `README.md` when you run `ls` in this folder.

### Prerequisites

- **Docker Desktop** (or Docker Engine) installed and running
- **Docker Compose** available (Docker Desktop includes it)

### Quick start (fresh setup)

From the project root:

1) **Install PHP dependencies (creates `vendor/` and Sail binary)**

- If you already have PHP + Composer on your machine:

```bash
composer install
```

- If you do NOT have PHP/Composer locally, install via a Composer container:

```bash
docker run --rm -u "$(id -u):$(id -g)" -v "$PWD:/app" -w /app composer:2 composer install
```

2) **Create `.env` from `.env.example`**

```bash
cp .env.example .env
```

3) **Start Sail**

```bash
./vendor/bin/sail up -d
```

4) **Generate `APP_KEY`**

```bash
./vendor/bin/sail artisan key:generate
```

5) **Run migrations (and optional seed data)**

- Migrate only:

```bash
./vendor/bin/sail artisan migrate
```

- Or reset + seed demo data (recommended for first run):

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

This will:

- Create all tables
- Insert a default **admin user** (`admin@example.com` / `password123`)
- Insert example **employees** and **attendance records** you can use immediately in the API and reports

### URLs (when running with Sail)

- **API base**: `http://localhost:8000/api/v1`
- **Swagger / API docs**: `http://localhost:8000/docs`
- **Mailpit UI**: `http://localhost:8025`

### Common Sail commands

```bash
./vendor/bin/sail ps
./vendor/bin/sail down
./vendor/bin/sail artisan tinker
./vendor/bin/sail artisan test
```

### Notes about `.env` (important)

This repo ships with a working `.env.example` for Sail. After copying it to `.env`, these are the key values:

- **APP_URL**: should match your local URL (default `http://localhost:8000`)
- **APP_PORT**: controls the exposed port (default `8000`)
- **Database**: Sail uses the `mysql` service name as host:
  - `DB_HOST=mysql`
  - `DB_DATABASE=employee_attendance`
  - `DB_USERNAME=sail`
  - `DB_PASSWORD=password`
- **Mailpit** (already set):
  - `MAIL_HOST=mailpit`
  - `MAIL_PORT=1025`

If port `8000` is busy on your machine, change `APP_PORT=8000` in `.env` to another port (e.g. `APP_PORT=8080`), then restart Sail:

```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
```

## Demo / test data

- **Admin user (for protected endpoints & employees)**

  After running migrations with seed:

  ```bash
  ./vendor/bin/sail artisan migrate:fresh --seed
  ```

  You can log in with:

  - Email: `admin@example.com`
  - Password: `password123`

  Use this account to:

  - Call `POST /api/v1/auth/login` → get an admin token
  - Click **Authorize** in Swagger and paste the token (no `Bearer ` prefix)
  - Call employee, attendance and reports endpoints as an admin.

- **Sample employee payload**

  ```json
  {
    "name": "Kwizera Emmanuel",
    "email": "emmizokwizera@gmail.com",
    "employee_identifier": "EMP-001",
    "phone_number": "+250788000000"
  }
  ```

- **Sample attendance payloads**

  ```json
  {
    "employee_id": 1,
    "notes": "Arrived on time"
  }
  ```

  or

  ```json
  {
    "employee_identifier": "EMP-001",
    "notes": "Left early for appointment"
  }
  ```

---

### Running tests

- **With local PHP:**

  ```bash
  php artisan test
  ```

- **With Sail (Docker):**

  ```bash
  ./vendor/bin/sail artisan test
  ```

All tests use Pest and `RefreshDatabase`. Queues and mails are faked where appropriate, so you can run the suite without external services.

---

### How to manually verify each feature

- **Authentication (Sanctum, stateless)**
  1. Start Sail and ensure DB is migrated/seeded.
  2. In Swagger at `http://localhost:8000/docs`, call `POST /api/v1/auth/login` with the seeded admin (`admin@example.com` / `password123`).
  3. Copy the returned `token`, click **Authorize** in Swagger, and paste the token (no `Bearer ` prefix).
  4. Call `POST /api/v1/auth/logout`, `POST /api/v1/auth/forgot-password`, and `POST /api/v1/auth/reset-password` to see stateless auth flows.

- **Employee CRUD**
  1. With the admin token authorized in Swagger, call:
     - `GET /api/v1/employees` → should return a paginated list.
     - `POST /api/v1/employees` → create using the **Sample employee payload** above.
     - `GET /api/v1/employees/{id}`, `PUT /api/v1/employees/{id}`, `DELETE /api/v1/employees/{id}`.
  2. Repeat as a non-admin user (register + login) to confirm 403 responses on employee endpoints.

- **Attendance management + queued emails**
  1. Use an existing employee id or the `employee_identifier` from the sample payload.
  2. Call:
     - `POST /api/v1/attendance/check-in` with one of the **Sample attendance payloads**.
     - `POST /api/v1/attendance/check-out` for the same employee.
  3. Open `http://localhost:8025` (Mailpit) and confirm attendance emails were queued and delivered.

- **Reports (PDF & Excel)**
  1. Ensure there is attendance data for today (either from the seeder or by creating check-ins/outs).
  2. With an authenticated token, call:
     - `GET /api/v1/reports/attendance/daily?format=pdf`
     - `GET /api/v1/reports/attendance/daily?format=xlsx`
  3. You should receive a file download in each case (browser) or a file when using the `curl` commands shown below.

- **OpenAPI docs (PHP 8 attributes)**
  1. Visit `http://localhost:8000/docs` while Sail is running.
  2. Confirm that all endpoints (Auth, Employees, Attendance, Reports) are listed with schemas and example payloads.

- **GitHub Actions (Tests workflow)**
  1. Push a commit or open a pull request targeting `master`.
  2. On GitHub, go to the **Actions** tab and open the latest `Tests` workflow run.
  3. Verify that steps run in order:
     - `composer install`
     - `php artisan key:generate`
     - `php artisan migrate --force` (against a MySQL service)
     - `php artisan test`
  4. A green checkmark indicates all automated tests passed for that branch/PR.

---

### CI tests on GitHub (GitHub Actions)

This repo includes a GitHub Actions workflow in `.github/workflows/tests.yml` that runs the full test suite on pull requests.

- **When it runs**
  - On every pull request targeting the `development` or `main` branches.

- **How to see it on GitHub**
  1. Push your branch to GitHub and open a pull request into `development` or `main`.
  2. On the PR page, open the **“Checks”** or **“Actions”** tab to see the `Tests` workflow.
  3. You’ll see live logs for:
     - `composer install`
     - `php artisan migrate --force`
     - `php artisan test`

If the workflow fails, fix the code or tests locally, push new commits to the same branch, and GitHub will re-run the `Tests` workflow automatically.

---

### Mailpit (viewing emails in Docker)

When running via Sail, Mailpit is already defined in `docker-compose.yml`:

- Mailpit container name: `mailpit`
- SMTP host/port (for Laravel): `MAIL_HOST=mailpit`, `MAIL_PORT=1025`
- Web UI: `http://localhost:8025`

To use it:

1. Ensure `.env` has:

   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=mailpit
   MAIL_PORT=1025
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_FROM_ADDRESS="hello@example.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```

2. Start Sail:

   ```bash
   ./vendor/bin/sail up -d
   ```

3. Trigger actions that send mail:
   - **Register a user** (`POST /api/v1/auth/register`) → Welcome email
   - **Forgot password** (`POST /api/v1/auth/forgot-password`) → Password reset token email
   - **Reset password** (`POST /api/v1/auth/reset-password`) → Password reset confirmation email
   - **Attendance check-in/check-out** → Attendance notification emails

4. Open `http://localhost:8025` to see all emails in Mailpit's web interface.

---

### Authentication endpoints

- `POST /api/v1/auth/register`
- `POST /api/v1/auth/login`
- `POST /api/v1/auth/logout`
- `POST /api/v1/auth/forgot-password`
- `POST /api/v1/auth/reset-password`

Use the returned token as:

```http
Authorization: Bearer {token}
```

---

### Employee & attendance features

- Employee CRUD (`/api/v1/employees`, admin-only)
- Attendance check-in (`/api/v1/attendance/check-in`)
- Attendance check-out (`/api/v1/attendance/check-out`)
- No double check-in; check-out requires an open attendance
- Queued emails on attendance creation/update

---

### Reports

Daily attendance report:

- Route: `GET /api/v1/reports/attendance/daily`
- Query params:
  - `date` (optional, `Y-m-d`, defaults to today)
  - `format` = `pdf` (default) or `xlsx`

#### PDF reports (wkhtmltopdf inside Sail)

PDF generation uses `laravel-snappy` (wkhtmltopdf). When running via Sail, the `wkhtmltopdf` binary must exist inside the `laravel.test` container.

If you change the Sail runtime Dockerfile (or pull updates that include it), rebuild once:

```bash
./vendor/bin/sail down
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d
```

Then you can download a PDF:

```bash
curl -H "Authorization: Bearer <TOKEN>" \
  "http://localhost:8000/api/v1/reports/attendance/daily?date=2026-02-06&format=pdf" \
  -o daily-attendance.pdf
```

#### Excel reports (XLSX)

Excel export uses `maatwebsite/excel` and does **not** require wkhtmltopdf:

```bash
curl -H "Authorization: Bearer <TOKEN>" \
  "http://localhost:8000/api/v1/reports/attendance/daily?date=2026-02-06&format=xlsx" \
  -o daily-attendance.xlsx
```
