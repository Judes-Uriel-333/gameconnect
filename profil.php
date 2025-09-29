<?php
session_start();

// Connexion à la base de données
try {
    $bdd = new PDO("mysql:host=localhost;dbname=gameconnect;charset=utf8mb4", "root", "");
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: connexion.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Traitement de la mise à jour du profil
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_profil'])) {
    $pseudo = $_POST['pseudo'] ?? '';
    $email = $_POST['email'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $bio = $_POST['bio'] ?? '';

    // Upload avatar
    if (!empty($_FILES['avatar']['name'])) {
        $dossier = "uploads/avatars/"; // dossier correct
        if (!is_dir($dossier)) { mkdir($dossier, 0755, true); } // créer si absent
        $avatar = $dossier . uniqid() . "_" . basename($_FILES['avatar']['name']);
        move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar);
    } else {
        $stmt = $bdd->prepare("SELECT avatar FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $avatar = $stmt->fetchColumn();
    }

    // Mise à jour en base
    $stmt = $bdd->prepare("UPDATE users SET pseudo = ?, email = ?, nom = ?, prenom = ?, bio = ?, avatar = ? WHERE id = ?");
    $stmt->execute([$pseudo, $email, $nom, $prenom, $bio, $avatar, $user_id]);

    $message = "Profil mis à jour avec succès.";
}

// Récupérer les infos de l'utilisateur
$stmt = $bdd->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Récupérer les posts de l'utilisateur
$stmt = $bdd->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY date_creation DESC");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil - GameConnect</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col">

<!-- Inclut l'entête identique à l'accueil -->
<?php include 'includes/header.php'; ?>

<div class="flex-1 max-w-4xl mx-auto p-6 space-y-6">

    <h1 class="text-3xl font-bold mb-4 text-center">Mon profil</h1>

    <?php if ($message): ?>
        <div class="bg-green-600 text-white px-4 py-2 rounded mb-4 text-center"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row items-center md:items-start space-x-0 md:space-x-6 mb-6">
        <?php if (!empty($user['avatar'])): ?>
            <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="Avatar" class="w-32 h-32 rounded-full mb-4 md:mb-0">
        <?php else: ?>
            <div class="w-32 h-32 bg-blue-600 text-white flex items-center justify-center rounded-full text-5xl font-bold mb-4 md:mb-0">
                <?= strtoupper(substr($user['pseudo'] ?? '', 0, 1)) ?>
            </div>
        <?php endif; ?>

        <div class="text-white space-y-1">
            <p><strong>Pseudo :</strong> <?= htmlspecialchars($user['pseudo'] ?? '') ?></p>
            <p><strong>Email :</strong> <?= htmlspecialchars($user['email'] ?? '') ?></p>
            <p><strong>Nom :</strong> <?= htmlspecialchars($user['nom'] ?? '') ?></p>
            <p><strong>Prénom :</strong> <?= htmlspecialchars($user['prenom'] ?? '') ?></p>
            <p><strong>Bio :</strong> <?= htmlspecialchars($user['bio'] ?? '') ?></p>
        </div>
    </div>

    <!-- Formulaire de modification -->
    <div class="bg-gray-800 p-4 rounded shadow-md">
        <h2 class="text-xl font-semibold mb-3">Modifier mes informations</h2>
        <form method="POST" enctype="multipart/form-data" class="space-y-3">
            <input type="hidden" name="update_profil" value="1">
            <input type="text" name="pseudo" value="<?= htmlspecialchars($user['pseudo'] ?? '') ?>" placeholder="Pseudo" class="w-full p-2 rounded text-black">
            <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" placeholder="Email" class="w-full p-2 rounded text-black">
            <input type="text" name="nom" value="<?= htmlspecialchars($user['nom'] ?? '') ?>" placeholder="Nom" class="w-full p-2 rounded text-black">
            <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom'] ?? '') ?>" placeholder="Prénom" class="w-full p-2 rounded text-black">
            <textarea name="bio" placeholder="Bio" class="w-full p-2 rounded text-black"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
            <input type="file" name="avatar" class="w-full p-2 rounded bg-white text-black">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 transition px-4 py-2 rounded text-white">Enregistrer</button>
        </form>
    </div>

    <hr class="my-6 border-gray-600">

    <!-- Liste des posts -->
    <h2 class="text-2xl font-bold mb-4 text-center">Mes publications</h2>
    <div class="space-y-4">
        <?php foreach ($posts as $post): ?>
            <div class="bg-gray-800 p-4 rounded shadow-md">
                <?php if (!empty($post['contenu'])): ?>
                    <p class="mb-2"><?= nl2br(htmlspecialchars_decode($post['contenu'], ENT_QUOTES)) ?></p>
                <?php endif; ?>
                <?php if (!empty($post['image'])): ?>
                    <img src="<?= htmlspecialchars($post['image']) ?>" alt="Image post" class="w-full max-h-64 object-cover rounded mb-2">
                <?php endif; ?>
                <?php if (!empty($post['lien'])): ?>
                    <a href="<?= htmlspecialchars($post['lien']) ?>" target="_blank" class="text-blue-400 underline"><?= htmlspecialchars($post['lien']) ?></a>
                <?php endif; ?>
                <p class="text-sm text-gray-400 mt-2"><?= date("d/m/Y H:i", strtotime($post['date_creation'])) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
<script src="js/scroll_top.js"></script>
</body>
</html>
