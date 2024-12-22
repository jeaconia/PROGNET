<?php
session_start();
include '../config.php';

// Periksa apakah admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id']; // ID pertanyaan yang ingin diedit
$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pertanyaan = $_POST['nama_pertanyaan'];
    $tipe_pertanyaan = $_POST['tipe_pertanyaan'];

    // Update pertanyaan
    $sql = "UPDATE pertanyaan SET nama_pertanyaan = ?, tipe_pertanyaan = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $nama_pertanyaan, $tipe_pertanyaan, $id);

    if ($stmt->execute()) {
        $message = "Pertanyaan berhasil diperbarui!";
    } else {
        $message = "Error: " . $stmt->error;
    }
}

$sql = "SELECT nama_pertanyaan, tipe_pertanyaan FROM pertanyaan WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nama_pertanyaan, $tipe_pertanyaan);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pertanyaan</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <div class="container">
        <h2>Edit Pertanyaan</h2>
        <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>
        <form method="POST">
            <label for="nama_pertanyaan">Nama Pertanyaan:</label>
            <input type="text" name="nama_pertanyaan" id="nama_pertanyaan" value="<?php echo htmlspecialchars($nama_pertanyaan); ?>" required>

            <label for="tipe_pertanyaan">Tipe Pertanyaan:</label>
            <select name="tipe_pertanyaan" id="tipe_pertanyaan" required>
                <option value="dropdown" <?php if ($tipe_pertanyaan == 'dropdown') echo 'selected'; ?>>Dropdown</option>
                <option value="checkbox" <?php if ($tipe_pertanyaan == 'checkbox') echo 'selected'; ?>>Checkbox</option>
                <option value="radio" <?php if ($tipe_pertanyaan == 'radio') echo 'selected'; ?>>Radio</option>
                <option value="textbox" <?php if ($tipe_pertanyaan == 'textbox') echo 'selected'; ?>>Textbox</option>
            </select>
            <button type="submit">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
