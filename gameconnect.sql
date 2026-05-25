-- Création de la base
CREATE DATABASE IF NOT EXISTS gameconnect;

-- Utilisation de la base
USE gameconnect;


-- TABLE USERS
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nom VARCHAR(100),
  prenom VARCHAR(100),
  bio TEXT,
  jeu_prefere VARCHAR(100) DEFAULT NULL,
  pseudo VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(150) NOT NULL UNIQUE,
  num_phone VARCHAR(20) DEFAULT NULL,
  mot_de_passe VARCHAR(255) NOT NULL,
  avatar TEXT DEFAULT NULL,
  date_inscription DATETIME DEFAULT CURRENT_TIMESTAMP
);


-- TABLE POSTS
CREATE TABLE posts (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  contenu TEXT NOT NULL,
  image TEXT DEFAULT NULL,
  lien TEXT DEFAULT NULL,
  date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_posts_users
  FOREIGN KEY (user_id)
  REFERENCES users(id)
  ON DELETE CASCADE
);


-- TABLE COMMENTAIRES
CREATE TABLE commentaires (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT NOT NULL,
  user_id INT NOT NULL,
  texte VARCHAR(255) NOT NULL,
  date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,

  CONSTRAINT fk_commentaires_posts
  FOREIGN KEY (post_id)
  REFERENCES posts(id)
  ON DELETE CASCADE,

  CONSTRAINT fk_commentaires_users
  FOREIGN KEY (user_id)
  REFERENCES users(id)
  ON DELETE CASCADE
);


-- TABLE LIKES
CREATE TABLE likes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  post_id INT NOT NULL,
  user_id INT NOT NULL,
  date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,

  UNIQUE (post_id, user_id),

  CONSTRAINT fk_likes_posts
  FOREIGN KEY (post_id)
  REFERENCES posts(id)
  ON DELETE CASCADE,

  CONSTRAINT fk_likes_users
  FOREIGN KEY (user_id)
  REFERENCES users(id)
  ON DELETE CASCADE
);