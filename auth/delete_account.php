<?php
session_start();

function redirectToProfile(string $status): void
{
    header('Location: ../user/profile.php?delete=' . urlencode($status));
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: ../pages/login.html');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectToProfile('failed');
}

$submittedToken = $_POST['csrf_token'] ?? '';
$sessionToken = $_SESSION['csrf_token'] ?? '';
if (!is_string($submittedToken) || !is_string($sessionToken) || !hash_equals($sessionToken, $submittedToken)) {
    redirectToProfile('csrf');
}

$currentPassword = $_POST['current_password'] ?? '';
if (!is_string($currentPassword) || $currentPassword === '') {
    redirectToProfile('invalid');
}

require_once '../db.php';

$userId = (int) $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT password FROM users WHERE id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user || !password_verify($currentPassword, $user['password'])) {
    $conn->close();
    redirectToProfile('invalid');
}

$delete = $conn->prepare('DELETE FROM users WHERE id = ?');
$delete->bind_param('i', $userId);
$deleted = $delete->execute() && $delete->affected_rows === 1;
$delete->close();
$conn->close();

if (!$deleted) {
    redirectToProfile('failed');
}

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $cookie = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $cookie['path'],
        $cookie['domain'],
        $cookie['secure'],
        $cookie['httponly']
    );
}
session_destroy();

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account deleted</title>
</head>
<body>
    <p>Your account has been deleted. <a href="../pages/login.html">Continue to login</a>.</p>
    <script>
        localStorage.removeItem('lguLoggedIn');
        localStorage.removeItem('lguUserName');
        localStorage.removeItem('lguUserEmail');
        localStorage.removeItem('lguUserRole');
        window.location.replace('../pages/login.html?account=deleted');
    </script>
</body>
</html>
