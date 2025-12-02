<?php
declare(strict_types=1);

require_once 'db_manager.php';

/**
 * Obtiene un artículo aleatorio.
 *
 * @return array|null Devuelve un array con los datos del artículo o null si no hay artículos.
 */
function get_random_article(): ?array
{
    try {
        connect(['dbname' => 'foro_e']);

        $sql = "SELECT * FROM articles ORDER BY RAND() LIMIT 1";
        $result = query($sql);

        if (!empty($result)) {
            return $result[0];
        }

        return null;

    } catch (PDOException $e) {
        return null;
    } finally {
        disconnect();
    }
}

/**
 * Obtiene el número de likes de un artículo.
 *
 * @param int $article_id El ID del artículo.
 * @return int El número de likes.
 */
function get_article_likes(int $article_id): int
{
    try {
        connect(['dbname' => 'foro_e']);

        $sql = "SELECT COUNT(*) as total FROM likes WHERE content_type = 'article' AND content_id = ?";
        $result = query($sql, [$article_id]);

        return (int)($result[0]['total'] ?? 0);

    } catch (PDOException $e) {
        return 0;
    } finally {
        disconnect();
    }
}

/**
 * Obtiene el número de comentarios de un artículo.
 *
 * @param int $article_id El ID del artículo.
 * @return int El número de comentarios.
 */
function get_article_comments_count(int $article_id): int
{
    try {
        connect(['dbname' => 'foro_e']);

        $sql = "SELECT COUNT(*) as total FROM comments WHERE content_type = 'article' AND content_id = ?";
        $result = query($sql, [$article_id]);

        return (int)($result[0]['total'] ?? 0);

    } catch (PDOException $e) {
        return 0;
    } finally {
        disconnect();
    }
}

/**
 * Obtiene un artículo por su ID.
 *
 * @param int $article_id El ID del artículo.
 * @return array|null Devuelve un array con los datos del artículo o null si no existe.
 */
function get_article_by_id(int $article_id): ?array
{
    try {
        connect(['dbname' => 'foro_e']);

        // Incluimos el nombre de usuario del autor
        $sql = "SELECT a.*, u.username
                FROM articles a
                INNER JOIN users u ON a.user_id = u.user_id
                WHERE a.article_id = ?";
        $result = query($sql, [$article_id]);

        if (!empty($result)) {
            return $result[0];
        }

        return null;

    } catch (PDOException $e) {
        return null;
    } finally {
        disconnect();
    }
}

/**
 * Obtiene la lista de comentarios de un artículo.
 *
 * @param int $article_id El ID del artículo.
 * @return array La lista de comentarios.
 */
function get_article_comments_list(int $article_id): array
{
    try {
        connect(['dbname' => 'foro_e']);

        $sql = "SELECT c.*, u.username
                FROM comments c
                INNER JOIN users u ON c.user_id = u.user_id
                WHERE c.content_type = 'article' AND c.content_id = ?
                ORDER BY c.comment_id ASC";
        $result = query($sql, [$article_id]);

        return $result;

    } catch (PDOException $e) {
        return [];
    } finally {
        disconnect();
    }
}
