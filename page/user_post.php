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

<div class="post frow">
	<img src="https://picsum.photos/71/53" alt="" srcset="">
	<div class="fcol">

		<a href=""><h3>titulo</h3></a>
		<p>comentario</p>
		<div class="frow">
			<p><b>0</b> likes</p>
			<p><b>0</b> comentarios</p>
		</div>

	</div>
</div>