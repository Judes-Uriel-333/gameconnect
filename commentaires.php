<?php
session_start();

try {
    $bdd = new PDO("mysql:host=localhost;dbname=gameconnect;charset=utf8mb4", "root", "");
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $post_id = intval($_POST['post_id']);
    $texte = $_POST['texte'] ?? '';
    $user_id = $_SESSION['user_id'];

    if (!empty($texte)) {
        $stmt = $bdd->prepare("INSERT INTO commentaires (post_id, user_id, texte) VALUES (?, ?, ?)");
        $stmt->execute([$post_id, $user_id, $texte]);
    }

    header("Location: index.php");
    exit();
}
?>
