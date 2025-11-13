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
	<title>Inicio - Foro E</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<?php include "page/header.php"; ?>
	<main>

		<?php if (isset($_SESSION['success'])): ?>
			<div class="success-box">
				<h3>Aviso</h3>
				<p><?php echo $_SESSION['success']; ?></p>
			</div>
			<?php unset($_SESSION['success']); endif; ?>

		<?php if (isset($_SESSION['error'])): ?>
			<div class="error-box">
				<h3>Error</h3>
				<p><?php echo $_SESSION['error']; ?></p>
			</div>
			<?php unset($_SESSION['error']); endif; ?>

		<h2>Explora nuestros foros <a href="foros.php">aquí</a></h2>
		<hr>
		<?php include "page/daily_news.php"; ?>
		<hr>
		<?php include "page/daily_article.php"; ?>
		<div class="ad"><!-- Placeholder para publicidad --></div>
		<?php // Incluye la publicación del usuario. ?>
		<?php // Incluye la publicación del usuario. ?>
		<?php // Incluye la publicación del usuario. ?>
		<?php // Incluye la publicación del usuario. ?>
	</main>
	<?php include "page/footer.php"; ?>
</body>

</html>
