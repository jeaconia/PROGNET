<?php
session_start();
include '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login-admin/login.php");
    exit();
}

$message = ""; // Variabel untuk pesan sukses/error

// Fungsi untuk menyimpan pertanyaan dan pilihan ke database
function simpanPertanyaan($conn, $nama_pertanyaan, $tipe_pertanyaan, $pilihan = []) {
    global $message;

    // Tambahkan pertanyaan ke tabel pertanyaan
    $sql_pertanyaan = "INSERT INTO pertanyaan (nama_pertanyaan, tipe_pertanyaan) VALUES (?, ?)";
    $stmt_pertanyaan = $conn->prepare($sql_pertanyaan);
    $stmt_pertanyaan->bind_param("ss", $nama_pertanyaan, $tipe_pertanyaan);

    if ($stmt_pertanyaan->execute()) {
        $pertanyaan_id = $stmt_pertanyaan->insert_id; // ID pertanyaan yang baru ditambahkan

        // Jika tipe pertanyaan memerlukan pilihan, tambahkan ke tabel pilihan
        if (in_array($tipe_pertanyaan, ['dropdown', 'checkbox', 'radio']) && !empty($pilihan)) {
            $sql_pilihan = "INSERT INTO pilihan (pertanyaan_id, pilihan) VALUES (?, ?)";
            $stmt_pilihan = $conn->prepare($sql_pilihan);

            foreach ($pilihan as $item) {
                $stmt_pilihan->bind_param("is", $pertanyaan_id, $item);
                $stmt_pilihan->execute();
            }
            $stmt_pilihan->close();
        }

        $message = "Pertanyaan berhasil ditambahkan! <br><a href='view-pertanyaan.php'>Kelola Pertanyaan</a>";
    } else {
        $message = "Error: " . $stmt_pertanyaan->error;
    }

    $stmt_pertanyaan->close();
}

// Proses jika form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_pertanyaan = $_POST['nama_pertanyaan'];
    $tipe_pertanyaan = $_POST['tipe_pertanyaan'];
    $pilihan = isset($_POST['pilihan']) ? array_filter($_POST['pilihan']) : []; // Ambil hanya pilihan yang tidak kosong

    if (!empty($nama_pertanyaan) && !empty($tipe_pertanyaan)) {
        simpanPertanyaan($conn, $nama_pertanyaan, $tipe_pertanyaan, $pilihan);
    } else {
        $message = "Nama pertanyaan dan tipe pertanyaan harus diisi.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pertanyaan dan Pilihan</title>
    <link rel="stylesheet" href="../styles.css">
    <script>
        // Fungsi untuk menambahkan input pilihan secara dinamis
        function addChoiceField() {
            const container = document.getElementById('choice-container');
            const div = document.createElement('div');
            div.classList.add('choice-item');
            div.innerHTML = `
                <input type="text" name="pilihan[]" placeholder="Masukkan pilihan">
                <button type="button" onclick="removeChoiceField(this)">Hapus</button>
            `;
            container.appendChild(div);
        }


        // Fungsi untuk menghapus input pilihan
        function removeChoiceField(button) {
            button.parentElement.remove();
        }
    </script>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-container">
            <ul class="navbar-links">
                <li><a href="../login-admin/home.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="navbar-logo">
            <img src="../img/logo-teknologi-informasi-universitas-udayana-ti-unud-jhonarendra.png" alt="Logo" class="logo">
        </div>
    </nav>

    <div class="container-add">
        <h2>Tambah Pertanyaan dan Pilihan</h2>

        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label for="nama_pertanyaan">Nama Pertanyaan:</label>
            <input type="text" name="nama_pertanyaan" id="nama_pertanyaan" required>

            <label for="tipe_pertanyaan">Tipe Pertanyaan:</label>
            <select name="tipe_pertanyaan" id="tipe_pertanyaan" required>
                <option value="">-- Pilih Tipe --</option>
                <option value="dropdown">Dropdown</option>
                <option value="checkbox">Checkbox</option>
                <option value="radio">Radio</option>
                <option value="textbox">Textbox</option>
            </select>

            <div id="choice-section">
                <h3>Tambahkan Pilihan (Opsional untuk Dropdown, Checkbox, atau Radio)</h3>
                <div id="choice-container">
                    <!-- Input awal untuk pilihan -->
                    <div class="choice-item">
                        <input type="text" name="pilihan[]" placeholder="Masukkan pilihan">
                        <button type="button" onclick="removeChoiceField(this)">Hapus</button>
                        <br><br>
                    </div>
                </div>
                <br>
                <button type="button" onclick="addChoiceField()">Tambah Pilihan</button>
            </div>
                <br>
            <button type="submit">Simpan</button>
        </form>
    </div>

    <footer id="footer">
        <div class="footer">
            <h2>Be the Next Generation</h2>
            <p>Copyright © 2024 AGS. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
