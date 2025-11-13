<?php
// Inicia la sesión solo si no hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

/**
 * Verifica si un foro existe en la base de datos.
 *
 * Se conecta a la base de datos, busca un foro por su ID y
 * devuelve un valor booleano que indica si se encontró.
 *
 * @param int $forum_id El ID del foro que se desea verificar.
 * @return bool Devuelve `true` (1) si el foro existe, de lo contrario `false` (0).
 */
function forum_exists(int $forum_id): bool
{
	// Establece la conexión con la base de datos.
	connect([
		'dbname' => 'foro_e'
	]);

	// Ejecuta la consulta para encontrar el foro.
	$forum = query(
		"SELECT * FROM forums WHERE forum_id = ?",
		[$forum_id]
	);

	// Cierra la conexión.
	disconnect();

	// Comprueba si la consulta devolvió algún resultado.
	if ($forum && !empty($forum)) {
		return true;
	} else {
		return false;
	}
}

/**
 * Obtiene el nombre de un foro a partir de su ID.
 *
 * Esta función asume que el foro con el ID proporcionado ya existe.
 * Si el foro no existe, puede generar un error.
 *
 * @param int $forum_id El ID del foro del cual se quiere obtener el nombre.
 * @return string Devuelve el nombre del foro.
 */
function get_forum_name(int $forum_id): string
{
	// Establece la conexión con la base de datos.
	connect([
		'dbname' => 'foro_e'
	]);

	// Ejecuta la consulta para obtener los datos del foro.
	$forum = query(
		"SELECT * FROM forums WHERE forum_id = ?",
		[$forum_id]
	);

	// Cierra la conexión.
	disconnect();

	// Devuelve el campo 'name' del primer resultado.
	return $forum[0]['name'];
}

/**
 * Obtiene la descripción de un foro a partir de su ID.
 *
 * Esta función asume que el foro con el ID proporcionado ya existe.
 * Si el foro no existe, puede generar un error.
 *
 * @param int $forum_id El ID del foro del cual se quiere obtener la descripción.
 * @return string Devuelve la descripción del foro.
 */
function get_forum_description(int $forum_id): string
{
	// Establece la conexión con la base de datos.
	connect([
		'dbname' => 'foro_e'
	]);

	// Ejecuta la consulta para obtener los datos del foro.
	$forum = query(
		"SELECT * FROM forums WHERE forum_id = ?",
		[$forum_id]
	);

	// Cierra la conexión.
	disconnect();

	// Devuelve el campo 'description' del primer resultado.
	return $forum[0]['description'];
}