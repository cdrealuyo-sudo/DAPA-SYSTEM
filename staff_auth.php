<?php

session_start();


/*
 * LOGIC:
 * Check if the user is logged in
 * and has the Staff role.
 */

if (
    !isset($_SESSION['user_id']) ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'staff'
) {

    /*
     * LOGIC:
     * If JavaScript is requesting JSON,
     * return JSON instead of redirecting.
     */

    $accept =
        $_SERVER['HTTP_ACCEPT'] ?? '';


    if (
        strpos(
            $accept,
            'application/json'
        ) !== false
    ) {

        header(
            "Content-Type: application/json"
        );

        echo json_encode([

            "success" => false,

            "message" =>
                "Staff login required."

        ]);

        exit();

    }


    /*
     * LOGIC:
     * Normal browser access goes
     * back to the login page.
     */

    header(
    "Location: pages/login.html?error=staff"
   );

    exit();

}

?>