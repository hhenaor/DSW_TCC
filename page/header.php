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

<header class="frow">
	<a href=".."><h1>Foro-E</h1></a>

	<?php
		require_once('php/session_manager.php');
		if ( is_logged() ):
	?>
	<div>
		<!-- <a ><button class="btn grn">Nuevo post SIN FUNCION</button></a> -->
		<a href="usuario.php?id=<?php echo get_id(); ?>"><button class="btn blu">Mi usuario</button></a>
	</div>
	<?php else: ?>
		<a href="usuario.php?login"><button class="btn grn">Acceder</button></a>
	<?php endif; ?>

</header>