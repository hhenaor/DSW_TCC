<?php
// Inicia la sesión solo si no hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

require_once 'db_manager.php';
require_once 'session_manager.php';

// Este script solo debe procesar solicitudes de tipo POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// Se verifica que el usuario haya iniciado sesión.
	if (!is_logged()) {
		$_SESSION['error'] = "Debes iniciar sesión para comentar.";
		header("Location: ../usuario.php?login");
		exit;
	}

	// Se valida que el contenido no esté vacío.
	if (isset($_POST['content']) && empty(trim($_POST['content']))) {
		$_SESSION['error'] = "El comentario no puede estar vacío.";
		// Redirigir de vuelta a la página anterior si es posible
		if (isset($_SERVER['HTTP_REFERER'])) {
			header("Location: " . $_SERVER['HTTP_REFERER']);
		} else {
			header("Location: ../inicio.php");
		}
		exit;
	}

	$content_raw = trim($_POST['content']);
	$content_type = isset($_POST['content_type']) ? $_POST['content_type'] : '';
	$content_id = isset($_POST['content_id']) ? intval($_POST['content_id']) : 0;

	// Validar tipo de contenido
	$allowed_types = ['news', 'post', 'article'];
	if (!in_array($content_type, $allowed_types)) {
		$_SESSION['error'] = "Tipo de contenido inválido.";
		header("Location: ../inicio.php");
		exit;
	}

	// Validar ID de contenido
	if ($content_id <= 0) {
		$_SESSION['error'] = "ID de contenido inválido.";
		header("Location: ../inicio.php");
		exit;
	}

	// Validar longitud del comentario
	if (mb_strlen($content_raw) > 1000) {
		$_SESSION['error'] = "El comentario es demasiado largo (máximo 1000 caracteres).";
		if (isset($_SERVER['HTTP_REFERER'])) {
			header("Location: " . $_SERVER['HTTP_REFERER']);
		} else {
			header("Location: ../inicio.php");
		}
		exit;
	}

	$content = filter_var($content_raw, FILTER_SANITIZE_STRING);

	try {
		connect(['dbname' => 'foro_e']);

		transaction();

		$sql = "INSERT INTO comments (content, user_id, content_type, content_id) VALUES (?, ?, ?, ?)";
		sql($sql, [$content, get_id(), $content_type, $content_id]);

		commit();

		$_SESSION['success'] = "Comentario publicado.";

		// Redirigir a la página correcta según el tipo de contenido
		if ($content_type === 'news') {
			header("Location: ../noticia.php?id=" . $content_id);
		} elseif ($content_type === 'post') {
			// Asumiendo que existe una página para ver posts
			header("Location: ../foros.php?id=1&post=" . $content_id); // Esto habría que ajustarlo para saber el foro_id si es necesario
		} else {
			header("Location: ../inicio.php");
		}
		exit;

	} catch (PDOException $e) {
		rollback();
		$_SESSION['error'] = geterror($e);
		if (isset($_SERVER['HTTP_REFERER'])) {
			header("Location: " . $_SERVER['HTTP_REFERER']);
		} else {
			header("Location: ../inicio.php");
		}
		exit;
	} finally {
		disconnect();
	}

} else {
	$_SESSION['error'] = "Solicitud inválida.";
	header("Location: ../inicio.php");
	exit;
}
