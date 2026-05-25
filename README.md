# GameConnect

GameConnect est une application web simple de type reseau social pour gamers.
Elle a ete realisée en PHP, MySQL, HTML/CSS et JavaScript dans le cadre d'une situation professionnelle BTS SIO SLAM.

L'application permet a un utilisateur de créer un compte, se connecter, publier des posts, ajouter une image ou un lien, liker des publications, commenter et consulter les profils des autres utilisateurs.

## Technologies utilisees

- PHP
- MySQL
- HTML / CSS
- JavaScript
- Tailwind CSS via CDN
- XAMPP

## Fonctionnalites principales

- Inscription avec pseudo, email, mot de passe hashé et avatar optionnel
- Connexion et deconnexion
- Gestion de session utilisateur
- Publication de posts avec texte, image et lien optionnel
- Fil d'actualite trié du plus recent au plus ancien
- Likes avec compteur visible
- Commentaires sur les publications
- Profil utilisateur avec avatar, pseudo, bio et jeu préféré
- Consultation des profils des autres utilisateurs

## Structure du projet

```text
gameconnect/
|-- Cahier de Charge/
|   `-- cahier de charges GameConnect.odt
|-- config/
|   `-- db.php
|-- docs/
|   `-- MCD.png
|-- includes/
|   |-- db.php
|   |-- footer.php
|   `-- header.php
|-- js/
|   |-- mot_de_passe.js
|   `-- scroll_top.js
|-- public/
|   `-- css/
|       `-- style.css
|-- commentaires.php
|-- connexion.php
|-- deconnexion.php
|-- gameconnect.sql
|-- index.php
|-- inscription.php
|-- likes.php
|-- poster.php
|-- profil.php
`-- profil_utilisateur.php
```

## Modele de donnees

Le schema de la base de donnees est disponible ici :

[Voir le schema](docs/MCD.png)

La base de donnees contient les tables suivantes :

- `users` : utilisateurs inscrits
- `posts` : publications des utilisateurs
- `commentaires` : commentaires ajoutes sur les publications
- `likes` : likes des utilisateurs sur les publications

## Documentation d'installation

### 1. Installer les outils

Installer XAMPP, puis demarrer les services suivants :

- Apache
- MySQL

### 2. Placer le projet dans XAMPP

Le dossier du projet doit être placé dans :

```text
C:\xampp\htdocs\gameconnect
```

### 3. Importer la base de donnees

Ouvrir phpMyAdmin depuis le navigateur :

```text
http://localhost/phpmyadmin
```

Puis importer le fichier :

```text
gameconnect.sql
```

Ce fichier crée la base `gameconnect` ainsi que les tables nécéssaires.

### 4. Lancer l'application

Ouvrir le navigateur et aller sur :

```text
http://localhost/gameconnect
```

L'utilisateur est rédirigé vers la page de connexion s'il n'est pas connecté.

## Documentation utilisateur

### Créer un compte

Depuis la page d'inscription, l'utilisateur renseigne :

- un pseudo
- une adresse email
- un mot de passe
- une confirmation du mot de passe
- un avatar optionnel

Le mot de passe doit respecter les règles affichées sur la page.

### Se connecter

Depuis la page de connexion, l'utilisateur indique son email et son mot de passe.
Une fois connecté, il arrive sur le fil d'actualité.

### Publier un post

Sur le fil d'actualite, l'utilisateur clique sur le bouton de création de publication.
Il peut ajouter :

- du texte
- une image
- un lien vers une video ou une ressource externe

Apres validation, la publication apparait dans le fil.

### Liker une publication

Chaque publication possède un bouton de like.
L'utilisateur peut liker ou rétirer son like.
Le compteur de likes est affiché sous la publication.

### Commenter une publication

Sous chaque publication, l'utilisateur peut saisir un commentaire.
Les commentaires déjà ajoutés sont affichés sous le post.

### Modifier son profil

Depuis la page profil, l'utilisateur peut modifier :

- sa bio
- son jeu préféré
- son avatar

La page profil affiche aussi la liste des publications de l'utilisateur.

### Consulter un profil

Dans le fil d'actualite, le pseudo d'un auteur permet d'accéder a son profil public.
Le profil affiche son avatar, son pseudo, sa bio, son jeu préféré et ses publications.

## Remarques

Le projet reste volontairement simple afin de correspondre a un niveau BTS SIO SLAM.
Les likes et commentaires fonctionnent avec des formulaires PHP classiques, sans systeme AJAX.

Les fichiers d'upload ne sont pas suivis par Git afin d'eviter d'envoyer des fichiers utilisateurs dans le dépot.
