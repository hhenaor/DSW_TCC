<?php

	require_once 'php/db_manager.php';
	require_once 'php/session_manager.php';

	$forum_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

	if ($forum_id <= 0) {
		echo "<p>Foro inválido.</p>";
		return;
	}

	try {
		connect([
			'dbname' => 'foro_e'
		]);

		$posts = query(
			"SELECT p.post_id, p.title, p.content, p.link_url, p.user_id, u.username
			 FROM posts p
			 INNER JOIN users u ON p.user_id = u.user_id
			 WHERE p.forum_id = ?
			 ORDER BY p.post_id DESC",
			[$forum_id]
		);

		disconnect();

		echo "<h2>Posts del foro</h2>";

		if ($posts && !empty($posts)) {
			foreach ($posts as $post) {
				?>
				<article class="post-box frow">
					<?php if (!empty($post['link_url'])): ?>
						<img src="<?php echo htmlspecialchars($post['link_url']) ?>" alt="Imagen del post" class="post-thumbnail" width="200" height="100">
					<?php endif; ?>

					<div>
						<h3><?php echo htmlspecialchars($post['title']) ?></h3>
						<p><?php echo nl2br(htmlspecialchars(substr($post['content'], 0, 200))) ?><?php if (strlen($post['content']) > 200) echo "..."; ?></p>
						<small>Por <strong><?php echo htmlspecialchars($post['username']) ?></strong></small>
					</div>

					<a href="foros.php?id=<?php echo $forum_id ?>&post=<?php echo $post['post_id'] ?>">
						<button class="btn blu">Ver post</button>
					</a>
				</article>
				<?php
			}
		} else {
			?>
			<p>No hay posts en este foro. <a href="foros.php?id=<?php echo $forum_id ?>&post=new">Crea uno</a></p>
			<?php
		}

	} catch (PDOException $e) {
		echo "<p>Error al cargar los posts: " . geterror($e) . "</p>";
	}
