## Employee Attendance API

Backend-only REST API for managing employees, attendance, and daily attendance reports, built with **Laravel 12**, **PHP 8.3**, **Sanctum**, **Pest**, **Laravel Excel**, and **laravel-snappy**.

### Local setup

- **1. Install dependencies**

  ```bash
  composer install
  ```

- **2. Create environment file**

  ```bash
  cp .env.example .env
  ```

- **3. Generate the application key (fixes `MissingAppKeyException`)**

  If running Laravel directly:

  ```bash
  php artisan key:generate
  ```

  If running via Sail:

  ```bash
  ./vendor/bin/sail artisan key:generate
  ```

  After this, your `.env` must contain a non-empty `APP_KEY=base64:...` value.

- **4. Run migrations**

  ```bash
  php artisan migrate
  # or, with Sail:
  ./vendor/bin/sail artisan migrate
  ```

- **5. Run the app**

  ```bash
  # Local PHP on port 8001
  php artisan serve --port=8001

  # Or, with Sail on port 8000
  ./vendor/bin/sail up -d
  ```

Visit the API at:

- `http://localhost:8001/api/v1` when running **locally** with `php artisan serve --port=8001`
- `http://localhost:8000/api/v1` when running via **Sail** (Docker)

---

### Demo / test data

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
