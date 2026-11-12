<?php

session_start();

require_once "db.php";

header("Content-Type: application/json");


// LOGIC: User must be logged in
if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        "success" => false,
        "message" => "Please login before making a reservation."
    ]);

    exit();
}


// LOGIC: Get the logged-in user's ID
$user_id = $_SESSION['user_id'];


// LOGIC: Get reservation information
$item = trim($_POST['item'] ?? '');
$date = trim($_POST['date'] ?? '');
$time = trim($_POST['time'] ?? '');


// LOGIC: Check if all fields are filled
if ($item === '' || $date === '' || $time === '') {

    echo json_encode([
        "success" => false,
        "message" => "Please complete all reservation details."
    ]);

    exit();
}


// LOGIC: Find the resource in the database
$stmt = $conn->prepare(
    "SELECT id
     FROM resources
     WHERE name = ?
     LIMIT 1"
);

$stmt->bind_param("s", $item);

$stmt->execute();

$result = $stmt->get_result();


// LOGIC: Resource does not exist
if ($result->num_rows === 0) {

    $stmt->close();

    echo json_encode([
        "success" => false,
        "message" => "Resource not found."
    ]);

    exit();
}


$resource = $result->fetch_assoc();

$resource_id = $resource['id'];

$stmt->close();


// LOGIC: Check if the same resource is already reserved
$check = $conn->prepare(
    "SELECT id
     FROM reservations
     WHERE resource_id = ?
     AND reservation_date = ?
     AND reservation_time = ?
     AND status IN ('Pending', 'Approved')
     LIMIT 1"
);

$check->bind_param(
    "iss",
    $resource_id,
    $date,
    $time
);

$check->execute();

$checkResult = $check->get_result();


// LOGIC: Prevent double booking
if ($checkResult->num_rows > 0) {

    $check->close();

    echo json_encode([
        "success" => false,
        "message" => "This resource is already reserved for the selected date and time."
    ]);

    exit();
}

$check->close();


// LOGIC: Save the reservation
$stmt = $conn->prepare(
    "INSERT INTO reservations
    (
        user_id,
        resource_id,
        reservation_date,
        reservation_time,
        status
    )
    VALUES
    (
        ?,
        ?,
        ?,
        ?,
        'Pending'
    )"
);

$stmt->bind_param(
    "iiss",
    $user_id,
    $resource_id,
    $date,
    $time
);


// LOGIC: Check if reservation was successfully saved
if ($stmt->execute()) {

    // LOGIC: Get the ID of the newly inserted reservation
    $new_id = $stmt->insert_id;

    // LOGIC: Check if the inserted reservation really exists
    $verify = $conn->prepare(
        "SELECT id
         FROM reservations
         WHERE id = ?
         LIMIT 1"
    );

    $verify->bind_param("i", $new_id);
    $verify->execute();

    $verifyResult = $verify->get_result();

    if ($verifyResult->num_rows > 0) {

        echo json_encode([
            "success" => true,
            "message" => "Reservation submitted successfully.",
            "insert_id" => $new_id,
            "verified" => true
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => "Reservation was inserted but could not be verified.",
            "insert_id" => $new_id,
            "verified" => false
        ]);
    }

    $verify->close();

} else {

    echo json_encode([
        "success" => false,
        "message" => "Failed to save reservation.",
        "error" => $stmt->error
    ]);

}

$stmt->close();

$conn->close();

?>