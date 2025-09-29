<?php
session_start();

try {
    $bdd = new PDO("mysql:host=localhost;dbname=gameconnect;charset=utf8mb4", "root", "");
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $contenu = $_POST["contenu"] ?? '';
    $lien = $_POST["lien"] ?? null;
    $image = null;

    if (!empty($_FILES['image']['name'])) {
        $dossier = "uploads/posts/"; // dossier correct
        if (!is_dir($dossier)) { mkdir($dossier, 0755, true); } // créer si absent
        $nomFichier = uniqid() . "_" . basename($_FILES['image']['name']);
        $cheminImage = $dossier . $nomFichier;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $cheminImage)) $image = $cheminImage;
    }

    $stmt = $bdd->prepare("INSERT INTO posts (user_id, contenu, image, lien) VALUES (?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $contenu, $image, $lien]);

    header("Location: profil.php");
    exit();
}
?>
