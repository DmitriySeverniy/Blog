DROP TABLE IF EXISTS categories;
CREATE TABLE categories
(
  id              smallint unsigned NOT NULL auto_increment,
  name            varchar(255) NOT NULL,                      # Название категории
  description     text NOT NULL,                              # Краткое описание категории

  PRIMARY KEY     (id)
);

DROP TABLE IF EXISTS articles;
CREATE TABLE articles
(
  id              smallint unsigned NOT NULL auto_increment,
  publicationDate date NOT NULL,                              # Дата публикации категории
  categoryId      smallint unsigned NOT NULL,                 # ID Идентификатор категории статьи
  title           varchar(255) NOT NULL,                      # Полное название статьи
  summary         text NOT NULL,                              # Краткое содержание статьи
  content         mediumtext NOT NULL,                        # Содержание статьи

  PRIMARY KEY     (id)
);

