<?php
// Inicia la sesión solo si no hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Requiere los manejadores de base de datos y de sesión.
require_once 'db_manager.php';
require_once 'session_manager.php';

// Este script solo debe procesar solicitudes de tipo POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// Se verifica que el usuario haya iniciado sesión antes de permitir crear una publicación.
	if (!is_logged()) {
		$_SESSION['error'] = "Debes iniciar sesión para crear una publicación.";
		header("Location: ../usuario.php?login");
		exit;
	}

	// Se valida que los campos principales no estén vacíos.
	if (
		(isset($_POST['title']) && empty($_POST['title'])) ||
		(isset($_POST['content']) && empty($_POST['content']))
	) {
		$_SESSION['error'] = "El título y el contenido de la publicación son requeridos.";
		header("Location: ../foros.php");
		exit;
	}

	// Se obtienen y limpian los datos del formulario.
	$title_raw = isset($_POST['title']) ? trim($_POST['title']) : '';
	$content_raw = isset($_POST['content']) ? trim($_POST['content']) : '';
	$link_url_raw = isset($_POST['link_url']) ? trim($_POST['link_url']) : '';
	$forum_id = isset($_POST['forum_id']) ? intval($_POST['forum_id']) : 0;

	// --- Validaciones adicionales de los datos ---

	// El título no debe exceder los 255 caracteres.
	if (mb_strlen($title_raw) > 255) {
		$_SESSION['error'] = "El título debe tener menos de 255 caracteres.";
		header("Location: ../foros.php");
		exit;
	}

	// El contenido debe tener una longitud mínima y máxima.
	if (mb_strlen($content_raw) < 10) {
		$_SESSION['error'] = "El contenido debe tener al menos 10 caracteres.";
		header("Location: ../foros.php");
		exit;
	}
	if (mb_strlen($content_raw) > 5000) {
		$_SESSION['error'] = "El contenido debe tener menos de 5000 caracteres.";
		header("Location: ../foros.php");
		exit;
	}

	// El ID del foro debe ser un número válido y positivo.
	if ($forum_id <= 0) {
		$_SESSION['error'] = "El foro no es válido.";
		header("Location: ../foros.php");
		exit;
	}

	// --- Saneamiento final de los datos ---
	$title = filter_var($title_raw, FILTER_SANITIZE_STRING);
	$content = filter_var($content_raw, FILTER_SANITIZE_STRING);
	$link_url = !empty($link_url_raw) ? filter_var($link_url_raw, FILTER_SANITIZE_URL) : null;

	try {
		// Se establece la conexión con la base de datos.
		connect([
			'dbname' => 'foro_e'
		]);

		// Se comprueba que el foro al que se quiere asociar la publicación realmente existe.
		$forum = query(
			"SELECT forum_id FROM forums WHERE forum_id = ?",
			[$forum_id]
		);

		if (empty($forum)) {
			$_SESSION['error'] = "El foro especificado no existe.";
			header("Location: ../foros.php");
			exit;
		}

		// Se inicia una transacción para asegurar la integridad de los datos.
		transaction();

		// Se inserta la nueva publicación en la base de datos.
		$stmt = sql(
			"INSERT INTO posts (title, content, link_url, user_id, forum_id) VALUES (?, ?, ?, ?, ?)",
			[$title, $content, $link_url, get_id(), $forum_id]
		);

		// Si la inserción fue exitosa, se confirman los cambios.
		commit();

		$_SESSION['success'] = "Publicación creada correctamente.";
		header("Location: ../foros.php?id=" . $forum_id);
		exit;

	} catch (PDOException $e) {
		// Si ocurre un error, se revierten los cambios.
		rollback();

		// Se guarda el error y se redirige al usuario.
		$_SESSION['error'] = geterror($e);
		header("Location: ../foros.php");
		exit;

	} finally {
		// Se asegura que la conexión a la base de datos siempre se cierre.
		disconnect();
	}

} else {
	// Si la solicitud no es de tipo POST, se considera inválida.
	$_SESSION['error'] = "Solicitud inválida.";
	header("Location: ..");
	exit;
}