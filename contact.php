<?php
/**
 * Aether365 Contact Form Handler
 * Change the $to address below to your real business email.
 */

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: contact.html");
    exit;
}

$to = "support@aether365.com";  // <-- CHANGE THIS TO YOUR REAL EMAIL

function clean($data) {
    return htmlspecialchars(stripslashes(trim($data)), ENT_QUOTES, 'UTF-8');
}

$name    = isset($_POST['name'])    ? clean($_POST['name'])    : '';
$company = isset($_POST['company']) ? clean($_POST['company']) : '';
$email   = isset($_POST['email'])   ? clean($_POST['email'])   : '';
$phone   = isset($_POST['phone'])   ? clean($_POST['phone'])   : '';
$service = isset($_POST['service']) ? clean($_POST['service']) : '';
$message = isset($_POST['message']) ? clean($_POST['message']) : '';

$errors = [];
if (empty($name)) $errors[] = "Name is required.";
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
if (empty($service)) $errors[] = "Please select a service.";
if (empty($message)) $errors[] = "Message is required.";

if (!empty($errors)) {
    echo "<!DOCTYPE html><html><head><title>Error</title><style>body{font-family:sans-serif;max-width:600px;margin:80px auto;padding:20px}</style></head><body>";
    echo "<h2>There was a problem</h2><ul>";
    foreach ($errors as $e) echo "<li>$e</li>";
    echo "</ul><p><a href='contact.html'>← Go back</a></p></body></html>";
    exit;
}

$subject = "[Aether365] $service request from $name";
$body  = "New inquiry from Aether365 website\n";
$body .= "=================================\n\n";
$body .= "Name:     $name\n";
$body .= "Company:  " . ($company ?: "Not provided") . "\n";
$body .= "Email:    $email\n";
$body .= "Phone:    " . ($phone ?: "Not provided") . "\n";
$body .= "Service:  $service\n\n";
$body .= "Message:\n$message\n\n";
$body .= "Submitted: " . date("Y-m-d H:i:s T") . "\n";

$headers  = "From: Aether365 Website <noreply@" . ($_SERVER['HTTP_HOST'] ?? 'aether365.com') . ">\r\n";
$headers .= "Reply-To: $name <$email>\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$sent = @mail($to, $subject, $body, $headers);

if ($sent) {
    echo "<!DOCTYPE html><html lang='en'><head><meta charset='UTF-8'><meta name='viewport' content='width=device-width,initial-scale=1'>
    <title>Request Received</title>
    <style>body{font-family:Inter,sans-serif;background:#f0f7ff;margin:0;padding:40px 20px}
    .box{max-width:500px;margin:60px auto;background:#fff;padding:48px 40px;border-radius:12px;box-shadow:0 10px 40px rgba(0,0,0,.1);text-align:center}
    h1{color:#0f172a;font-size:1.7rem;margin-bottom:14px}p{color:#64748b;line-height:1.6;margin-bottom:26px}
    a{display:inline-block;background:#1d4ed8;color:#fff;padding:13px 26px;border-radius:8px;text-decoration:none;font-weight:600}</style></head>
    <body><div class='box'><div style='font-size:2.8rem;margin-bottom:12px'>✅</div>
    <h1>Request Received</h1>
    <p>Thank you, <strong>" . htmlspecialchars($name) . "</strong>. We have received your inquiry and will respond within one business day.</p>
    <a href='index.html'>Return to Homepage</a></div></body></html>";
} else {
    echo "<!DOCTYPE html><html><head><title>Error</title></head><body style='font-family:sans-serif;max-width:600px;margin:80px auto;padding:20px'>
    <h2>Unable to send</h2><p>Please email us directly at support@aether365.com</p>
    <p><a href='contact.html'>← Go back</a></p></body></html>";
}
?>
