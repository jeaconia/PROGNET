<?php
session_start();
include '../config.php';

// Pastikan mahasiswa sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login-mahasiswa/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim_mahasiswa = $_POST['nim_mahasiswa'];
    $nip_dosen = $_POST['nip_dosen'];
    $saran = $_POST['saran'];

    // Debug input dari form
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";

    // Insert ke tabel kuisioner
    $sql_kuisioner = "INSERT INTO kuisioner (nim_mahasiswa, nip_dosen, saran) VALUES (?, ?, ?)";
    $stmt_kuisioner = $conn->prepare($sql_kuisioner);

    if (!$stmt_kuisioner) {
        die("Error prepare kuisioner: " . $conn->error);
    }

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

                        if (!$stmt_jawaban) {
                            die("Error prepare jawaban (checkbox): " . $conn->error);
                        }

                        $stmt_jawaban->bind_param("iiis", $kuisioner_id, $pertanyaan_id, $pilihan_id, $jawaban_teks);

                        if (!$stmt_jawaban->execute()) {
                            die("Error insert jawaban (checkbox): " . $stmt_jawaban->error);
                        }

                        $stmt_jawaban->close();
                    }
                } elseif (is_numeric($value)) {
                    // Dropdown or radio: single pilihan_id
                    $pilihan_id = (int)$value;

                    $sql_jawaban = "INSERT INTO jawaban (kuisioner_id, pertanyaan_id, pilihan_id, jawaban_teks) VALUES (?, ?, ?, ?)";
                    $stmt_jawaban = $conn->prepare($sql_jawaban);

                    if (!$stmt_jawaban) {
                        die("Error prepare jawaban (dropdown/radio): " . $conn->error);
                    }

                    $stmt_jawaban->bind_param("iiis", $kuisioner_id, $pertanyaan_id, $pilihan_id, $jawaban_teks);

                    if (!$stmt_jawaban->execute()) {
                        die("Error insert jawaban (dropdown/radio): " . $stmt_jawaban->error);
                    }

                    $stmt_jawaban->close();
                } else {
                    // Textbox: jawaban_teks
                    $jawaban_teks = $value;

                    $sql_jawaban = "INSERT INTO jawaban (kuisioner_id, pertanyaan_id, pilihan_id, jawaban_teks) VALUES (?, ?, ?, ?)";
                    $stmt_jawaban = $conn->prepare($sql_jawaban);

                    if (!$stmt_jawaban) {
                        die("Error prepare jawaban (textbox): " . $conn->error);
                    }

                    $stmt_jawaban->bind_param("iiis", $kuisioner_id, $pertanyaan_id, $pilihan_id, $jawaban_teks);

                    if (!$stmt_jawaban->execute()) {
                        die("Error insert jawaban (textbox): " . $stmt_jawaban->error);
                    }

                    $stmt_jawaban->close();
                }
            }
        }

        echo "<script>
            alert('Kuisioner dan jawaban berhasil disimpan!');
            window.location.href = '../login-mahasiswa/home.php';
        </script>";
    } else {
        die("Error insert kuisioner: " . $stmt_kuisioner->error);
    }

    $stmt_kuisioner->close();
}

$conn->close();
?>
