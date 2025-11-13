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

<div class="frow self-actions">

	<?php
		require_once('php/session_manager.php');

		if ( user_exists($_GET['id']) == 0 ) {
			$_SESSION['error'] = "Usuario no existe.";
			Header("Location: ../index.php");
			exit;
		}

		echo "<h2>Perfil de " . get_username($_GET['id']) . "</h2>";
	?>

</div>

<br> <hr> <br>

<p>Rol de usuario: <b><?php echo get_role($_GET['id']) ? "Administrador" : "Usuario" ; ?></b> </p>
<p>Likes del usuario:</p>
<p>Posts del usuario:</p>
<p>Comentarios del usuario:</p>

<script>
	document.title = "Perfil de <?php echo get_username($_GET['id']) ?> - Foro E"
</script>
