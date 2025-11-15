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

	if ( !(isset($_SESSION['username']) && !empty($_SESSION['username']) && isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) ) {
		$_SESSION['error'] = "Sin permisos.";
		Header("Location: index.php");
		exit;
	}

	require_once 'php/session_manager.php';

	if ( is_admin() == 0 ) {
		$_SESSION['error'] = "Sin permisos.";
		Header("Location: index.php");
		exit;
	}

?>

<h1>Crear contenido</h1>

<br>

<?php if ( isset($_SESSION['success']) ): ?>
	<div class="success-box">
		<h3>Aviso</h3>
		<p><?php echo $_SESSION['success'] ?></p>
	</div>
<?php endif; unset($_SESSION['success']) ?>

<?php if ( isset($_SESSION['error']) ): ?>
	<div class="error-box">
		<h3>Error</h3>
		<p><?php echo $_SESSION['error'] ?></p>
	</div>
<?php endif; unset($_SESSION['error']) ?>

<form class="fcol" action="php/admin_manager.php" method="post">

	<b><label for="content_type">Tipo de contenido</label></b>
	<select id="content_type" name="content_type">
		<option value="1">Foro</option>
		<option value="2">Noticia</option>
		<option value="3">Artículo</option>
	</select>

	<b><label for="thumb">Miniatura</label></b> <small>(Opcional)</small>
	<input id="thumb" name="thumb" type="url">

	<b><label for="title">Titulo</label></b>
	<input id="title" name="title" type="text">

	<b><label for="content">Contenido / Descripción</label></b>
	<textarea id="content" name="content"> </textarea>

	<button type="submit" class="btn grn">Crear</button>

</form>
