<?php
/**
 * Verifies a Google reCAPTCHA v3 response.
 *
 * When recaptcha is disabled in config (the local-development default),
 * verification is skipped and treated as a pass so the app is usable
 * without a real Google API secret.
 */

require_once __DIR__ . '/../config.php';
$recaptchaConfig = app_config()['recaptcha'];

if ($recaptchaConfig['enabled']) {
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = $recaptchaConfig['secret'];
    $recaptcha_response = $_POST['recaptcha_response'] ?? '';

    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);
} else {
    $recaptcha = (object) ['success' => true, 'score' => 1.0];
}