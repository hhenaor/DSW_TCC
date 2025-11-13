<?php
// Inicia la sesión solo si no hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Requiere el manejador de la base de datos para las funciones que lo necesitan.
require_once 'db_manager.php';

/**
 * Verifica si el usuario ha iniciado sesión.
 *
 * Comprueba la existencia y el contenido de las variables de sesión 'username' y 'user_id'.
 *
 * @return bool Devuelve `true` si el usuario ha iniciado sesión, de lo contrario `false`.
 */
function is_logged(): bool
{
	if (isset($_SESSION['username']) && !empty($_SESSION['username']) && isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
		return true;
	} else {
		return false;
	}
}

/**
 * Obtiene el ID del usuario que ha iniciado sesión.
 *
 * @return int|null Devuelve el ID del usuario si ha iniciado sesión, o `null` si no lo ha hecho.
 */
function get_id(): ?int
{
	if (is_logged()) {
		return $_SESSION['user_id'];
	} else {
		return null;
	}
}

/**
 * Obtiene el rol de un usuario específico a partir de su ID.
 *
 * Se conecta a la base de datos para verificar si el usuario tiene el indicador de administrador.
 *
 * @param int $user_id El ID del usuario a consultar.
 * @return string Devuelve '1' si el usuario es administrador, de lo contrario '0'.
 */
function get_role(int $user_id): string
{
	connect([
		'dbname' => 'foro_e'
	]);

	$user = query(
		"SELECT admin FROM users WHERE user_id = ?",
		[$user_id]
	);

	disconnect();

	if ($user && !empty($user) && $user[0]['admin'] == 1) {
		return '1'; // Devuelve como string para coincidir con la declaración de tipo.
	} else {
		return '0'; // Devuelve como string para coincidir con la declaración de tipo.
	}
}

/**
 * Obtiene el nombre de usuario a partir de su ID.
 *
 * Esta función asume que el usuario con el ID proporcionado existe.
 *
 * @param int $user_id El ID del usuario.
 * @return string El nombre de usuario.
 */
function get_username(int $user_id): string
{
	connect([
		'dbname' => 'foro_e'
	]);

	$user = query(
		"SELECT username FROM users WHERE user_id = ?",
		[$user_id]
	);

	disconnect();

	return $user[0]['username'];
}

/**
 * Verifica si un usuario existe en la base de datos a partir de su ID.
 *
 * @param int $user_id El ID del usuario a verificar.
 * @return bool Devuelve `true` si el usuario existe, de lo contrario `false`.
 */
function user_exists(int $user_id): bool
{
	connect([
		'dbname' => 'foro_e'
	]);

	$user = query(
		"SELECT user_id FROM users WHERE user_id = ?",
		[$user_id]
	);

	disconnect();

	if ($user && !empty($user)) {
		return true;
	} else {
		return false;
	}
}

/**
 * Verifica si el usuario actualmente autenticado es un administrador.
 *
 * Es una función de conveniencia que utiliza get_id() y get_role().
 *
 * @return bool Devuelve `true` si el usuario es administrador, de lo contrario `false`.
 */
function is_admin(): bool
{
	$user_id = get_id();
	if ($user_id !== null && get_role($user_id) === '1') {
		return true;
	}
	return false;
}

// --- Bloque de seguridad ---
// Este código previene que el archivo sea accedido directamente desde el navegador.
$loader = '';

if (isset($_SERVER['SCRIPT_FILENAME'])) {
	$loader = realpath($_SERVER['SCRIPT_FILENAME']);
} elseif (isset($_SERVER['PHP_SELF'])) {
	$scriptName = basename($_SERVER['PHP_SELF']);
	$loader = realpath(__DIR__ . '/' . $scriptName) ?: '';
}

if ($loader !== '' && realpath(__FILE__) === $loader) {
	$_SESSION['error'] = "Archivo no accesible.";
	header("Location: ../index.php");
	exit;
}