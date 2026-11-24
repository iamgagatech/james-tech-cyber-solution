<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('Cache-Control: no-store');

function respond(int $status, array $body): void {
    http_response_code($status);
    echo json_encode($body, JSON_UNESCAPED_SLASHES);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST');
    respond(405, ['ok' => false, 'message' => 'Use the consultation form to send a request.']);
}

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$host = $_SERVER['HTTP_HOST'] ?? '';
if ($origin !== '' && $host !== '' && parse_url($origin, PHP_URL_HOST) !== preg_replace('/:\d+$/', '', $host)) {
    respond(403, ['ok' => false, 'message' => 'This request could not be verified. Please reload the page and try again.']);
}

$contentType = strtolower($_SERVER['CONTENT_TYPE'] ?? '');
if (strpos($contentType, 'application/json') === false) {
    respond(415, ['ok' => false, 'message' => 'Unsupported form submission.']);
}
if ((int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > 65536) respond(413, ['ok' => false, 'message' => 'The form submission is too large.']);
$input = json_decode((string) file_get_contents('php://input'), true);
if (!is_array($input)) respond(400, ['ok' => false, 'message' => 'Please check the form and try again.']);

// Quietly discard automated submissions that fill the hidden honeypot.
if (!empty($input['website'])) respond(200, ['ok' => true, 'message' => 'Thank you. Your consultation request has been received.']);

$fields = ['name' => 120, 'business' => 160, 'email' => 254, 'phone' => 40, 'service' => 100, 'concern' => 2000, 'date' => 20, 'method' => 50];
$data = [];
foreach ($fields as $key => $max) {
    $value = trim((string) ($input[$key] ?? ''));
    $length = function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
    if ($length > $max) respond(422, ['ok' => false, 'message' => 'One or more fields are too long. Please shorten your response.']);
    $data[$key] = $value;
}
if ($data['name'] === '' || !filter_var($data['email'], FILTER_VALIDATE_EMAIL) || $data['service'] === '' || $data['concern'] === '' || !in_array($data['method'], ['Video consultation', 'Zoom call'], true)) {
    respond(422, ['ok' => false, 'message' => 'Please provide your name, a valid email, service interest, concern and consultation method.']);
}

$configPath = dirname(__DIR__) . '/business-config.json';
$config = is_file($configPath) ? json_decode((string) file_get_contents($configPath), true) : null;
$recipient = is_array($config) ? trim((string) ($config['businessEmail'] ?? '')) : '';
if ($recipient === '' || !filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
    respond(503, ['ok' => false, 'message' => 'Online enquiry delivery is not active yet. Please contact the business through its official channel once published.']);
}

$subject = 'Website consultation request — ' . preg_replace('/[\r\n]+/', ' ', $data['name']);
$body = "New consultation request from the James-Tech Cyber Solution website\n\n";
$labels = ['name' => 'Full name', 'business' => 'Business / organization', 'email' => 'Email', 'phone' => 'Phone', 'service' => 'Service / package', 'date' => 'Preferred date', 'method' => 'Consultation method', 'concern' => 'Concern'];
foreach ($labels as $key => $label) $body .= $label . ': ' . ($data[$key] !== '' ? $data[$key] : 'Not provided') . "\n";
$headers = "MIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\n";
$headers .= 'From: James-Tech Website <' . $recipient . ">\r\n";
$headers .= 'Reply-To: ' . str_replace(["\r", "\n"], '', $data['email']) . "\r\n";
if (!function_exists('mail') || !@mail($recipient, $subject, $body, $headers)) {
    respond(503, ['ok' => false, 'message' => 'Enquiry delivery is temporarily unavailable. Please try again later.']);
}
respond(200, ['ok' => true, 'message' => 'Thank you. Your consultation request has been sent. We will follow up using the contact details you provided.']);
