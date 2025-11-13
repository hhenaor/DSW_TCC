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

<div id="news" class="fcol">
	<h2>Noticia del día</h2>
	<p>Temporalmente deshabilitadas.</p>
	<!-- <div id="box">

		<h3>titulo</h3>
		<i>Descripcion</i>
		<div class="frow">
			<div class="frow">
				<p><b>0</b> likes</p>
				<p><b>0</b> comentarios</p>
			</div>
			<a href=""><button class="btn blu sml">Leer más</button></a>
		</div>

	</div> -->
</div>