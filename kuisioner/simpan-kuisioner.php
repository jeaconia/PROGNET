<?php
session_start();
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nim_mahasiswa = $_POST['nim_mahasiswa'];
    $nip_dosen = $_POST['nip_dosen'];
    $saran = $_POST['saran'];

    // Insert ke tabel kuisioner
    $sql_kuisioner = "INSERT INTO kuisioner (nim_mahasiswa, nip_dosen, saran, is_filled) VALUES (?, ?, ?, 1)";
    $stmt_kuisioner = $conn->prepare($sql_kuisioner);
    $stmt_kuisioner->bind_param("sss", $nim_mahasiswa, $nip_dosen, $saran);

    if ($stmt_kuisioner->execute()) {
        $kuisioner_id = $stmt_kuisioner->insert_id;

        // Simpan jawaban
        foreach ($_POST['jawaban'] as $pertanyaan_id => $value) {
            if (is_array($value)) {
                // Untuk checkbox (multiple pilihan)
                foreach ($value as $pilihan_id) {
                    $sql_jawaban = "INSERT INTO jawaban (kuisioner_id, pertanyaan_id, pilihan_id, nip_dosen) VALUES (?, ?, ?, ?)";
                    $stmt_jawaban = $conn->prepare($sql_jawaban);
                    $stmt_jawaban->bind_param("iiis", $kuisioner_id, $pertanyaan_id, $pilihan_id, $nip_dosen);
                    $stmt_jawaban->execute();
                }
            } elseif (is_numeric($value)) {
                // Untuk dropdown atau radio
                $sql_jawaban = "INSERT INTO jawaban (kuisioner_id, pertanyaan_id, pilihan_id, nip_dosen) VALUES (?, ?, ?, ?)";
                $stmt_jawaban = $conn->prepare($sql_jawaban);
                $stmt_jawaban->bind_param("iiis", $kuisioner_id, $pertanyaan_id, $value, $nip_dosen);
                $stmt_jawaban->execute();
            } else {
                // Untuk textbox
                $sql_jawaban = "INSERT INTO jawaban (kuisioner_id, pertanyaan_id, jawaban_teks, nip_dosen) VALUES (?, ?, ?, ?)";
                $stmt_jawaban = $conn->prepare($sql_jawaban);
                $stmt_jawaban->bind_param("iiss", $kuisioner_id, $pertanyaan_id, $value, $nip_dosen);
                $stmt_jawaban->execute();
            }
        }

        echo "<script>
            alert('Kuisioner berhasil disimpan!');
            window.location.href = '../login-mahasiswa/home.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menyimpan kuisioner.');
        </script>";
    }

    $stmt_kuisioner->close();
}

$conn->close();
?>
