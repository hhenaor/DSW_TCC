<?php
declare(strict_types=1);

require_once 'db_manager.php';

/**
 * Obtiene la noticia más reciente (con el ID más alto).
 *
 * @return array|null Devuelve un array con los datos de la noticia o null si no hay noticias.
 */
function get_latest_news(): ?array
{
    try {
        connect(['dbname' => 'foro_e']);

        $sql = "SELECT * FROM news ORDER BY news_id DESC LIMIT 1";
        $result = query($sql);

        if (!empty($result)) {
            return $result[0];
        }

        return null;

    } catch (PDOException $e) {
        // En un entorno real, podríamos registrar el error.
        return null;
    } finally {
        disconnect();
    }
}

/**
 * Obtiene el número de likes de una noticia.
 *
 * @param int $news_id El ID de la noticia.
 * @return int El número de likes.
 */
function get_news_likes(int $news_id): int
{
    try {
        connect(['dbname' => 'foro_e']);

        $sql = "SELECT COUNT(*) as total FROM likes WHERE content_type = 'news' AND content_id = ?";
        $result = query($sql, [$news_id]);

        return (int)($result[0]['total'] ?? 0);

    } catch (PDOException $e) {
        return 0;
    } finally {
        disconnect();
    }
}

/**
 * Obtiene el número de comentarios de una noticia.
 *
 * @param int $news_id El ID de la noticia.
 * @return int El número de comentarios.
 */
function get_news_comments(int $news_id): int
{
    try {
        connect(['dbname' => 'foro_e']);

        $sql = "SELECT COUNT(*) as total FROM comments WHERE content_type = 'news' AND content_id = ?";
        $result = query($sql, [$news_id]);

        return (int)($result[0]['total'] ?? 0);

    } catch (PDOException $e) {
        return 0;
    } finally {
        disconnect();
    }
}

/**
 * Obtiene una noticia por su ID.
 *
 * @param int $news_id El ID de la noticia.
 * @return array|null Devuelve un array con los datos de la noticia o null si no existe.
 */
function get_news_by_id(int $news_id): ?array
{
    try {
        connect(['dbname' => 'foro_e']);

        // Incluimos el nombre de usuario del autor
        $sql = "SELECT n.*, u.username
                FROM news n
                INNER JOIN users u ON n.user_id = u.user_id
                WHERE n.news_id = ?";
        $result = query($sql, [$news_id]);

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
 * Obtiene la lista de comentarios de una noticia.
 *
 * @param int $news_id El ID de la noticia.
 * @return array La lista de comentarios.
 */
function get_news_comments_list(int $news_id): array
{
    try {
        connect(['dbname' => 'foro_e']);

        $sql = "SELECT c.*, u.username
                FROM comments c
                INNER JOIN users u ON c.user_id = u.user_id
                WHERE c.content_type = 'news' AND c.content_id = ?
                ORDER BY c.comment_id ASC";
        $result = query($sql, [$news_id]);

        return $result;

    } catch (PDOException $e) {
        return [];
    } finally {
        disconnect();
    }
}
