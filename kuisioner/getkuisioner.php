<?php
session_start();
include '../config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-dosen/login.php");
    exit;
}

// Fetch data from kuisioner table
$sql = "SELECT * FROM kuisioner";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = array();

    // Fetch associative array
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Return JSON response
    header('Content-Type: application/json');
    echo json_encode($data);
} else {
    echo json_encode([]);
}

$conn->close();
?>

