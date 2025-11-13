<?php
	if (session_status() === PHP_SESSION_NONE) { session_start(); }

	$loader = '';

	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$loader = realpath($_SERVER['SCRIPT_FILENAME']);
	} elseif (isset($_SERVER['PHP_SELF'])) {
		$scriptName = basename($_SERVER['PHP_SELF']);
		$loader = realpath(__DIR__ . '/' . $scriptName) ?: '';
	}

	if ($loader !== '' && realpath(__FILE__) === $loader) {
		$_SESSION['error'] = "Archivo no accesible.";
		Header("Location: ../index.php");
		exit;
	}
?>

<footer class="frow">
	<div class="fcol">
		<p><b>Foros LLC.</b></p>
		<p>Bolivar, Cartagena, Colombia | Kr12 3b-45</p>
		<p>Télefono: +57 301 123 4567</p>
		<p>Correo: hola@foros.com</p>
	</div>
	<div class="fcol right">
		<p>Siguenos en </p>
		<p>Facebook</p>
		<p>Twitter</p>
		<p>Instagram</p>
	</div>
</footer>