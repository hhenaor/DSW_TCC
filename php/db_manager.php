<?php
declare(strict_types=1);

// Inicia la sesión solo si no hay una sesión activa.
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

/**
 * Manejador simple de base de datos usando PDO para MySQL.
 * Guarda este archivo como db_manager.php e inclúyelo donde sea necesario.
 *
 * Funciones proporcionadas:
 * - connect(array $config = []): PDO
 * - transaction(): bool
 * - commit(): bool
 * - rollback(): bool
 * - sql(string $sql, array $params = []): PDOStatement
 * - query(string $sql, array $params = []): array
 * - disconnect(): void
 * - geterror(PDOException $e): string
 */

// Variable global para almacenar la instancia de la conexión PDO.
global $DB_CONN;
$DB_CONN = null;

// --- Configuración global por defecto para la conexión a la base de datos ---
global $_GLOBAL_host;
$_GLOBAL_host = 'localhost';
global $_GLOBAL_port;
$_GLOBAL_port = '3306';
global $_GLOBAL_dbname;
$_GLOBAL_dbname = '';
global $_GLOBAL_user;
$_GLOBAL_user = 'root';
global $_GLOBAL_pass;
$_GLOBAL_pass = '';
global $_GLOBAL_charset;
$_GLOBAL_charset = 'utf8mb4';
global $_GLOBAL_error;
$_GLOBAL_error = true; // Define si se muestran errores detallados de la base de datos.

/**
 * Se conecta a la base de datos (usando el patrón singleton).
 * Si ya existe una conexión, la devuelve. De lo contrario, crea una nueva.
 *
 * Claves para el array de configuración: host, port, dbname, user, pass, charset
 * @param array $config Un array con parámetros de configuración para sobrescribir los valores por defecto.
 * @return PDO La instancia de la conexión PDO.
 */
function connect(array $config = []): PDO
{
	global $DB_CONN, $_GLOBAL_host, $_GLOBAL_port, $_GLOBAL_dbname, $_GLOBAL_user, $_GLOBAL_pass, $_GLOBAL_charset;

	if ($DB_CONN instanceof PDO) {
		return $DB_CONN;
	}

	$host = $config['host'] ?? $_GLOBAL_host;
	$port = $config['port'] ?? $_GLOBAL_port;
	$dbname = $config['dbname'] ?? $_GLOBAL_dbname;
	$user = $config['user'] ?? $_GLOBAL_user;
	$pass = $config['pass'] ?? $_GLOBAL_pass;
	$charset = $config['charset'] ?? $_GLOBAL_charset;

	$dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
	$options = [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	];

	$DB_CONN = new PDO($dsn, $user, $pass, $options);
	return $DB_CONN;
}

/**
 * Inicia una transacción.
 *
 * @return bool Devuelve `true` si la transacción se inició correctamente.
 */
function transaction(): bool
{
	global $DB_CONN;
	if (!($DB_CONN instanceof PDO)) {
		connect();
	}
	return $DB_CONN->beginTransaction();
}

/**
 * Confirma la transacción actual.
 *
 * @return bool Devuelve `true` si la transacción se confirmó correctamente.
 * @throws RuntimeException si no hay una conexión activa a la base de datos.
 */
function commit(): bool
{
	global $DB_CONN;
	if (!($DB_CONN instanceof PDO)) {
		throw new RuntimeException('No hay una conexión activa con la base de datos para confirmar.');
	}
	return $DB_CONN->commit();
}

/**
 * Revierte la transacción actual si está activa.
 *
 * @return bool Devuelve `true` si la transacción se revirtió correctamente, de lo contrario `false`.
 */
function rollback(): bool
{
	global $DB_CONN;
	if ($DB_CONN instanceof PDO && $DB_CONN->inTransaction()) {
		return $DB_CONN->rollBack();
	}
	return false;
}

/**
 * Ejecuta una sentencia (INSERT, UPDATE, DELETE o cualquier otro SQL).
 * Devuelve el objeto PDOStatement para que quien llama a la función pueda
 * inspeccionar rowCount() o lastInsertId() a través de la conexión global.
 *
 * @param string $sql La consulta SQL a ejecutar.
 * @param array $params Los parámetros para la consulta preparada.
 * @return PDOStatement El objeto de la sentencia ejecutada.
 */
function sql(string $sql, array $params = []): PDOStatement
{
	global $DB_CONN;
	if (!($DB_CONN instanceof PDO)) {
		connect();
	}
	$stmt = $DB_CONN->prepare($sql);
	$stmt->execute($params);
	return $stmt;
}

/**
 * Función de conveniencia para consultas SELECT.
 * Devuelve todas las filas como un array asociativo.
 *
 * @param string $sql La consulta SQL a ejecutar.
 * @param array $params Los parámetros para la consulta preparada.
 * @return array Un array con todos los resultados.
 */
function query(string $sql, array $params = []): array
{
	$stmt = sql($sql, $params);
	return $stmt->fetchAll();
}

/**
 * Desconecta / cierra la conexión PDO.
 * Establece la variable de conexión global a null.
 */
function disconnect(): void
{
	global $DB_CONN;
	$DB_CONN = null;
}

/**
 * Obtiene un mensaje de error.
 * Devuelve el mensaje detallado o uno genérico según la configuración global.
 *
 * @param PDOException $e La excepción PDO capturada.
 * @return string El mensaje de error.
 */
function geterror(PDOException $e): string
{
	global $_GLOBAL_error;
	return $_GLOBAL_error ? $e->getMessage() : 'Ocurrió un error en la base de datos';
}

// --- Bloque de seguridad ---
// Este código previene que el archivo sea accedido directamente desde el navegador.
// Compara la ruta del script que se está ejecutando con la ruta de este archivo.
// Si coinciden, significa que se intentó cargar este archivo directamente.
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