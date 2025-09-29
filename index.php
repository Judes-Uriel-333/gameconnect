<?php
session_start();

// Connexion à la base de données
try {
    $bdd = new PDO("mysql:host=localhost;dbname=gameconnect;charset=utf8mb4", "root", "");
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Rediriger si pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: connexion.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les posts des autres utilisateurs
$requete = $bdd->prepare("
    SELECT posts.*, users.pseudo, users.avatar 
    FROM posts 
    JOIN users ON posts.user_id = users.id 
    WHERE users.id != ? 
    ORDER BY date_creation DESC
");
$requete->execute([$user_id]);
$posts = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Fil d'actualité - GameConnect</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    #post-form { position: fixed; top: 20px; left: 0; width: 20%; max-width: 300px; background-color: #1f2937; padding: 1rem; border-radius: 0 8px 8px 0; box-shadow: 2px 2px 10px rgba(0,0,0,0.5); z-index: 50; display: none; }
    #overlay { position: fixed; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.3); z-index: 40; display:none; }
    body { padding-bottom: 80px; }
  </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col pb-16">

<?php include 'includes/header.php'; ?>

<div id="overlay"></div>

<div id="post-form">
    <div class="flex justify-between items-center mb-2">
        <h2 class="text-lg font-bold">Créer un post</h2>
        <button onclick="closeForm()" class="text-red-500 font-bold">&times;</button>
    </div>
    <form method="post" action="poster.php" enctype="multipart/form-data" class="space-y-2">
        <textarea name="contenu" rows="4" class="w-full p-2 rounded bg-gray-700 text-white" placeholder="Quoi de neuf ?"></textarea>
        <input type="file" name="image" class="text-white">
        <input type="text" name="lien" placeholder="Lien (optionnel)" class="w-full p-2 rounded bg-gray-700 text-white">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 p-2 rounded text-white font-semibold w-full">Publier</button>
    </form>
</div>

<div class="max-w-3xl mx-auto p-4 space-y-6 flex-1">
    <div class="text-center mb-4">
        <button onclick="openForm()" class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded text-white font-semibold">
            Créer une publication
        </button>
    </div>

    <h1 class="text-2xl font-bold text-center mb-4">Fil d'actualité</h1>

    <?php foreach ($posts as $post): ?>
      <div class="bg-gray-800 p-4 rounded shadow-md">
        <div class="flex items-center space-x-3 mb-2">
          <?php if ($post['avatar']): ?>
            <img src="<?= htmlspecialchars($post['avatar']) ?>" alt="avatar" class="w-10 h-10 rounded-full">
          <?php else: ?>
            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center uppercase font-bold text-lg"><?= strtoupper(substr($post['pseudo'],0,1)) ?></div>
          <?php endif; ?>
          <span class="font-semibold"><?= htmlspecialchars($post['pseudo']) ?></span>
          <span class="text-sm text-gray-400 ml-auto"><?= date("d/m/Y H:i", strtotime($post['date_creation'])) ?></span>
        </div>

        <?php if ($post['contenu']): ?>
          <p class="mb-2"><?= nl2br(htmlspecialchars_decode($post['contenu'], ENT_QUOTES)) ?></p>
        <?php endif; ?>
        <?php if ($post['image']): ?>
          <img src="<?= htmlspecialchars($post['image']) ?>" alt="post image" class="w-full max-h-64 object-cover rounded mb-2">
        <?php endif; ?>
        <?php if ($post['lien']): ?>
          <a href="<?= htmlspecialchars($post['lien']) ?>" target="_blank" class="text-blue-400 underline"><?= htmlspecialchars($post['lien']) ?></a>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
</div>

<footer class="bg-gray-800 p-4 text-center text-gray-400 fixed bottom-0 left-0 w-full">
    <p>&copy; 2025 GameConnect. Tous droits réservés.</p>
    <p>Contact : judesuriel33@gmail.com</p>
</footer>

<script>
function toggleComments(id) { document.getElementById("comment-box-"+id).classList.toggle("hidden"); }
function openForm() { document.getElementById("post-form").style.display="block"; document.getElementById("overlay").style.display="block"; }
function closeForm() { document.getElementById("post-form").style.display="none"; document.getElementById("overlay").style.display="none"; }
</script>
<script src="js/scroll_top.js"></script>
</body>
</html>
