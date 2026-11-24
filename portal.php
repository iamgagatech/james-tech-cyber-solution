<?php
session_start();

// Simple demo authentication
// In production you would replace this with a real database + hashed passwords
$valid_users = [
    'demo@aether365.com' => 'Demo2026!',
    'client@company.com' => 'Support123'
];

$error = '';
$logged_in = isset($_SESSION['portal_user']);

if (isset($_POST['login'])) {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (isset($valid_users[$email]) && $valid_users[$email] === $password) {
        $_SESSION['portal_user'] = $email;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid email or password. Try the demo credentials below.';
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: portal.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Portal Login | Aether365</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<header class="header">
    <div class="container">
        <a href="index.html" class="logo">Aether<span>365</span></a>
        <nav class="nav">
            <a href="index.html">Home</a>
            <a href="services.html">Services</a>
            <a href="blog.html">Blog</a>
            <a href="portal.php" class="btn-nav">Client Portal</a>
        </nav>
        <button class="mobile-toggle">☰</button>
    </div>
</header>

<section class="portal-hero">
    <div class="container">
        <h1>Client Portal</h1>
        <p>Secure access to your support tickets, reports, and Microsoft 365 status.</p>
    </div>
</section>

<div class="portal-box">
    <h2>Sign In</h2>
    <p class="sub">Existing managed support clients only</p>

    <div class="demo-note">
        <strong>Demo Access</strong><br>
        Email: demo@aether365.com<br>
        Password: Demo2026!
    </div>

    <?php if ($error): ?>
        <div style="background:#fef2f2;color:#b91c1c;padding:12px;border-radius:8px;margin-bottom:18px;font-size:.9rem;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="portal.php">
        <div class="form-group">
            <label for="email">Work Email</label>
            <input type="email" id="email" name="email" required placeholder="you@company.com" value="demo@aether365.com">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required placeholder="••••••••">
        </div>
        <button type="submit" name="login" class="btn btn-primary btn-full">Sign In to Portal</button>
    </form>

    <p style="text-align:center; margin-top:20px; font-size:.85rem; color:var(--gray-500);">
        Need an account? <a href="contact.html" style="color:var(--primary);">Contact us</a> about managed support.
    </p>
</div>

<footer class="footer">
    <div class="container">
        <div class="footer-bottom">
            <p>© 2026 Aether365 Support LLC. Secure client portal.</p>
        </div>
    </div>
</footer>
<script src="assets/script.js"></script>
</body>
</html>
