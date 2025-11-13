SET default_storage_engine=InnoDB;
DROP DATABASE IF EXISTS foro_e;
CREATE DATABASE foro_e;
USE foro_e;

-- ----------------------------------------------------
-- TABLA: users
-- Almacena la información de los usuarios registrados.
-- ----------------------------------------------------
CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL, -- Almacena siempre el hash de la contraseña, no el texto plano.
    admin BOOLEAN DEFAULT FALSE -- Indica si el usuario es administrador.
);

-- ----------------------------------------------------
-- TABLA: forums
-- Define las categorías o foros donde los usuarios pueden crear publicaciones.
-- ----------------------------------------------------
CREATE TABLE forums (
    forum_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    link_url VARCHAR(1000) NULL, -- Campo opcional para un enlace externo relacionado con el foro.
    description TEXT
);

-- ----------------------------------------------------
-- TABLA: posts
-- Almacena las publicaciones (posts) que los usuarios crean dentro de un foro.
-- ----------------------------------------------------
CREATE TABLE posts (
    post_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    link_url VARCHAR(1000) NULL, -- Campo opcional para un enlace externo relacionado con la publicación.
    user_id INT UNSIGNED NOT NULL,
    forum_id INT UNSIGNED NOT NULL,

    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE, -- Si se borra un usuario, se borran sus publicaciones.
    FOREIGN KEY (forum_id) REFERENCES forums(forum_id) ON DELETE CASCADE -- Si se borra un foro, se borran sus publicaciones.
);

-- ----------------------------------------------------
-- TABLA: news
-- Almacena noticias, similar a las publicaciones, pero para contenido editorial.
-- ----------------------------------------------------
CREATE TABLE news (
    news_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    link_url VARCHAR(1000) NULL, -- Campo opcional para un enlace externo relacionado con la noticia.
    user_id INT UNSIGNED NOT NULL, -- El autor de la noticia (probablemente un administrador o editor).

    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- ----------------------------------------------------
-- TABLA: articles
-- Almacena artículos, similar a las noticias, para otro tipo de contenido.
-- ----------------------------------------------------
CREATE TABLE articles (
    article_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    link_url VARCHAR(1000) NULL, -- Campo opcional para un enlace externo relacionado con el artículo.
    user_id INT UNSIGNED NOT NULL, -- El autor del artículo.

    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- ----------------------------------------------------
-- TABLA: comments
-- Almacena comentarios para publicaciones, noticias o artículos.
-- ----------------------------------------------------
CREATE TABLE comments (
    comment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    user_id INT UNSIGNED NOT NULL,
    content_type ENUM('post', 'news', 'article') NOT NULL, -- Tipo de contenido al que pertenece el comentario.
    content_id INT UNSIGNED NOT NULL, -- ID de la publicación, noticia o artículo asociado.

    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

-- ----------------------------------------------------
-- TABLA: likes
-- Registra los 'me gusta' que un usuario da a diferentes tipos de contenido.
-- ----------------------------------------------------
CREATE TABLE likes (
    like_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    content_type ENUM('post', 'news', 'article', 'comment') NOT NULL, -- Tipo de contenido que recibe el 'me gusta'.
    content_id INT UNSIGNED NOT NULL, -- ID de la publicación, noticia, artículo o comentario asociado.

    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    -- Se crea una llave única para asegurar que un usuario solo pueda dar 'me gusta' una vez al mismo contenido.
    UNIQUE KEY unique_like (user_id, content_type, content_id)
);
