<?php

session_start();

require_once "db.php";

header("Content-Type: application/json");

// LOGIC: User must be logged in

if (!isset($_SESSION['user_id'])) {

    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);

    exit();

}

$user_id = $_SESSION['user_id'];

// LOGIC: Get only reservations belonging to the logged-in user

$stmt = $conn->prepare(
    "SELECT
        reservations.id,
        resources.name AS resource_name,
        resources.type AS resource_type,
        reservations.reservation_date,
        reservations.reservation_time,
        reservations.status,
        reservations.created_at
     FROM reservations
     INNER JOIN resources
        ON reservations.resource_id = resources.id
     WHERE reservations.user_id = ?
     ORDER BY reservations.created_at DESC"
);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$result = $stmt->get_result();

$reservations = [];

while ($row = $result->fetch_assoc()) {

    $reservations[] = $row;

}

echo json_encode([
    "success" => true,
    "reservations" => $reservations
]);

$stmt->close();

$conn->close();

?>