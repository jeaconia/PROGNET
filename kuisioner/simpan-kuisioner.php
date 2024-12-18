<?php
session_start();
include '../config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-mahasiswa/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim_mahasiswa = $_POST['nim_mahasiswa'];
    $nip_dosen = $_POST['nip_dosen'];
    $saran = $_POST['saran'];

    // Insert ke tabel kuisioner
    $sql_kuisioner = "INSERT INTO kuisioner (nim_mahasiswa, nip_dosen, saran) VALUES (?, ?, ?)";
    $stmt_kuisioner = $conn->prepare($sql_kuisioner);
    $stmt_kuisioner->bind_param("sss", $nim_mahasiswa, $nip_dosen, $saran);

    if ($stmt_kuisioner->execute()) {
        $kuisioner_id = $stmt_kuisioner->insert_id;

        // Process answers from 'jawaban'
        if (isset($_POST['jawaban'])) {
            foreach ($_POST['jawaban'] as $pertanyaan_id => $value) {
                $pertanyaan_id = (int)$pertanyaan_id;
                $pilihan_id = null;
                $jawaban_teks = null;

                if (is_array($value)) {
                    // Checkbox: multiple pilihan_id
                    foreach ($value as $selected_pilihan_id) {
                        $pilihan_id = (int)$selected_pilihan_id;
                        $sql_jawaban = "INSERT INTO jawaban (kuisioner_id, pertanyaan_id, pilihan_id, jawaban_teks) VALUES (?, ?, ?, ?)";
                        $stmt_jawaban = $conn->prepare($sql_jawaban);
                        $stmt_jawaban->bind_param("iiis", $kuisioner_id, $pertanyaan_id, $pilihan_id, $jawaban_teks);
                        $stmt_jawaban->execute();
                        $stmt_jawaban->close();
                    }
                } elseif (is_numeric($value)) {
                    // Dropdown or radio: single pilihan_id
                    $pilihan_id = (int)$value;
                    $sql_jawaban = "INSERT INTO jawaban (kuisioner_id, pertanyaan_id, pilihan_id, jawaban_teks) VALUES (?, ?, ?, ?)";
                    $stmt_jawaban = $conn->prepare($sql_jawaban);
                    $stmt_jawaban->bind_param("iiis", $kuisioner_id, $pertanyaan_id, $pilihan_id, $jawaban_teks);
                    $stmt_jawaban->execute();
                    $stmt_jawaban->close();
                } else {
                    // Textbox: jawaban_teks
                    $jawaban_teks = $value;
                    $sql_jawaban = "INSERT INTO jawaban (kuisioner_id, pertanyaan_id, pilihan_id, jawaban_teks) VALUES (?, ?, ?, ?)";
                    $stmt_jawaban = $conn->prepare($sql_jawaban);
                    $stmt_jawaban->bind_param("iiis", $kuisioner_id, $pertanyaan_id, $pilihan_id, $jawaban_teks);
                    $stmt_jawaban->execute();
                    $stmt_jawaban->close();
                }
            }
        }

        echo "<script>
            alert('Kuisioner dan jawaban berhasil disimpan!');
            window.location.href = '../login-mahasiswa/home.html';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menyimpan kuisioner: " . addslashes($stmt_kuisioner->error) . "');
        </script>";
    }

    $stmt_kuisioner->close();
}

$conn->close();
?>
