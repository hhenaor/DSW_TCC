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

<h1>Registro</h1>

<p>Registrate para publicar en los foros</p>
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

<form class="fcol" action="php/register_manager.php" method="post">

	<b><label for="username">Nombre de usuario</label></b>
	<input id="username" name="username" type="text">

	<b><label for="email">Correo</label></b>
	<input id="email" name="email" type="email">

	<b><label for="password">Contraseña</label></b>
	<input id="password" name="password" type="password" required>

	<b><label for="password_check">Contraseña (Check seguro)</label></b>
	<input id="password_check" name="password_check" type="password" required>

	<button type="submit" class="btn grn">Registrarte</button>

	<p>¿Ya tienes un usuario? <a href="usuario.php?login">Accede aquí</a></p>

</form>
