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
	<title>Foros - Foro E</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?php include "page/header.php"; ?>
	<main>

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
		// Comprueba si se ha proporcionado un ID de foro en la URL.
		if (isset($_GET['id'])) {

			// Convierte el ID del foro a un entero para validarlo.
			$forum_id = intval($_GET['id']);
			// Verifica si el ID del foro es un número entero positivo.
			if ($forum_id > 0) {
				// Comprueba si se está solicitando un post específico.
				if (isset($_GET['post'])) {
					$post_id = $_GET['post'];
					// Si el parámetro 'post' es 'new', muestra el formulario para crear un nuevo post.
					if ($post_id === 'new') {
						// Si el usuario no ha iniciado sesión, lo redirige.
						if (!is_logged()) {
							$_SESSION['error'] = "Primero debes iniciar sesión.";
							header("Location: ../usuario.php?login");
							exit;
						} else {
							// Incluye el formulario de creación de post.
							include "page/forum_post_create.php";
						}
					} else {
						// Si no es 'new', intenta ver un post específico.
						$post_id = intval($post_id);
						// Verifica que el ID del post sea válido.
						if ($post_id > 0) {
							// Incluye la vista del post.
							include "page/post_view.php";
						} else {
							// Si el ID del post es inválido, redirige de vuelta al foro.
							$_SESSION['error'] = "Post inválido.";
							header("Location: foros.php?id=" . $forum_id);
							exit;
						}
					}
				} else {
					// Si no se especifica un post, muestra la lista de posts del foro.
					include "page/forum_load.php";
				}
			} else {
				// Si el ID del foro es inválido, redirige a la lista principal de foros.
				$_SESSION['error'] = "Foro inválido o no existe.";
				header("Location: foros.php");
				exit;
			}
			// Si no se pasa ningún parámetro GET, muestra la lista de todos los foros.
		} elseif (empty($_GET)) {
			// Incluye la lista de foros.
			include "page/forums_list.php";
			// Si los parámetros no coinciden con ninguna de las opciones válidas.
		} else {
			// Establece un error y redirige a la página principal de foros.
			$_SESSION['error'] = "Solicitud inválida.";
			header("Location: foros.php");
			exit;
		}
		?>

		<div class="ad"><!-- Placeholder para publicidad --></div>

	</main>
	<?php include "page/footer.php"; ?>
</body>

</html>