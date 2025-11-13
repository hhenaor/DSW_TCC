<?php
// Inicia la sesión solo si no hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Usuario - Foro E</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?php include "page/header.php"; ?>
	<main id="users">

		<?php // Muestra un mensaje de éxito si existe en la sesión. ?>
		<?php if (isset($_SESSION['success'])): ?>
			<div class="success-box">
				<h3>Aviso</h3>
				<p><?php echo $_SESSION['success']; ?></p>
			</div>
			<?php unset($_SESSION['success']); endif; ?>

		<?php // Muestra un mensaje de error si existe en la sesión. ?>
		<?php if (isset($_SESSION['error'])): ?>
			<div class="error-box">
				<h3>Error</h3>
				<p><?php echo $_SESSION['error']; ?></p>
			</div>
			<?php unset($_SESSION['error']); endif; ?>

		<?php

		// Requiere el manejador de sesiones.
		require_once('php/session_manager.php');
		// Requiere el manejador de la base de datos.
		require_once('php/db_manager.php');

		// Comprueba si se está solicitando la página de registro.
		if (isset($_GET['register'])) {

			// Si el usuario ya ha iniciado sesión, lo redirige.
			if (is_logged()) {
				$_SESSION['error'] = "Ya has iniciado sesión.";
				header("Location: ../index.php");
				exit;
			} else {
				// Incluye el formulario de registro.
				include "page/user_register.php";
			}

		// Comprueba si se está solicitando la página de inicio de sesión.
		} else if (isset($_GET['login'])) {

			// Si el usuario ya ha iniciado sesión, lo redirige.
			if (is_logged()) {
				$_SESSION['error'] = "Ya has iniciado sesión.";
				header("Location: ../index.php");
				exit;
			} else {
				// Incluye el formulario de inicio de sesión.
				include "page/user_login.php";
			}

		// Comprueba si se está solicitando cerrar la sesión.
		} else if (isset($_GET['sign-out'])) {

			// Limpia las variables de sesión.
			session_unset();
			// Destruye la sesión.
			session_destroy();
			// Redirige al inicio.
			header("Location: ../index.php");
			exit;

		// Comprueba si se está solicitando eliminar un usuario (actualmente deshabilitado).
		} else if (isset($_GET['delete-user'])) {

			$_SESSION['error'] = "Temporalmente deshabilitado.";
			header("Location: usuario.php?id=" . get_id());
			exit;

		// Comprueba si se está solicitando ver un perfil de usuario con un ID válido.
		} else if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) {

			// Si el ID solicitado es el del propio usuario, muestra su perfil.
			if ($_GET['id'] === strval(get_id())) {
				include "page/self_profile.php";
			} else {
				// De lo contrario, muestra el perfil de otro usuario.
				include "page/user_profile.php";
			}

		// Si ninguna de las condiciones anteriores se cumple, es una solicitud inválida.
		} else {
			$_SESSION['error'] = "Solicitud inválida.";
			header("Location: ../index.php");
			exit;
		}
		?>

		<br>
		<hr> <br>

		<div class="ad"><!-- Placeholder para publicidad --></div>

	</main>
	<?php include "page/footer.php"; ?>
</body>

</html>