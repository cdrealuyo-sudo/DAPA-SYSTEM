<?php

session_start();

require_once "../db.php";


// Get login information
$email = trim($_POST['loginEmail'] ?? '');
$password = $_POST['loginPassword'] ?? '';


// Check if fields are empty
if ($email === '' || $password === '') {

    header("Location: ../pages/login.html?error=empty");
    exit();

}


// Find the user
$stmt = $conn->prepare(
    "SELECT id, full_name, email, password, role
     FROM users
     WHERE email = ?"
);

$stmt->bind_param("s", $email);

$stmt->execute();

$result = $stmt->get_result();


// Check if account exists
if ($result->num_rows === 0) {

    $stmt->close();
    $conn->close();

    header("Location: ../pages/login.html?error=invalid");
    exit();

}


$user = $result->fetch_assoc();


// Check password
if (!password_verify($password, $user['password'])) {

    $stmt->close();
    $conn->close();

    header("Location: ../pages/login.html?error=invalid");
    exit();

}


// Save user information in PHP session
$_SESSION['user_id'] = $user['id'];
$_SESSION['full_name'] = $user['full_name'];
$_SESSION['email'] = $user['email'];
$_SESSION['role'] = $user['role'];


// Close database connection
$stmt->close();
$conn->close();


// Send the login information to JavaScript localStorage
// because your existing frontend uses localStorage

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login Successful</title>

</head>


<body>

<script>

    // Save login state for the existing frontend
    localStorage.setItem(
        "lguLoggedIn",
        "true"
    );


    localStorage.setItem(
        "lguUserName",
        <?php echo json_encode($user['full_name']); ?>
    );


    localStorage.setItem(
        "lguUserEmail",
        <?php echo json_encode($user['email']); ?>
    );


    localStorage.setItem(
        "lguUserRole",
        <?php echo json_encode($user['role']); ?>
    );


    // Redirect according to account type

    <?php if ($user['role'] === 'staff'): ?>

        // Staff goes to staff dashboard
        window.location.href = "../pages/staff.html";

    <?php else: ?>

        // Regular user goes to their personal dashboard
       window.location.href = "../user/dashboard.php";

    <?php endif; ?>

</script>

</body>

</html>