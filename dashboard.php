<?php
session_start();

if (!isset($_SESSION['portal_user'])) {
    header('Location: portal.php');
    exit;
}

$user = $_SESSION['portal_user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Dashboard | Aether365 Portal</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<header class="header">
    <div class="container">
        <a href="index.html" class="logo">Aether<span>365</span></a>
        <nav class="nav">
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="portal.php?logout=1">Sign Out</a>
        </nav>
        <button class="mobile-toggle">☰</button>
    </div>
</header>

<section class="dashboard">
    <div class="container">
        <div class="dash-header">
            <div>
                <h1 style="font-size:1.7rem; margin-bottom:4px;">Welcome back</h1>
                <p style="color:var(--gray-500); font-size:.95rem;">Signed in as <?php echo htmlspecialchars($user); ?></p>
            </div>
            <a href="contact.html" class="btn btn-primary btn-sm">Open New Ticket</a>
        </div>

        <div class="dash-stats">
            <div class="dash-stat">
                <strong>3</strong>
                <span>Open Tickets</span>
            </div>
            <div class="dash-stat">
                <strong>12</strong>
                <span>Resolved (30 days)</span>
            </div>
            <div class="dash-stat">
                <strong>99.8%</strong>
                <span>Service Uptime</span>
            </div>
            <div class="dash-stat">
                <strong>4.2h</strong>
                <span>Avg Response</span>
            </div>
        </div>

        <h2 style="font-size:1.2rem; margin-bottom:16px;">Recent Support Tickets</h2>
        <table class="ticket-table">
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Updated</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#A365-2841</td>
                    <td>Teams calling audio issues for sales team</td>
                    <td><span class="badge badge-progress">In Progress</span></td>
                    <td>High</td>
                    <td>Today, 09:14 AM</td>
                </tr>
                <tr>
                    <td>#A365-2837</td>
                    <td>New user onboarding – Marketing intern</td>
                    <td><span class="badge badge-open">Open</span></td>
                    <td>Normal</td>
                    <td>Yesterday</td>
                </tr>
                <tr>
                    <td>#A365-2829</td>
                    <td>SharePoint site permissions review</td>
                    <td><span class="badge badge-resolved">Resolved</span></td>
                    <td>Normal</td>
                    <td>Nov 21</td>
                </tr>
                <tr>
                    <td>#A365-2815</td>
                    <td>Conditional Access policy adjustment</td>
                    <td><span class="badge badge-resolved">Resolved</span></td>
                    <td>High</td>
                    <td>Nov 19</td>
                </tr>
                <tr>
                    <td>#A365-2802</td>
                    <td>License reassignment after employee departure</td>
                    <td><span class="badge badge-resolved">Resolved</span></td>
                    <td>Normal</td>
                    <td>Nov 17</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top:40px; background:var(--gray-50); padding:24px; border-radius:10px;">
            <h3 style="margin-bottom:10px; font-size:1.1rem;">Monthly Health Snapshot</h3>
            <p style="color:var(--gray-500); font-size:.92rem; margin-bottom:12px;">November 2026 – Sample report for demo account</p>
            <ul style="color:var(--gray-700); font-size:.92rem; padding-left:18px;">
                <li>All critical security alerts reviewed and closed</li>
                <li>2 unused licenses identified and flagged for removal</li>
                <li>MFA registration: 98% of users complete</li>
                <li>No active service degradations detected</li>
            </ul>
        </div>

        <p style="margin-top:30px; font-size:.85rem; color:var(--gray-500); text-align:center;">
            This is a demonstration dashboard. In a production deployment, tickets and reports are connected to your real support system.
        </p>
    </div>
</section>

<footer class="footer">
    <div class="container">
        <div class="footer-bottom">
            <p>© 2026 Aether365 Support LLC. Client Portal – Secure Access.</p>
        </div>
    </div>
</footer>
<script src="assets/script.js"></script>
</body>
</html>
