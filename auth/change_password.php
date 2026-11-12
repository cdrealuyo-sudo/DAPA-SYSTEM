<?php
session_start();

function redirectToProfile(string $status): void
{
    header("Location: ../user/profile.php?password=" . urlencode($status));
    exit();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirectToProfile('failed');
}

$submittedToken = $_POST['csrf_token'] ?? '';
if (!is_string($submittedToken) || !hash_equals($_SESSION['csrf_token'] ?? '', $submittedToken)) {
    redirectToProfile('csrf');
}

$currentPassword = $_POST['current_password'] ?? '';
$newPassword = $_POST['new_password'] ?? '';
$confirmPassword = $_POST['confirm_password'] ?? '';

if (!is_string($currentPassword) || !is_string($newPassword) || !is_string($confirmPassword)) {
    redirectToProfile('failed');
}

if (strlen($newPassword) < 8) {
    redirectToProfile('short');
}

if ($newPassword !== $confirmPassword) {
    redirectToProfile('mismatch');
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

if (password_verify($newPassword, $user['password'])) {
    $conn->close();
    redirectToProfile('same');
}

$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
$update = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
$update->bind_param('si', $newPasswordHash, $userId);
$updated = $update->execute();
$update->close();
$conn->close();

redirectToProfile($updated ? 'changed' : 'failed');