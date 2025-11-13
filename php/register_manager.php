<?php
// Inicia la sesión solo si no hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Requiere el manejador de la base de datos.
require_once 'db_manager.php';

// Este script solo debe procesar solicitudes de tipo POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// Se valida que ninguno de los campos requeridos esté vacío.
	if (
		(isset($_POST['username']) && empty($_POST['username'])) ||
		(isset($_POST['email']) && empty($_POST['email'])) ||
		(isset($_POST['password']) && empty($_POST['password'])) ||
		(isset($_POST['password_check']) && empty($_POST['password_check']))
	) {
		$_SESSION['error'] = "Uno o más campos están vacíos.";
		header("Location: ../usuario.php?register");
		exit;
	}

	// Se obtienen los datos del formulario, eliminando espacios en blanco al inicio y al final.
	$username_raw = isset($_POST['username']) ? trim($_POST['username']) : '';
	$email_raw = isset($_POST['email']) ? trim($_POST['email']) : '';
	$password_raw = isset($_POST['password']) ? $_POST['password'] : '';
	$password_check_raw = isset($_POST['password_check']) ? $_POST['password_check'] : '';

	// --- Validaciones de negocio para los datos de entrada ---

	// El nombre de usuario no debe exceder los 15 caracteres.
	if (mb_strlen($username_raw) > 15) {
		$_SESSION['error'] = "El nombre de usuario debe tener menos de 15 caracteres.";
		header("Location: ../usuario.php?register");
		exit;
	}

	// El correo electrónico no debe exceder los 30 caracteres.
	if (mb_strlen($email_raw) > 30) {
		$_SESSION['error'] = "El correo electrónico debe tener menos de 30 caracteres.";
		header("Location: ../usuario.php?register");
		exit;
	}

	// La contraseña debe tener una longitud de entre 8 y 30 caracteres.
	if (strlen($password_raw) < 8 || strlen($password_raw) > 30) {
		$_SESSION['error'] = "La contraseña debe tener entre 8 y 30 caracteres.";
		header("Location: ../usuario.php?register");
		exit;
	}

	// Las contraseñas introducidas deben coincidir.
	if ($password_raw !== $password_check_raw) {
		$_SESSION['error'] = "Las contraseñas no coinciden.";
		header("Location: ../usuario.php?register");
		exit;
	}

	// Se verifica la complejidad de la contraseña.
	$digits = preg_match_all('/\d/', $password_raw);
	$uppers = preg_match_all('/[A-Z]/', $password_raw);
	$symbols = preg_match_all('/[^a-zA-Z0-9]/', $password_raw);

	if ($digits < 2 || $uppers < 2 || $symbols < 2) {
		$_SESSION['error'] = "La contraseña debe contener al menos 2 números, 2 mayúsculas y 2 símbolos.";
		header("Location: ../usuario.php?register");
		exit;
	}

	// --- Saneamiento final de los datos antes de usarlos en la base de datos ---
	$username = filter_var($username_raw, FILTER_SANITIZE_STRING);
	$email = filter_var($email_raw, FILTER_SANITIZE_EMAIL);
	$password = $password_raw; // La contraseña no se sanea para poder hacer el hash correctamente.

	try {
		// Se establece la conexión con la base de datos.
		connect([
			'dbname' => 'foro_e'
		]);

		// Se comprueba si ya existe un usuario con el mismo nombre o correo electrónico.
		$existingUser = query(
			"SELECT user_id FROM users WHERE username = ? OR email = ?",
			[$username, $email]
		);

		if (!empty($existingUser)) {
			$_SESSION['error'] = "El nombre de usuario o el correo electrónico ya están registrados.";
			header("Location: ../usuario.php?register");
			exit;
		}

		// Se genera un hash seguro de la contraseña.
		$password_hash = password_hash($password, PASSWORD_DEFAULT);

		// Se inicia una transacción para asegurar la integridad de los datos.
		transaction();

		// Se inserta el nuevo usuario en la base de datos.
		$stmt = sql(
			"INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)",
			[$username, $email, $password_hash]
		);

		// Si la inserción fue exitosa, se confirman los cambios.
		commit();

		$_SESSION['success'] = "El usuario se creó correctamente. Ahora puede iniciar sesión.";
		header("Location: ../usuario.php?login");
		exit;

	} catch (PDOException $e) {
		// Si ocurre un error, se revierten los cambios.
		rollback();

		// Se guarda el error y se redirige al formulario de registro.
		$_SESSION['error'] = geterror($e);
		header("Location: ../usuario.php?register");
		exit;
	}

} else {
	// Si la solicitud no es de tipo POST, se considera inválida.
	$_SESSION['error'] = "Solicitud inválida.";
	header("Location: ..");
	exit;
}