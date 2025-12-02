<?php
declare(strict_types=1);

require_once 'db_manager.php';

/**
 * Elimina un usuario y todos sus datos relacionados (por CASCADE).
 *
 * @param int $user_id El ID del usuario a eliminar.
 * @return bool Devuelve true si la eliminación fue exitosa, false en caso contrario.
 */
function delete_user(int $user_id): bool
{
    try {
        connect(['dbname' => 'foro_e']);

        transaction();

        $sql = "DELETE FROM users WHERE user_id = ?";
        sql($sql, [$user_id]);

        commit();
        return true;

    } catch (PDOException $e) {
        rollback();
        return false;
    } finally {
        disconnect();
    }
}
