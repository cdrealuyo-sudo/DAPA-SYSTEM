<?php

require_once "../db.php";

// Get the information from the registration form
$full_name = trim($_POST['registerName'] ?? '');
$email = trim($_POST['registerEmail'] ?? '');
$password = $_POST['registerPassword'] ?? '';
$confirm_password = $_POST['registerConfirmPassword'] ?? '';


// Modern popup function
function showPopup($title, $message, $type = "success", $redirect = null) {

    if ($type === "success") {
        $icon = "✓";
        $iconClass = "success";
    } else {
        $icon = "!";
        $iconClass = "error";
    }

    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>LGU Dapa</title>

        <style>

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: Arial, sans-serif;
                min-height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                background: linear-gradient(135deg, #eef3f8, #dce6ef);
            }

            .popup-overlay {
                position: fixed;
                inset: 0;
                background: rgba(15, 23, 42, 0.55);
                backdrop-filter: blur(6px);
                display: flex;
                justify-content: center;
                align-items: center;
                padding: 20px;
            }

            .popup {
                width: 100%;
                max-width: 420px;
                background: white;
                border-radius: 22px;
                padding: 35px 30px 30px;
                text-align: center;
                box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
                animation: popupShow 0.35s ease;
            }

            @keyframes popupShow {
                from {
                    opacity: 0;
                    transform: translateY(25px) scale(0.92);
                }

                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .icon {
                width: 70px;
                height: 70px;
                margin: 0 auto 20px;
                border-radius: 50%;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 36px;
                font-weight: bold;
            }

            .icon.success {
                background: #dcfce7;
                color: #16a34a;
            }

            .icon.error {
                background: #fee2e2;
                color: #dc2626;
            }

            .popup h2 {
                font-size: 24px;
                color: #1e293b;
                margin-bottom: 10px;
            }

            .popup p {
                color: #64748b;
                font-size: 15px;
                line-height: 1.6;
                margin-bottom: 25px;
            }

            .popup button {
                width: 100%;
                border: none;
                padding: 13px 20px;
                border-radius: 10px;
                font-size: 15px;
                font-weight: bold;
                cursor: pointer;
                transition: 0.2s;
            }

            .popup button.success {
                background: #2563eb;
                color: white;
            }

            .popup button.success:hover {
                background: #1d4ed8;
                transform: translateY(-1px);
            }

            .popup button.error {
                background: #dc2626;
                color: white;
            }

            .popup button.error:hover {
                background: #b91c1c;
                transform: translateY(-1px);
            }

        </style>
    </head>

    <body>

        <div class='popup-overlay'>

            <div class='popup'>

                <div class='icon $iconClass'>
                    $icon
                </div>

                <h2>$title</h2>

                <p>$message</p>

                <button
                    class='$iconClass'
                    onclick=\"" . ($redirect ? "window.location.href='$redirect'" : "history.back()") . "\"
                >
                    OK
                </button>

            </div>

        </div>

    </body>
    </html>
    ";

    exit;
}


// Check if all fields are filled
if ($full_name === '' || $email === '' || $password === '' || $confirm_password === '') {

    showPopup(
        "Missing Information",
        "Please fill in all the required fields.",
        "error",
        "../pages/register.html"
    );
}


// Check if passwords match
if ($password !== $confirm_password) {

    showPopup(
        "Passwords Don't Match",
        "The passwords you entered do not match. Please try again.",
        "error",
        "../pages/register.html"
    );
}


// Check if email already exists
$check = $conn->prepare("SELECT id FROM users WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {

    $check->close();

    showPopup(
        "Email Already Registered",
        "This email is already registered. Please use another email.",
        "error",
        "../pages/register.html"
    );
}

$check->close();


// Encrypt the password before saving
$hashed_password = password_hash($password, PASSWORD_DEFAULT);


// Save the user to the database
$stmt = $conn->prepare(
    "INSERT INTO users (full_name, email, password, role)
     VALUES (?, ?, ?, 'user')"
);

$stmt->bind_param(
    "sss",
    $full_name,
    $email,
    $hashed_password
);


// Registration successful
if ($stmt->execute()) {

    showPopup(
        "Registration Successful!",
        "Your account has been created successfully. You can now login.",
        "success",
        "../pages/login.html"
    );

} else {

    showPopup(
        "Registration Failed",
        "Something went wrong while creating your account. Please try again.",
        "error",
        "../pages/register.html"
    );

}


$stmt->close();
$conn->close();

?>