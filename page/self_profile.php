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

<div class="frow self-actions">
	<?php
		require_once('php/session_manager.php');
		echo "<h2>Hola, " . get_username(get_id()) . "!</h2>";
	?>
	<a href="?sign-out"><button class="btn ylw">Cerrar sesión</button></a>
</div>

	<?php if ( is_admin() == 1 ): ?>
		<br> <hr> <br>

		<div class="fcol self-actions">
			<h2>Control de admin</h2>
			<br>
			<div class="frow self-actions">
				<a href="create.php"><button class="btn grn">Nuevo foro, noticia, articulo</button></a>
			</div>
		</div>
	<?php endif; ?>

<br> <hr> <br>

<p>Mi rol: <b><?php echo is_admin() == 1 ? "Administrador" : "Usuario" ; ?></b> </p>
<p>Mis likes:</p>
<p>Mis posts:</p>
<p>Mis comentarios:</p>

<br> <hr> <br>

<div class="frow self-actions">
	<div>
		<h2>Area de peligro</h2>
		<small>Para eliminar tu usuario espera 10 segundos.</small>
	</div>
	<button class="btn red" disabled>Eliminar cuenta</button>
</div>
<script>
	document.title = "Perfil de <?php echo get_username(get_id()) ?> - Foro E"
	setTimeout(() => {
		const btn = document.querySelector('button.btn.red');
		if (!btn) return;
		btn.removeAttribute('disabled');
		btn.addEventListener('click', () => {
			window.location.href = 'usuario.php?delete-user=<?php echo get_id(); ?>';
		});
    }, 1000);
</script>
