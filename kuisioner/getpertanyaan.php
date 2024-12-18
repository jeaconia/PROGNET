<?php
session_start();
include '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-mahasiswa/login.php");
    exit;
}

// Fetch data from pertanyaan table
$sql = "SELECT * FROM pertanyaan";
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

