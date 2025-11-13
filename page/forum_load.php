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

<div class="fcol self-actions">

	<?php
		require_once('php/content_manager.php');
		require_once('php/session_manager.php');

		if ( forum_exists($_GET['id']) == 0 ) {
			$_SESSION['error'] = "El foro no existe.";
			Header("Location: ../foros.php");
			exit;
		}

		echo "<p>Foro sobre</p>";
		echo "<h2>" . get_forum_name($_GET['id']) . "</h2>";
		echo "<small>" . get_forum_description($_GET['id']) . "</small>";
	?>

</div>

<?php if ( is_logged() ): ?>
	<br> <hr> <br>

	<div class="frow self-actions">
		<p>Publicar en este foro</p>
		<a href="foros.php?id=<?php echo $_GET['id'] ?>&post=new"> <button class="btn grn">Nuevo post</button></a>
	</div>
<?php endif; ?>
<br> <hr> <br>

<?php
	include "page/post_list.php";
?>

<script>
	document.title = "Foro de <?php echo get_forum_name($_GET['id']) ?> - Foro E"
</script>
