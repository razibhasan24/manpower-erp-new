# Install — Manpower Recruitment & Overseas Employment ERP (backend)

Target machine: **Windows 10, XAMPP, PHP 8.2+, MySQL 8, Composer, Node.js 20+**.
The app is delivered as a folder to extract into `D:\PH-batch-13\Personal\razib vai`
so that this file ends up at `D:\PH-batch-13\Personal\razib vai\backend\INSTALL.md`.

> The code was authored and statically checked on Linux without PHP, so the
> first install on the Windows PC is also the first live verification run.
> If any step below fails, stop and report the exact error message — do not
> skip the step.

## 0. Check versions first

```cmd
php -v
composer --version
mysql --version
node -v
npm -v
```

Required: **PHP 8.2 or newer** (Laravel 12 requirement), **MySQL 8**,
Composer 2, Node.js 20+. If PHP is older than 8.2, stop here: install a
newer XAMPP first — do not try to install Laravel 12 on PHP 8.1 or below.

## 1. Start XAMPP

Start **Apache** and **MySQL** from the XAMPP Control Panel.

## 2. Create the database

Open `http://localhost/phpmyadmin`, or run in a terminal:

```cmd
mysql -u root -p
```

```sql
CREATE DATABASE manpower_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

## 3. Configure the environment

```cmd
cd D:\PH-batch-13\Personal\razib vai\backend
copy .env.example .env
```

Edit `.env` (Notepad / VS Code) and set at minimum:

```ini
DB_DATABASE=manpower_erp
DB_USERNAME=root
DB_PASSWORD=            # your MySQL root password (empty if none)
APP_URL=http://localhost:8000
```

Optional — first Super Admin account (used only by the seeder):

```ini
SEED_ADMIN_EMAIL=admin@example.com
SEED_ADMIN_NAME=Super Admin
SEED_ADMIN_PASSWORD=      # leave empty: the seeder generates a random
                          # password and prints it once to the console
```

Never commit `.env` and never paste its contents into chat or email.

## 4. Install PHP dependencies

```cmd
composer install
```

This installs Laravel 12 and the pinned packages in `composer.json`:
`spatie/laravel-permission ^6.0`, `laravel/sanctum ^4.0`,
`barryvdh/laravel-dompdf ^3.0`, `maatwebsite/excel ^3.1`,
`spatie/laravel-backup ^9.0`. If Composer reports a version conflict,
record the exact message and stop — do not force a different version.

## 5. Install JavaScript dependencies and build assets

```cmd
npm install
npm run build
```

## 6. Generate the application key

```cmd
php artisan key:generate
```

## 7. Migrate and seed

```cmd
php artisan migrate --seed
```

This creates all tables (74 migrations) and seeds: 7 roles with all
`module.action` permissions, default settings + company profile, and the
single Super Admin account. **Save the generated admin password printed
by the seeder** — it is not stored anywhere else. Change it after first
login (the account is flagged `must_change_password`).

## 8. Run the app

```cmd
php artisan serve
```

Open `http://localhost:8000` and log in with the Super Admin account.
First tasks after login: **Settings → Company Profile** (real agency
name, logo, colours, currency) and **Profile → Change password**.

For production-style serving under XAMPP Apache instead of
`php artisan serve`, point a virtual host at `backend\public` (never at
`backend\` itself) and set `APP_ENV=production`, `APP_DEBUG=false`,
`SESSION_SECURE_COOKIE=true` in `.env`.

## 9. Verify the install

```cmd
php artisan test
php artisan migrate:status
node tools\verify-navigation.mjs
```

`node tools\verify-navigation.mjs` checks every sidebar route, permission, view, component and icon without needing PHP. Run it after changing the menu.

## Password reset email configuration

The login page includes a complete Laravel password-reset flow. Tokens are
single-use and expire after **60 minutes** (`config/auth.php`). No SMTP
credentials are stored in the code or the database.

The current `.env` uses:

```ini
MAIL_MAILER=log
```

With `log`, a reset email is written to `storage\logs\laravel.log` instead of
being delivered. That is correct for local development, but **users will not
receive a real email until a real mailer is configured**. Set the values for
your mail provider before go-live:

```ini
MAIL_MAILER=smtp
MAIL_HOST=smtp.your-provider.com
MAIL_PORT=587
MAIL_USERNAME=your-smtp-username
MAIL_PASSWORD=your-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-domain.com
MAIL_FROM_NAME="${APP_NAME}"

# The reset link is built from this value — it must be the public URL.
APP_URL=https://erp.your-domain.com
```

Verify it on the target machine:

1. Open `/forgot-password`, submit a real account email.
2. Confirm the reset email arrives and the button opens `/reset-password/...`.
3. Set a new password using upper/lower case, a number and a symbol.
4. Confirm the success page appears and the old link can no longer be used.

Do not paste SMTP passwords into chat or commit them to the repository.

## Daily operation

- Queue worker (mail, PDFs, backups): `php artisan queue:work`
- Manual backup: `php artisan backup:run`
- Scheduled backup runs daily at 02:00 (see `routes/console.php`); it
  only runs while the scheduler is active — on Windows, add a scheduled
  task running `php artisan schedule:run` every minute.

## Troubleshooting

| Problem | Fix |
|---|---|
| `php` is not recognised | Add `C:\xampp\php` to the Windows PATH, reopen the terminal |
| `composer install` fails on a package version | Check `php -v` is 8.2+; paste the exact error to the developer |
| `Access denied for user 'root'` | Fix `DB_USERNAME` / `DB_PASSWORD` in `.env` |
| `SQLSTATE[HY000] [2002] Connection refused` | MySQL is not running in XAMPP |
| Blank page / 500 error | Check `storage\logs\laravel.log`; ensure `storage` is writable |
| Assets (CSS/JS) missing | Run `npm run build` again |

## Deployment

For production deployment (HTTPS, secure cookies, backup schedule,
queue worker, scheduler, production checklist and rollback), see
`docs/DEPLOYMENT.md` (Phase 34, Group E). Install first using this
file, then follow the deployment guide before go-live.
