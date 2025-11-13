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

<h1>Acceso</h1>

<p>Ingresa tus credenciales para comparitr en los foros</p>
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

<form class="fcol" action="php/login_manager.php" method="post">

	<b><label for="username">Nombre de usuario</label></b>
	<input id="username" name="username" type="text" required>

	<b><label for="password">Contraseña</label></b>
	<input id="password" name="password" type="password" required>

	<button type="submit" class="btn blu">Acceder</button>

	<p>¿No tienes un usuario? <a href="usuario.php?register">Registrate aquí</a></p>

</form>
