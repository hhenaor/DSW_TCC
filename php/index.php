<?php
	if (session_status() === PHP_SESSION_NONE) { session_start(); }
	$_SESSION['error'] = "Directorio no accesible.";
	Header("Location: ../index.php");
	exit;
