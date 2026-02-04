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
  php artisan serve
  # or, with Sail:
  ./vendor/bin/sail up -d
  ```

Visit the API at `http://localhost/api/v1` (or `http://localhost:8080/api/v1` when using Sail).

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

3. Trigger actions that send mail (e.g. forgot password, attendance notifications) and open `http://localhost:8025` to see the messages in Mailpit.

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
