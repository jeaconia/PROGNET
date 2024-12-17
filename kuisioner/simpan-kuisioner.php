<?php
session_start();
include '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-mahasiswa/login.php");
    exit;
}

// Fetch questions for the current kuisioner_id
$kuisioner_id = $_SESSION['kuisioner_id'];

// Handle form submission for answers
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim_mahasiswa = $_POST['nim_mahasiswa'];
    $nip_dosen = $_POST['nip_dosen'];
    $saran = $_POST['saran']; // Assuming 'saran' is always available

    // Insert new kuisioner into the database
    $sql_kuisioner = "INSERT INTO kuisioner (nim_mahasiswa, nip_dosen, saran) VALUES (?, ?, ?)";
    $stmt_kuisioner = $conn->prepare($sql_kuisioner);
    $stmt_kuisioner->bind_param("sss", $nim_mahasiswa, $nip_dosen, $saran);

    if ($stmt_kuisioner->execute()) {
        $kuisioner_id = $stmt_kuisioner->insert_id;

        // Now insert answers related to this kuisioner
        foreach ($_POST as $key => $value) {
            // Skip if it's not a valid question field
            if (strpos($key, 'pertanyaan_') !== 0) continue;

            $pertanyaan_id = (int) str_replace('pertanyaan_', '', $key);
            $jawaban_teks = null;
            $pilihan_id = null;

            // Determine if the answer is a dropdown, checkbox, radio, or textbox
            if (isset($_POST["jawaban_{$pertanyaan_id}_pilihan"])) {
                // Dropdown, radio, or checkbox
                $pilihan_id = (int) $_POST["jawaban_{$pertanyaan_id}_pilihan"];
            } else {
                // Textbox
                $jawaban_teks = $value;
            }

            // Insert answer into `jawaban` table
            $sql_jawaban = "INSERT INTO jawaban (kuisioner_id, pertanyaan_id, pilihan_id, jawaban_teks) VALUES (?, ?, ?, ?)";
            $stmt_jawaban = $conn->prepare($sql_jawaban);
            $stmt_jawaban->bind_param("iiis", $kuisioner_id, $pertanyaan_id, $pilihan_id, $jawaban_teks);

            if (!$stmt_jawaban->execute()) {
                echo "<script>
                    alert('Terjadi error saat menyimpan jawaban: " . addslashes($stmt_jawaban->error) . "');
                </script>";
            }

            $stmt_jawaban->close();
        }

        echo "<script>
            alert('Kuisioner berhasil disimpan!');
            window.location.href = '../login-mahasiswa/home.html';
        </script>";

    } else {
        echo "<script>
            alert('Terjadi error saat menyimpan kuisioner: " . addslashes($stmt_kuisioner->error) . "');
        </script>";
    }

    $stmt_kuisioner->close();
}

$conn->close();
?>