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

<div id="artc" class="fcol">
	<h2>Articulo destacado</h2>

	<?php
	require_once 'php/article_manager.php';
	$random_article = get_random_article();
	?>

	<?php if ($random_article): ?>
		<?php
			$article_id = $random_article['article_id'];
			$likes = get_article_likes($article_id);
			$comments = get_article_comments_count($article_id);
		?>
		<div id="box">
			<h3><?php echo htmlspecialchars($random_article['title']); ?></h3>
			<i><?php echo htmlspecialchars(substr($random_article['content'], 0, 150)) . (strlen($random_article['content']) > 150 ? '...' : ''); ?></i>
			<div class="frow">
				<div class="frow">
					<!-- <p><b><?php echo $likes; ?></b> likes</p> -->
					<p><b><?php echo $comments; ?></b> comentarios</p>
				</div>
				<a href="articulo.php?id=<?php echo $article_id; ?>"><button class="btn blu sml">Leer más</button></a>
			</div>
		</div>
	<?php else: ?>
		<p>No hay artículos disponibles.</p>
	<?php endif; ?>
</div>