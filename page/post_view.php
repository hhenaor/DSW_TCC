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

<?php
	require_once('php/db_manager.php');
	require_once('php/session_manager.php');

	$forum_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
	$post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;

	if ($forum_id <= 0 || $post_id <= 0) {
		$_SESSION['error'] = "Post o foro inválido.";
		Header("Location: ../foros.php");
		exit;
	}

	try {
		connect([
			'dbname' => 'foro_e'
		]);

		// Obtener el post con información del autor
		$post = query(
			"SELECT p.post_id, p.title, p.content, p.link_url, p.user_id, p.forum_id, u.username
			 FROM posts p
			 INNER JOIN users u ON p.user_id = u.user_id
			 WHERE p.post_id = ? AND p.forum_id = ?",
			[$post_id, $forum_id]
		);

		if (empty($post)) {
			$_SESSION['error'] = "El post no existe o pertenece a otro foro.";
			Header("Location: ../foros.php?id=" . $forum_id);
			exit;
		}

		$post = $post[0];

		disconnect();

		?>

		<div class="fcol self-actions">
			<?php if (!empty($post['link_url'])): ?>
				<img src="<?php echo htmlspecialchars($post['link_url']) ?>" alt="Imagen del post" class="post-thumbnail">
			<?php endif; ?>

			<div>
				<h2><?php echo htmlspecialchars($post['title']) ?></h2>
				<small>Por <strong><?php echo htmlspecialchars($post['username']) ?></strong></small>
			</div>
		</div>

		<br> <hr> <br>

		<div class="fcol post-content">
			<p><?php echo nl2br(htmlspecialchars($post['content'])) ?></p>
		</div>

		<br> <hr> <br>

		<div class="frow self-actions">
			<a href="foros.php?id=<?php echo $forum_id ?>">
				<button class="btn grn">Volver al foro</button>
			</a>
		</div>

		<?php

	} catch (PDOException $e) {
		echo "<p>Error al cargar el post: " . geterror($e) . "</p>";
	}
