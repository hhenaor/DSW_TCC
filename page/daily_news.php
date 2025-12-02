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

<div id="news" class="fcol">
	<h2>Noticia del día</h2>

	<?php
	require_once 'php/news_manager.php';
	$latest_news = get_latest_news();
	?>

	<?php if ($latest_news): ?>
		<?php
			$news_id = $latest_news['news_id'];
			$likes = get_news_likes($news_id);
			$comments = get_news_comments($news_id);
		?>
		<div id="box">
			<h3><?php echo htmlspecialchars($latest_news['title']); ?></h3>
			<i><?php echo htmlspecialchars(substr($latest_news['content'], 0, 150)) . (strlen($latest_news['content']) > 150 ? '...' : ''); ?></i>
			<div class="frow">
				<div class="frow">
					<!-- <p><b><?php echo $likes; ?></b> likes</p> -->
					<p><b><?php echo $comments; ?></b> comentarios</p>
				</div>
				<a href="noticia.php?id=<?php echo $news_id; ?>"><button class="btn blu sml">Leer más</button></a>
			</div>
		</div>
	<?php else: ?>
		<p>No hay noticias recientes.</p>
	<?php endif; ?>
</div>