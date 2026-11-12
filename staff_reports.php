<?php

require_once "staff_auth.php";
require_once "db.php";

header("Content-Type: application/json");


/* =====================================================
   RESERVATION REPORTS
===================================================== */


/* LOGIC: Count total reservations */

$totalReservations = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM reservations"
);

if ($result) {

    $row = $result->fetch_assoc();

    $totalReservations =
        (int)$row['total'];

}


/* LOGIC: Count Pending reservations */

$pendingReservations = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM reservations
     WHERE status = 'Pending'"
);

if ($result) {

    $row = $result->fetch_assoc();

    $pendingReservations =
        (int)$row['total'];

}


/* LOGIC: Count Approved reservations */

$approvedReservations = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM reservations
     WHERE status = 'Approved'"
);

if ($result) {

    $row = $result->fetch_assoc();

    $approvedReservations =
        (int)$row['total'];

}


/* LOGIC: Count Rejected reservations */

$rejectedReservations = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM reservations
     WHERE status = 'Rejected'"
);

if ($result) {

    $row = $result->fetch_assoc();

    $rejectedReservations =
        (int)$row['total'];

}


/* LOGIC: Count Cancelled reservations */

$cancelledReservations = 0;

$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM reservations
     WHERE status = 'Cancelled'"
);

if ($result) {

    $row = $result->fetch_assoc();

    $cancelledReservations =
        (int)$row['total'];

}


/* =====================================================
   MOST RESERVED RESOURCES
===================================================== */

$resources = [];

$result = $conn->query(
    "SELECT
        resources.name AS resource_name,
        COUNT(reservations.id) AS total_reservations
     FROM resources
     LEFT JOIN reservations
        ON resources.id = reservations.resource_id
     GROUP BY resources.id, resources.name
     ORDER BY total_reservations DESC"
);

if ($result) {

    while ($row = $result->fetch_assoc()) {

        $resources[] = $row;

    }

}


/* =====================================================
   SEND REPORT DATA
===================================================== */

echo json_encode([

    "success" => true,

    "total" =>
        $totalReservations,

    "pending" =>
        $pendingReservations,

    "approved" =>
        $approvedReservations,

    "rejected" =>
        $rejectedReservations,

    "cancelled" =>
        $cancelledReservations,

    "resources" =>
        $resources

]);


$conn->close();

?>