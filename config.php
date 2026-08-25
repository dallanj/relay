<?php
/**
 * Central application configuration.
 *
 * All values are read from environment variables so real credentials never
 * need to be committed to the repo. Sensible local-development defaults are
 * used as a fallback when a variable isn't set.
 *
 * Copy .env.example to .env (or export these as real environment variables)
 * and fill in real values for production use.
 *
 * Usage: $config = require __DIR__ . '/config.php'; (relative depth varies
 * by caller) — this file always returns the full config array, no matter
 * how many times or from how many places it's included in one request.
 */

if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        $value = getenv($key);
        return $value === false ? $default : $value;
    }
}

if (!function_exists('app_config')) {
    function app_config(): array
    {
        static $config = null;

        if ($config === null) {
            $config = [
                'db' => [
                    'host'     => env('DB_HOST', '127.0.0.1'),
                    'username' => env('DB_USER', 'root'),
                    'password' => env('DB_PASS', ''),
                    'database' => env('DB_NAME', 'liveChat'),
                ],

                'recaptcha' => [
                    // When false, recaptcha verification is skipped entirely.
                    // This is meant for local development only — keep it
                    // true in production.
                    'enabled'  => filter_var(env('RECAPTCHA_ENABLED', 'false'), FILTER_VALIDATE_BOOLEAN),
                    'secret'   => env('RECAPTCHA_SECRET', ''),
                    'site_key' => env('RECAPTCHA_SITE_KEY', ''),
                ],

                'mail' => [
                    'host'     => env('SMTP_HOST', ''),
                    'username' => env('SMTP_USERNAME', ''),
                    'password' => env('SMTP_PASSWORD', ''),
                    'port'     => (int) env('SMTP_PORT', 465),
                    'from'     => env('SMTP_FROM', ''),
                ],
            ];
        }

        return $config;
    }
}

return app_config();
