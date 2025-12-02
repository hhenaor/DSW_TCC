<?php
// Inicia la sesión solo si no hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

require_once 'php/article_manager.php';

$article_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$article_item = null;
$error_msg = "";

if ($article_id > 0) {
	$article_item = get_article_by_id($article_id);
	if (!$article_item) {
		$error_msg = "El artículo no existe.";
	}
} else {
	$error_msg = "ID de artículo inválido.";
}

if ($error_msg) {
	$_SESSION['error'] = $error_msg;
	header("Location: inicio.php");
	exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo htmlspecialchars($article_item['title']); ?> - Foro E</title>
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

		<div class="fcol self-actions">
			<?php if (!empty($article_item['link_url'])): ?>
				<img src="<?php echo htmlspecialchars($article_item['link_url']) ?>" alt="Imagen del artículo" class="post-thumbnail">
			<?php endif; ?>

			<div>
				<h2><?php echo htmlspecialchars($article_item['title']) ?></h2>
				<small>Publicado por <strong><?php echo htmlspecialchars($article_item['username']) ?></strong></small>
			</div>
		</div>

		<br> <hr> <br>

		<div class="fcol post-content">
			<p><?php echo nl2br(htmlspecialchars($article_item['content'])) ?></p>
		</div>

		<br> <hr> <br>

		<!-- Sección de Comentarios -->
		<div id="comments">
			<h3>Comentarios</h3>
			<?php
			$comments = get_article_comments_list($article_id);
			if (empty($comments)) {
				echo "<p>No hay comentarios aún.</p>";
			} else {
				foreach ($comments as $comment) {
					echo "<div class='post-box'>"; // Reutilizando estilo de post-box por ahora
					echo "<small><b>" . htmlspecialchars($comment['username']) . "</b> dice:</small>";
					echo "<p>" . nl2br(htmlspecialchars($comment['content'])) . "</p>";
					echo "</div>";
				}
			}
			?>
		</div>

		<br>

		<!-- Botón o Formulario de Comentario -->
		<?php if (isset($_GET['comment']) && $_GET['comment'] === 'new'): ?>
			<?php if (isset($_SESSION['user_id'])): ?>
				<div class="fcol">
					<h3>Agregar Comentario</h3>
					<form action="php/comment_manager.php" method="post" class="fcol">
						<input type="hidden" name="content_type" value="article">
						<input type="hidden" name="content_id" value="<?php echo $article_id; ?>">

						<label for="content">Tu comentario:</label>
						<textarea id="content" name="content" rows="4" required></textarea>

						<div class="frow">
							<button type="submit" class="btn grn">Publicar Comentario</button>
							<a href="articulo.php?id=<?php echo $article_id; ?>" class="btn red sml" style="margin-left: 1em; text-decoration: none; text-align: center;">Cancelar</a>
						</div>
					</form>
				</div>
			<?php else: ?>
				<div class="error-box">
					<p>Debes <a href="usuario.php?login">iniciar sesión</a> para comentar.</p>
				</div>
			<?php endif; ?>
		<?php else: ?>
			<div class="frow">
				<a href="articulo.php?id=<?php echo $article_id; ?>&comment=new">
					<button class="btn blu">Comentar</button>
				</a>
			</div>
		<?php endif; ?>

		<br> <hr> <br>

		<div class="frow self-actions">
			<a href="inicio.php">
				<button class="btn grn">Volver al inicio</button>
			</a>
		</div>

		<div class="ad"><!-- Placeholder para publicidad --></div>

	</main>
	<?php include "page/footer.php"; ?>
</body>

</html>
