<?php
session_start();

// Hapus semua sesi
session_unset();
session_destroy();

// Redirect ke halaman login admin
header("Location: ../login-admin/login.php");
exit;
?>