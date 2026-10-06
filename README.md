# Eloom LMS

An open source Learning Management System for vocational training organisations (RTOs), colleges and
education agencies. It covers the student lifecycle from lead and application through offer letter,
enrolment, delivery and assessment to certification, including Recognition of Prior Learning (RPL).

Built with Laravel 8 and a modular architecture ([nwidart/laravel-modules](https://github.com/nWidart/laravel-modules)).
Every feature area is a self-contained module under [`Modules/`](Modules/).

## Features

**Portals.** Separate logins and dashboards for admins, trainers, students, education agents and agent
branch users. There is also a REST API (Laravel Passport) for a student mobile app.

**Students and enrolment**
- Student records, documents, identifiers and custom conditions
- Offer letters, generated as PDF from company details and offer settings, with offer status tracking
- Intakes, intake courses and units, classrooms and delivery sites
- Credit transfer and RPL
- Fees, instalments, payments and due reports

**Teaching and assessment**
- Courses, units and learning resources
- Assignments, submissions, grading and PDF annotation
- Attendance
- Online classes with Zoom and Microsoft Teams
- Certificates and transcripts built from HTML/DOCX templates, with QR codes

**Agents and CRM**
- Education agents, branches, branch users and commission tracking
- Leads, with optional Zoho CRM sync

**Communication**
- Real-time chat through an external Socket.io server
- Email templates and email accounts: SMTP, Mailgun, Gmail and Microsoft 365
- Push notifications through Firebase Cloud Messaging
- Support tickets

**Administration**
- Role-based permissions
- Activity logs
- Reports: students, intakes, fees, agents and commissions
- AVETMISS data backup
- Database backup and restore

## Requirements

- PHP 8.2+ (the committed `composer.lock` requires it), with extensions `gd`, `zip`, `intl`, `mbstring`, `pdo_mysql` and `curl`
- MySQL 5.7+ or MariaDB 10.3+, with the `mysql` / `mysqldump` command line tools installed if you use
  backup and restore
- Composer 2
- Node.js 16+ and npm, only if you rebuild the front-end assets

## Installation

```bash
git clone https://github.com/eloompty/Eloom-LMS-RPL.git
cd Eloom-LMS-RPL

composer install
cp .env.example .env
php artisan key:generate
```

Create an empty MySQL database. Set the `DB_*` values in `.env`, then run:

```bash
php artisan migrate        # includes every module's migrations
php artisan db:seed
php artisan passport:install
```

Start the app:

```bash
php artisan serve
```

Open <http://localhost:8000>. On a fresh install there are no users yet, so the app shows a one-time
**registration form**. It creates the super admin account and your organisation's company record.
After that first account exists, registration is closed automatically.

Next, go to **Settings** and fill in:
- Offer settings: signatory, signature, logo, bank/payment details, and the terms and conditions printed on offer letters
- Email settings
- Other organisation-specific details

## Configuration

All deployment-specific values come from `.env`. See [`.env.example`](.env.example) for the full list.
These integrations are optional:

| Feature | Variables | Notes |
|---|---|---|
| Push notifications | `FIREBASE_*`, `SERVER_API_KEY` | Values come from your own Firebase project. The service worker is served dynamically at `/firebase-messaging-sw.js`. |
| Real-time chat | `SOCKET_SERVER_URL`, `MIX_SOCKET_SERVER_URL` | Needs a Socket.io server that accepts `POST /emit`. The server is not part of this repository. |
| DOCX certificate import | `DOCX_CONVERTER_URL` | Needs an external DOCX-to-HTML conversion service. Without it, build templates in the HTML editor instead. |
| Zoom classes | `ZOOM_API_*` | |
| Student limit | `STUDENT_COUNT` | Maximum number of students. Defaults to 100. |
| Dashboard footer | `APP_FOOTER_TEXT`, `APP_FOOTER_URL` | Defaults to `APP_NAME` / `APP_URL`. |

Mail providers (Gmail, Microsoft 365, Mailgun), Microsoft Teams and Zoho CRM credentials are entered
by an admin in the **Settings** screens and stored in the database. They don't go in `.env`.

## API documentation

The student API is documented with OpenAPI (L5-Swagger). To regenerate and view the docs:

```bash
php artisan l5-swagger:generate
```

Then visit `/api/documentation`.

## Project structure

```
app/            Core helpers (app/helpers.php), traits and service providers
config/         Laravel configuration
Modules/        One folder per feature (Student, Course, Intake, Trainer, Agent, ...)
  <Module>/
    Database/   Migrations and seeders for the module
    Entities/   Eloquent models
    Http/       Controllers
    Resources/  Blade views and assets
    Routes/     web.php and api.php
public/themes/  AdminLTE admin theme
```

Turn modules on or off in [`modules_statuses.json`](modules_statuses.json).

## Contributing

Contributions are welcome. Read [CONTRIBUTING.md](CONTRIBUTING.md) before you open a pull request.

## Security

Please don't report security vulnerabilities in public issues. See [SECURITY.md](SECURITY.md).

## License

Released under the [MIT License](LICENSE).
