<?php
// Inicia la sesión solo si no hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Verifica si el usuario ha iniciado sesión.
// Si no existen las variables de sesión 'username' o 'user_id', o están vacías,
// se deniega el acceso y se redirige al inicio.
if (!(isset($_SESSION['username']) && !empty($_SESSION['username']) && isset($_SESSION['user_id']) && !empty($_SESSION['user_id']))) {
	$_SESSION['error'] = "Sin permisos.";
	header("Location: index.php");
	exit;
}

// Requiere el manejador de sesiones para utilizar sus funciones.
require_once 'php/session_manager.php';

// Verifica si el usuario tiene permisos de administrador.
// Si no es administrador, se deniega el acceso y se redirige al inicio.
if (is_admin() == 0) {
	$_SESSION['error'] = "Sin permisos.";
	header("Location: index.php");
	exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ADMIN | Crear - Foro E</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?php // Incluye el encabezado de la página. ?>
	<?php include "page/header.php"; ?>
	<main>

		<?php
		// Incluye el contenido principal para la creación de entidades por el administrador.
		include "page/admin_create.php";
		?>

	</main>
	<?php // Incluye el pie de página. ?>
	<?php include "page/footer.php"; ?>
</body>

</html>