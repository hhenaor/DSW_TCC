<?php
// Inicia la sesión solo si no hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Requiere el manejador de la base de datos.
require_once 'db_manager.php';

// Este script solo debe procesar solicitudes de tipo POST.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	// Se sanean los datos de entrada para prevenir ataques XSS.
	$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
	$password = $_POST['password'];

	try {
		// Se establece la conexión con la base de datos 'foro_e'.
		connect([
			'dbname' => 'foro_e'
		]);

		// Se busca al usuario en la base de datos por su nombre de usuario.
		$user = query(
			"SELECT user_id, username, password_hash FROM users WHERE username = ?",
			[$username]
		);

		// Si la consulta no devuelve ningún usuario, las credenciales son incorrectas.
		if (empty($user)) {
			$_SESSION['error'] = "Usuario o contraseña incorrectos.";
			header("Location: ../usuario.php?login");
			exit;
		}

		// Se verifica que la contraseña proporcionada coincida con el hash almacenado.
		if (!password_verify($password, $user[0]['password_hash'])) {
			$_SESSION['error'] = "Usuario o contraseña incorrectos.";
			header("Location: ../usuario.php?login");
			exit;
		}

		// Si las credenciales son correctas, se inicia la sesión del usuario.
		// Se guardan el ID y el nombre de usuario en las variables de sesión.
		$_SESSION['user_id'] = $user[0]['user_id'];
		$_SESSION['username'] = $user[0]['username'];

		// Se redirige al usuario a la página de inicio con un mensaje de bienvenida.
		$_SESSION['success'] = "¡Hola de nuevo, " . $_SESSION['username'] . "!";
		header("Location: ../index.php");
		exit;

	} catch (PDOException $e) {
		// Si ocurre un error en la base de datos, se guarda el mensaje y se redirige.
		$_SESSION['error'] = geterror($e);
		header("Location: ../usuario.php?login");
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