<?php
session_start();

try {
    $bdd = new PDO("mysql:host=localhost;dbname=gameconnect", "root", "");
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}

if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$profil_id = intval($_GET['id']);

// Récupère les infos du profil visité
$stmt = $bdd->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$profil_id]);
$profil = $stmt->fetch();

if (!$profil) {
    die("Utilisateur introuvable.");
}

// Récupère les posts de ce user
$stmt = $bdd->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY date_creation DESC");
$stmt->execute([$profil_id]);
$posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($profil['pseudo']) ?> - Profil</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white">
<div class="max-w-3xl mx-auto p-6">
    <a href="index.php" class="text-blue-400 underline mb-4 inline-block">← Retour au fil</a>

    <div class="flex items-center space-x-4 mb-6">
        <?php if ($profil['avatar']): ?>
            <img src="<?= $profil['avatar'] ?>" alt="Avatar" class="w-20 h-20 rounded-full">
        <?php else: ?>
            <div class="w-20 h-20 bg-blue-600 flex items-center justify-center text-3xl font-bold rounded-full">
                <?= strtoupper(substr($profil['pseudo'], 0, 1)) ?>
            </div>
        <?php endif; ?>

        <div>
            <p class="text-xl font-bold"><?= htmlspecialchars($profil['pseudo']) ?></p>
            <p class="text-gray-400"><?= htmlspecialchars($profil['bio']) ?></p>
        </div>
    </div>

    <h2 class="text-lg font-semibold mb-2">Posts de <?= htmlspecialchars($profil['pseudo']) ?></h2>

    <div class="space-y-4">
        <?php foreach ($posts as $post): ?>
            <div class="bg-gray-800 p-4 rounded">
                <?php if ($post['contenu']): ?>
                    <p class="mb-2"><?= nl2br(htmlspecialchars($post['contenu'])) ?></p>
                <?php endif; ?>
                <?php if ($post['image']): ?>
                    <img src="<?= htmlspecialchars($post['image']) ?>" class="max-h-64 rounded mb-2">
                <?php endif; ?>
                <?php if ($post['lien']): ?>
                    <a href="<?= htmlspecialchars($post['lien']) ?>" class="text-blue-400 underline" target="_blank"><?= htmlspecialchars($post['lien']) ?></a>
                <?php endif; ?>
                <p class="text-sm text-gray-400"><?= date("d/m/Y H:i", strtotime($post['date_creation'])) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
