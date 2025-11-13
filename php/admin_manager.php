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

	// Se verifica que el usuario tenga permisos de administrador.
	// Si no es administrador, se le deniega el acceso y se redirige.
	if (is_admin() == 0) {
		$_SESSION['error'] = "Sin permisos.";
		header("Location: index.php");
		exit;
	}

	// Se valida que los campos principales no estén vacíos.
	if (
		(isset($_POST['content_type']) && empty($_POST['content_type'])) ||
		(isset($_POST['title']) && empty($_POST['title'])) ||
		(isset($_POST['content']) && empty($_POST['content']))
	) {
		$_SESSION['error'] = "Uno o más campos están vacíos.";
		header("Location: ../create.php");
		exit; // Se detiene la ejecución si hay campos vacíos.
	}

	// Se obtienen los datos del formulario POST, asignando un valor por defecto si no existen.
	$content_type_raw = isset($_POST['content_type']) ? $_POST['content_type'] : '';
	$url_raw = isset($_POST['thumb']) ? $_POST['thumb'] : '';
	$title_raw = isset($_POST['title']) ? trim($_POST['title']) : '';
	$content_raw = isset($_POST['content']) ? $_POST['content'] : '';

	// --- Validaciones adicionales de los datos ---

	// El tipo de contenido debe ser un valor entre 1 y 3.
	if ($content_type_raw < 1 || $content_type_raw > 3) {
		$_SESSION['error'] = "El tipo de contenido no es válido.";
		header("Location: ../create.php");
		exit;
	}

	// El título no debe exceder los 30 caracteres.
	if (mb_strlen($title_raw) > 30) {
		$_SESSION['error'] = "El título debe tener menos de 30 caracteres.";
		header("Location: ../create.php");
		exit;
	}

	// El contenido no debe exceder los 1000 caracteres.
	if (mb_strlen($content_raw) > 1000) {
		$_SESSION['error'] = "El tamaño del contenido es demasiado grande.";
		header("Location: ../create.php");
		exit;
	}

	// --- Saneamiento de los datos ---
	// Se limpian las variables para prevenir ataques (ej. XSS).
	$content_type = filter_var($content_type_raw, FILTER_SANITIZE_NUMBER_INT);
	$url = filter_var($url_raw, FILTER_SANITIZE_URL);
	$title = filter_var($title_raw, FILTER_SANITIZE_STRING);
	$content = filter_var($content_raw, FILTER_SANITIZE_STRING);

	try {
		// Se establece la conexión con la base de datos.
		connect([
			'dbname' => 'foro_e'
		]);

		// Se inicia una transacción para asegurar la integridad de los datos.
		// Si algo falla, se puede revertir toda la operación.
		transaction();

		// Se determina qué tipo de contenido crear según el valor de 'content_type'.
		switch ($content_type) {
			case 1: // Crear un nuevo foro.
				$stmt = sql(
					"INSERT INTO forums (name, link_url, description) VALUES (?, ?, ?)",
					[$title, $url, $content]
				);
				break;
			case 2: // Crear una nueva noticia.
				$stmt = sql(
					"INSERT INTO news (title, content, link_url, user_id) VALUES (?, ?, ?, ?)",
					[$title, $content, $url, get_id()]
				);
				break;
			case 3: // Crear un nuevo artículo.
				$stmt = sql(
					"INSERT INTO articles (title, content, link_url, user_id) VALUES (?, ?, ?, ?)",
					[$title, $content, $url, get_id()]
				);
				break;
			default:
				// Si el tipo de contenido no es válido, se establece un error.
				$_SESSION['error'] = "El tipo de contenido no es válido.";
				header("Location: ../create.php");
				exit;
		}

		// Si todas las operaciones de la base de datos fueron exitosas, se confirman los cambios.
		commit();

		$_SESSION['success'] = "Se creó el contenido correctamente.";
		header("Location: ../index.php"); // Redirigir a una página de éxito o al inicio.
		exit;

	} catch (PDOException $e) {
		// Si ocurre cualquier error durante la transacción, se revierten todos los cambios.
		rollback();

		// Se guarda el mensaje de error en la sesión y se redirige al formulario.
		$_SESSION['error'] = geterror($e);
		header("Location: ../create.php");
		exit;
	}

} else {
	// Si la solicitud no es de tipo POST, se considera inválida.
	$_SESSION['error'] = "Solicitud inválida.";
	header("Location: ..");
	exit;
}