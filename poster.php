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
    $contenu = trim($_POST["contenu"] ?? '');
    $lien = trim($_POST["lien"] ?? '');
    if ($lien === '' || !filter_var($lien, FILTER_VALIDATE_URL)) {
        $lien = null;
    }

    $image = null;

    if (!empty($_FILES['image']['name'])) {
        $dossier = "uploads/posts/"; // dossier correct
        if (!is_dir($dossier)) { mkdir($dossier, 0755, true); } // créer si absent
        $nomFichier = uniqid() . "_" . basename($_FILES['image']['name']);
        $cheminImage = $dossier . $nomFichier;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $cheminImage)) $image = $cheminImage;
    }

    if ($contenu !== '' || $image !== null || $lien !== null) {
        $stmt = $bdd->prepare("INSERT INTO posts (user_id, contenu, image, lien) VALUES (?, ?, ?, ?)");
        $stmt->execute([$_SESSION['user_id'], $contenu, $image, $lien]);
    }

    header("Location: index.php");
    exit();
}
?>
