/* Création de la table post */
CREATE TABLE post (
    id INT UNSIGNED NOT NULL (AUTOINCRMEENT),
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    content TEXT(100000) NOT NULL,
    created_at DATETIME NOT NULL,
    PRIMARY KEY(id)
)

/* Création de la table category */
CREATE TABLE category(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    PRIMARY KEY(id)
)
/* Création de la table de liaison entre la table post et la table, category
    ( une post peut avoir plusieurs categories )
    fk ( FOREIGN KEY ) " clé étrangère "
    fk_post, fera référence a la table " post ", et aura pour id, l'id du post
    ON DELETE CASCADE, si on supprime l'article, la ligne associer seront automatiquement supprimer
*/
CREATE TABLE post_category(
    post_id INT UNSIGNED NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    PRIMARY KEY(post_id, category_id),
    CONSTRAINT fk_post FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE ON UPDATE RESTRICT,
    CONSTRAINT fk_category FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE ON UPDATE RESTRICT
)

/* Création de la table user */
CREATE TABLE user(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
)