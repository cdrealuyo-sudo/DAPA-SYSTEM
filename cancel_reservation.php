<?php

session_start();

require_once "db.php";

header("Content-Type: application/json");


/* LOGIC: User must be logged in */

if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit();
}


$user_id = $_SESSION['user_id'];

$id = intval($_POST['id'] ?? 0);


/* LOGIC: Check reservation ID */

if ($id <= 0) {

    echo json_encode([
        "success" => false,
        "message" => "Invalid reservation."
    ]);

    exit();
}


/* LOGIC: Make sure the reservation belongs
   to the currently logged-in user */

$check = $conn->prepare(
    "SELECT id, status
     FROM reservations
     WHERE id = ?
     AND user_id = ?
     LIMIT 1"
);

$check->bind_param(
    "ii",
    $id,
    $user_id
);

$check->execute();

$result = $check->get_result();


if ($result->num_rows === 0) {

    $check->close();

    echo json_encode([
        "success" => false,
        "message" => "Reservation not found."
    ]);

    exit();
}


$reservation = $result->fetch_assoc();

$check->close();


/* LOGIC: Only Pending reservations
   can be cancelled */

if ($reservation['status'] !== 'Pending') {

    echo json_encode([
        "success" => false,
        "message" => "Only pending reservations can be cancelled."
    ]);

    exit();
}


/* LOGIC: Change Pending to Cancelled */

$stmt = $conn->prepare(
    "UPDATE reservations
     SET status = 'Cancelled'
     WHERE id = ?
     AND user_id = ?"
);

$stmt->bind_param(
    "ii",
    $id,
    $user_id
);


if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Reservation cancelled successfully."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => "Failed to cancel reservation.",
        "error" => $stmt->error
    ]);

}


$stmt->close();
$conn->close();

?>