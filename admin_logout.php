<?php
session_start();
unset($_SESSION['admin_logado'], $_SESSION['admin_usuario'], $_SESSION['admin_id']);
header("Location: admin_login.php");
exit;