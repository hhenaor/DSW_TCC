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

<h1>Nuevo post</h1>


	<?php
		require_once('php/content_manager.php');
		require_once('php/session_manager.php');

		if ( forum_exists($_GET['id']) == 0 ) {
			$_SESSION['error'] = "El foro no existe.";
			Header("Location: ../foros.php");
			exit;
		}

		echo "<p>Crear nuevo post para <b>" . get_forum_name($_GET['id']) . "</b> publicando como <b>" . get_username(get_id()) . "</b></p>";
	?>

<br>

<?php  if ( isset($_SESSION['success']) ): ?>
	<div class="success-box">
		<h3>Aviso</h3>
		<p><?php echo $_SESSION['success'] ?></p>
	</div>
<?php endif; unset($_SESSION['success']) ?>

<?php  if ( isset($_SESSION['error']) ): ?>
	<div class="error-box">
		<h3>Error</h3>
		<p><?php echo $_SESSION['error'] ?></p>
	</div>
<?php endif; unset($_SESSION['error']) ?>

<form class="fcol" action="php/post_manager.php" method="post">

	<input type="hidden" name="forum_id" value="<?php echo $_GET['id']; ?>">

	<b><label for="title">Titulo de publicación</label></b>
	<input id="title" name="title" type="text" required>

	<b><label for="content">Contenido del post </label></b>
	<textarea id="content" name="content" required> </textarea>

	<b><label for="link_url">Link de imagen (opcional)</label></b>
	<input id="link_url" name="link_url" type="url" placeholder="https://ejemplo.com/imagen.jpg">

	<button type="submit" class="btn grn">Publicar</button>
	<small>Al publicar acepta los terminos y condiciones del sitio web, <a href="tyc.php">leelos aquí</a> </small>

</form>
