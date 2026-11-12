<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.html");
    exit();
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$statusMessages = [
    'changed' => ['Password changed', 'Your password has been updated. You can now log in with your new password.', 'success'],
    'invalid' => ['Password not changed', 'Your current password is incorrect.', 'error'],
    'mismatch' => ['Password not changed', 'The new passwords do not match.', 'error'],
    'short' => ['Password not changed', 'Your new password must be at least 8 characters long.', 'error'],
    'same' => ['Password not changed', 'Choose a new password that is different from your current password.', 'error'],
    'failed' => ['Password not changed', 'We could not update your password. Please try again.', 'error'],
    'csrf' => ['Request expired', 'Please try changing your password again.', 'error']
];
$status = $_GET['password'] ?? '';
$statusMessage = is_string($status) ? ($statusMessages[$status] ?? null) : null;
$deleteMessages = [
    'invalid' => 'The current password is incorrect.',
    'failed' => 'Your account could not be deleted. Please try again.',
    'csrf' => 'The request expired. Please try again.'
];
$deleteStatus = $_GET['delete'] ?? '';
$deleteMessage = is_string($deleteStatus) ? ($deleteMessages[$deleteStatus] ?? null) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LGU Dapa | Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f4f7fc; color: #172b67; font-family: Arial, sans-serif; }
        .sidebar { position: fixed; inset: 0 auto 0 0; width: 250px; padding: 25px 18px; background: #fff; border-right: 1px solid #e3e8f0; display: flex; flex-direction: column; box-shadow: 5px 0 25px rgba(15, 23, 42, .04); }
        .brand { display: flex; align-items: center; gap: 12px; padding: 5px 8px 22px; border-bottom: 1px solid #edf0f5; }
        .brand-icon { width: 46px; height: 46px; display: grid; place-items: center; border: 1px solid #e1e7ef; border-radius: 12px; color: #1671f9; font-size: 21px; }
        .brand strong { display: block; font-size: 16px; }
        .brand small { display: block; margin-top: 4px; color: #64748b; font-size: 10px; line-height: 1.4; }
        nav { display: grid; gap: 7px; margin-top: 26px; }
        .nav-link { display: flex; align-items: center; gap: 12px; min-height: 44px; padding: 0 13px; border-radius: 9px; color: #64748b; font-size: 13px; font-weight: 600; text-decoration: none; }
        .nav-link i { width: 20px; text-align: center; }
        .nav-link:hover { background: #edf4ff; color: #1671f9; }
        .nav-link.active { background: #1671f9; color: #fff; }
        .sidebar-user { margin-top: auto; padding: 14px 10px 4px; border-top: 1px solid #edf0f5; font-size: 13px; font-weight: 700; }
        .sidebar-user small { display: block; margin-top: 5px; color: #64748b; font-size: 11px; font-weight: 400; overflow-wrap: anywhere; }
        main { max-width: 920px; margin-left: 250px; padding: 38px 42px 60px; }
        .eyebrow { color: #1671f9; font-size: 10px; font-weight: 700; letter-spacing: 1px; }
        h1 { margin: 7px 0 8px; color: #142b70; font-size: 27px; }
        .intro { margin: 0 0 25px; color: #64748b; font-size: 13px; }
        .panel { padding: 24px; background: #fff; border: 1px solid #e1e7ef; border-radius: 12px; box-shadow: 0 6px 20px rgba(15, 23, 42, .04); }
        .panel h2 { margin: 0 0 20px; color: #172b67; font-size: 17px; }
        .profile-details { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; margin-bottom: 24px; }
        .detail { min-width: 0; padding: 13px; background: #f7f9fc; border: 1px solid #e8edf4; border-radius: 8px; }
        .detail label { display: block; margin-bottom: 7px; color: #64748b; font-size: 11px; }
        .detail strong { display: block; overflow-wrap: anywhere; color: #172b67; font-size: 13px; }
        .divider { margin: 0 0 20px; border: 0; border-top: 1px solid #edf0f5; }
        form { max-width: 480px; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 7px; color: #334155; font-size: 12px; font-weight: 600; }
        .form-group input { width: 100%; min-height: 42px; padding: 10px 12px; border: 1px solid #dce4ef; border-radius: 8px; background: #fff; color: #172b67; font: inherit; font-size: 13px; }
        .form-group input:focus { outline: 2px solid rgba(22, 113, 249, .18); border-color: #1671f9; }
        .submit { min-height: 42px; padding: 0 15px; border: 0; border-radius: 8px; background: #1671f9; color: #fff; font-size: 13px; font-weight: 700; cursor: pointer; }
        .submit:hover { background: #0f5fd6; }
        .alert { margin: 0 0 20px; padding: 12px 14px; border: 1px solid; border-radius: 8px; font-size: 13px; }
        .alert.success { border-color: #bbf7d0; background: #f0fdf4; color: #166534; }
        .alert.error { border-color: #fecaca; background: #fef2f2; color: #b91c1c; }
        .danger-zone { margin-top: 20px; border-color: #fecaca; }
        .danger-zone h2 { color: #b91c1c; }
        .danger-description { max-width: 620px; margin: -8px 0 18px; color: #64748b; font-size: 13px; line-height: 1.5; }
        .delete-button { min-height: 42px; padding: 0 15px; border: 0; border-radius: 8px; background: #b91c1c; color: #fff; font-size: 13px; font-weight: 700; cursor: pointer; }
        .delete-button:hover { background: #991b1b; }
        .delete-dialog { width: min(440px, calc(100% - 32px)); padding: 27px; border: 1px solid #e5eaf1; border-radius: 14px; background: #fff; color: #172b67; box-shadow: 0 24px 70px rgba(15, 23, 42, .28); }
        .delete-dialog[open] { animation: dialog-enter .18s ease-out; }
        .delete-dialog::backdrop { background: rgba(15, 23, 42, .58); backdrop-filter: blur(4px); }
        @keyframes dialog-enter { from { opacity: 0; transform: translateY(8px) scale(.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
        .dialog-mark { width: 48px; height: 48px; display: grid; place-items: center; margin-bottom: 16px; border-radius: 12px; background: #fff1f2; color: #be123c; font-size: 19px; }
        .dialog-kicker { color: #be123c; font-size: 10px; font-weight: 700; }
        .delete-dialog h2 { margin: 8px 0 10px; color: #172b67; font-size: 20px; line-height: 1.35; }
        .dialog-description { margin: 0; color: #64748b; font-size: 13px; line-height: 1.55; }
        .dialog-actions { display: flex; justify-content: flex-end; gap: 9px; margin-top: 24px; }
        .dialog-actions button { min-height: 40px; padding: 0 13px; border: 0; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; }
        .dialog-cancel { background: #eef2f7; color: #334155; }
        .dialog-cancel:hover { background: #e2e8f0; }
        .dialog-confirm { background: #b91c1c; color: #fff; }
        .dialog-confirm:hover { background: #991b1b; }
        .dialog-actions button:focus-visible { outline: 3px solid rgba(22, 113, 249, .35); outline-offset: 2px; }
        @media (max-width: 760px) {
            .sidebar { position: static; width: 100%; padding: 14px; }
            .brand { padding-bottom: 13px; }
            nav { grid-template-columns: repeat(2, minmax(0, 1fr)); margin-top: 12px; }
            .nav-link { min-width: 0; padding: 0 9px; font-size: 12px; }
            .sidebar-user { display: none; }
            main { margin: 0; padding: 25px 16px 40px; }
        }
        @media (max-width: 480px) {
            .profile-details { grid-template-columns: 1fr; }
            .panel { padding: 18px; }
            h1 { font-size: 23px; }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand">
            <div class="brand-icon"><i class="fa-solid fa-building-columns" aria-hidden="true"></i></div>
            <div>
                <strong>LGU DAPA</strong>
                <small>Facilities &amp; Equipment<br>Reservation</small>
            </div>
        </div>
        <nav aria-label="Main navigation">
            <a class="nav-link" href="dashboard.php#top"><i class="fa-solid fa-house" aria-hidden="true"></i>Dashboard</a>
            <a class="nav-link" href="dashboard.php#resources"><i class="fa-solid fa-building" aria-hidden="true"></i>Browse Resources</a>
            <a class="nav-link" href="dashboard.php#myReservations"><i class="fa-solid fa-calendar-check" aria-hidden="true"></i>My Reservations</a>
            <a class="nav-link active" href="profile.php" aria-current="page"><i class="fa-solid fa-user" aria-hidden="true"></i>Profile</a>
        </nav>
        <div class="sidebar-user">
            <?php echo htmlspecialchars($_SESSION['full_name'] ?? 'User', ENT_QUOTES, 'UTF-8'); ?>
            <small><?php echo htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></small>
        </div>
    </aside>

    <main>
        <span class="eyebrow">ACCOUNT</span>
        <h1>Profile</h1>
        <p class="intro">Manage your account details and password.</p>

        <section class="panel" aria-labelledby="profileHeading">
            <h2 id="profileHeading">Your account</h2>

            <?php if ($statusMessage): ?>
                <div class="alert <?php echo htmlspecialchars($statusMessage[2], ENT_QUOTES, 'UTF-8'); ?>" role="status">
                    <strong><?php echo htmlspecialchars($statusMessage[0], ENT_QUOTES, 'UTF-8'); ?>.</strong>
                    <?php echo htmlspecialchars($statusMessage[1], ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <div class="profile-details">
                <div class="detail">
                    <label>Full name</label>
                    <strong><?php echo htmlspecialchars($_SESSION['full_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong>
                </div>
                <div class="detail">
                    <label>Email address</label>
                    <strong><?php echo htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?></strong>
                </div>
            </div>

            <hr class="divider">
            <h2>Change password</h2>
            <form method="post" action="../auth/change_password.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-group">
                    <label for="currentPassword">Current password</label>
                    <input type="password" id="currentPassword" name="current_password" autocomplete="current-password" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">New password</label>
                    <input type="password" id="newPassword" name="new_password" autocomplete="new-password" minlength="8" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Confirm new password</label>
                    <input type="password" id="confirmPassword" name="confirm_password" autocomplete="new-password" minlength="8" required>
                </div>
                <button class="submit" type="submit"><i class="fa-solid fa-key" aria-hidden="true"></i> Change password</button>
            </form>
        </section>

        <section class="panel danger-zone" aria-labelledby="deleteHeading">
            <h2 id="deleteHeading">Delete account</h2>
            <p class="danger-description">Deleting your account is permanent. Your account and reservation records will be removed, and you will be signed out.</p>

            <?php if ($deleteMessage): ?>
                <div class="alert error" role="alert">
                    <?php echo htmlspecialchars($deleteMessage, ENT_QUOTES, 'UTF-8'); ?>
                </div>
            <?php endif; ?>

            <form id="deleteAccountForm" method="post" action="../auth/delete_account.php">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'], ENT_QUOTES, 'UTF-8'); ?>">
                <div class="form-group">
                    <label for="deletePassword">Confirm with your current password</label>
                    <input type="password" id="deletePassword" name="current_password" autocomplete="current-password" required>
                </div>
                <button class="delete-button" type="submit"><i class="fa-solid fa-trash" aria-hidden="true"></i> Delete my account</button>
            </form>
        </section>
    </main>

    <dialog class="delete-dialog" id="deleteConfirmDialog" aria-labelledby="deleteDialogTitle" aria-describedby="deleteDialogDescription">
        <div class="dialog-mark"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i></div>
        <span class="dialog-kicker">PERMANENT ACTION</span>
        <h2 id="deleteDialogTitle">Permanently delete your account and reservations?</h2>
        <p class="dialog-description" id="deleteDialogDescription">This cannot be undone. Your account and reservation records will be removed.</p>
        <div class="dialog-actions">
            <button class="dialog-cancel" id="cancelDelete" type="button">Keep my account</button>
            <button class="dialog-confirm" id="confirmDelete" type="button">Delete permanently</button>
        </div>
    </dialog>

    <script>
        const deleteForm = document.getElementById('deleteAccountForm');
        const deleteDialog = document.getElementById('deleteConfirmDialog');
        const cancelDelete = document.getElementById('cancelDelete');
        const confirmDelete = document.getElementById('confirmDelete');

        deleteForm.addEventListener('submit', function (event) {
            event.preventDefault();
            deleteDialog.showModal();
        });

        cancelDelete.addEventListener('click', function () {
            deleteDialog.close();
        });

        confirmDelete.addEventListener('click', function () {
            HTMLFormElement.prototype.submit.call(deleteForm);
        });

        deleteDialog.addEventListener('click', function (event) {
            if (event.target === deleteDialog) {
                deleteDialog.close();
            }
        });
    </script>
</body>
</html>
