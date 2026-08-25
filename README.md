# Relay

Relay is a real-time, browser-based chat application with end-to-end
PGP-encrypted messaging. It's built with vanilla PHP (loosely following an
MVC structure), jQuery/JavaScript on the frontend, and MySQL for storage.

## Features

- Username/password registration and login, with optional "remember me"
  persistent login and optional email-based two-factor authentication
- A unique PGP keypair is generated for every user at registration; messages
  are encrypted with the recipient's public key
- Direct messages and group chats, with friend requests and a friends list
- Group chat administration (adding/removing members, public/private groups)
- Account settings: profile photo upload, email/password changes, deleting
  an account
- Username/message profanity filtering
- Google reCAPTCHA v3 on registration/login forms (optional, see below)

## Tech stack

- **Backend:** PHP, MySQL (via `mysqli` and PDO in different parts of the
  codebase — see [Known limitations](#known-limitations))
- **Frontend:** jQuery / vanilla JavaScript, CSS
- **PGP:** [openpgp-php](http://github.com/bendiken/openpgp-php) (vendored
  under `lib/`, public domain) + [phpseclib](https://phpseclib.com/) for RSA
  key generation
- **Email:** [PHPMailer](https://github.com/PHPMailer/PHPMailer) (vendored
  under `vendor/`)

## Project structure

```
app/
  controllers/   Request-handling classes (one per action)
  models/        Database/business logic classes
  views/         Page templates (register, login, forgot password, etc.)
  core/          Shared base classes (DB connection, mailer, controller)
classes/
  chat/          Chat UI/behavior (scripts.php — served as JS)
  recaptcha.php  reCAPTCHA verification
inc/             Form-handling endpoints (e.g. login.inc.php, register.inc.php)
                 and shared includes (db.php, authenticator.php)
lib/             Vendored openpgp-php library
vendor/          Vendored PHPMailer
vendor_pgp/      Vendored dependencies used by the PGP flow (phpseclib, etc.)
uploads/         User-uploaded profile pictures + static images
config.php       Central app configuration (reads from environment variables)
relaydb.sql             Database schema (tables only, no seed data)
```

## Getting started (local development)

### Requirements

- PHP 8.1+ with the `mysqli` and `pdo_mysql` extensions
- MySQL or MariaDB
- A webserver, or just PHP's built-in dev server (used below)

### 1. Clone and set up the database

```bash
git clone https://github.com/dallanj/relay.git
cd relay
mysql -u root -e "CREATE DATABASE liveChat;"
mysql -u root liveChat < relaydb.sql
```

### 2. Configure environment variables

Copy `.env.example` to `.env` as a reference, then export the variables in
your shell (or however your local setup loads `.env` files — this project
doesn't include a `.env` loader, so use `export $(cat .env | xargs)`,
`direnv`, or your webserver's environment config):

```bash
export DB_HOST=127.0.0.1
export DB_USER=root
export DB_PASS=
export DB_NAME=liveChat
export RECAPTCHA_ENABLED=false   # leave off locally, see note below
```

All settings are documented in `.env.example` and read centrally in
`config.php`.

### 3. Run it

```bash
php -S 127.0.0.1:8080
```

Then visit `http://127.0.0.1:8080/index.php`.

### About reCAPTCHA

Registration and login are gated behind Google reCAPTCHA v3 by default. For
local development, set `RECAPTCHA_ENABLED=false` — verification is skipped
entirely and treated as a pass. For a production deployment, set
`RECAPTCHA_ENABLED=true` and provide `RECAPTCHA_SECRET` /
`RECAPTCHA_SITE_KEY` from your own Google reCAPTCHA v3 account.

### Sending email (password reset, 2FA codes)

Outgoing mail uses PHPMailer over SMTP. Set `SMTP_HOST`, `SMTP_USERNAME`,
`SMTP_PASSWORD`, `SMTP_PORT`, and `SMTP_FROM` to a real mail provider's
credentials to enable this; without them, email-dependent flows (forgot
password, 2FA emails) won't be able to send.

## Known limitations

This is a personal/learning project, and a few things are worth knowing
about before relying on it:

- **Mixed DB access patterns:** some code uses `mysqli` (via `inc/db.php`)
  and other parts use PDO (via `app/core/dbh.classes.php`). Both are wired
  up from the same `config.php`, but consolidating on one API would be a
  good follow-up refactor.
- **PHP version compatibility:** the vendored `lib/openpgp.php` originally
  used curly-brace string/array offset syntax (`$var{0}`), which PHP 8
  removed entirely — this caused registration to fail with a fatal error.
  It's been converted to bracket syntax (`$var[0]`) so it runs on current
  PHP versions, but if you pull in a newer upstream copy of the library,
  re-check for the same issue.
- **PHP notices:** you'll see some `Undefined array key` warnings for
  session values that aren't always set (e.g. optional checkbox fields).
  These are non-fatal but worth cleaning up.
- Password/session/security logic here has not been audited; treat this as
  a learning project rather than something to run with real user data
  without a security review.

## License

No license file is currently included — all rights reserved by default
until one is added. The vendored `lib/openpgp.php` (openpgp-php) is public
domain, and PHPMailer is licensed under LGPL-2.1 (see `vendor/phpmailer`).