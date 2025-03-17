<?php
session_start();
session_destroy();
echo "<script>sessionStorage.removeItem('logado'); window.location.href = '../../login.html';</script>";
?>
