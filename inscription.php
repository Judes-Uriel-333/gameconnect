<?php
session_start();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connexion à la base de données
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=gameconnect", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }

    // Récupération des champs
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $pseudo = htmlspecialchars(trim($_POST['pseudo']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $mdp = $_POST['mdp'];
    $confirm_mdp = $_POST['confirm_mdp'];

    // Vérifications
    if (empty($nom) || empty($prenom) || empty($pseudo) || empty($email) || empty($mdp) || empty($confirm_mdp)) {
        $errors[] = "Tous les champs sont obligatoires.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email invalide.";
    }

    if ($mdp !== $confirm_mdp) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    // Validation du mot de passe
    if (
        strlen($mdp) < 10 ||
        !preg_match('/[A-Z]/', $mdp) ||
        !preg_match('/[a-z]/', $mdp) ||
        !preg_match('/[0-9]/', $mdp) ||
        !preg_match('/[\W]/', $mdp)
    ) {
        $errors[] = "Le mot de passe doit contenir au moins 10 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
    }

    // Gestion de l'avatar
    $avatarPath = null;
    if (!empty($_FILES['avatar']['name'])) {
        $avatarName = uniqid() . '_' . $_FILES['avatar']['name'];
        $uploadDir = 'uploads/avatars/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        $avatarPath = $uploadDir . $avatarName;
        move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath);
    }

    // S’il n’y a pas d’erreurs
    if (empty($errors)) {
        // Hash du mot de passe
        $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);

        // Si aucun avatar, on utilisera la première lettre du pseudo côté affichage
        $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, pseudo, email, mot_de_passe, avatar) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $pseudo, $email, $mdpHash, $avatarPath]);

        header("Location: connexion.php?success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription - GameConnect</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-6 bg-gray-800 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Créer un compte GameConnect</h2>

        <?php if (!empty($errors)): ?>
            <div class="bg-red-600 p-2 mb-4 rounded">
                <ul class="text-sm">
                    <?php foreach ($errors as $e): ?>
                        <li>• <?= $e ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" novalidate>
            <input type="text" name="nom" placeholder="Nom" class="w-full p-2 mb-2 rounded bg-gray-700" required>
            <input type="text" name="prenom" placeholder="Prénom" class="w-full p-2 mb-2 rounded bg-gray-700" required>
            <input type="text" name="pseudo" placeholder="Pseudo" class="w-full p-2 mb-2 rounded bg-gray-700" required>
            <input type="email" name="email" placeholder="Adresse email" class="w-full p-2 mb-2 rounded bg-gray-700" required>

            <input type="password" name="mdp" id="mdp" placeholder="Mot de passe" class="w-full p-2 mb-2 rounded bg-gray-700" required>
            <input type="password" name="confirm_mdp" placeholder="Confirmer mot de passe" class="w-full p-2 mb-2 rounded bg-gray-700" required>

            <div id="password-rules" class="text-xs mb-2">
                <p id="rule-length" class="text-red-500">• Au moins 10 caractères</p>
                <p id="rule-upper" class="text-red-500">• 1 majuscule</p>
                <p id="rule-lower" class="text-red-500">• 1 minuscule</p>
                <p id="rule-digit" class="text-red-500">• 1 chiffre</p>
                <p id="rule-special" class="text-red-500">• 1 caractère spécial</p>
            </div>

            <label class="text-sm">Avatar (optionnel)</label>
            <input type="file" name="avatar" class="w-full p-2 mb-4 bg-gray-700 text-white">

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 p-2 rounded text-white font-semibold">S'inscrire</button>
        </form>

        <p class="text-sm text-center mt-4">
            Vous avez déjà un compte ? <a href="connexion.php" class="text-indigo-400 hover:underline">Connectez-vous ici</a>
        </p>
    </div>

    <script>
        const mdp = document.getElementById("mdp");
        const rules = {
            length: document.getElementById("rule-length"),
            upper: document.getElementById("rule-upper"),
            lower: document.getElementById("rule-lower"),
            digit: document.getElementById("rule-digit"),
            special: document.getElementById("rule-special")
        };

        mdp.addEventListener("input", function () {
            const value = mdp.value;
            rules.length.className = value.length >= 10 ? "text-green-400" : "text-red-500";
            rules.upper.className = /[A-Z]/.test(value) ? "text-green-400" : "text-red-500";
            rules.lower.className = /[a-z]/.test(value) ? "text-green-400" : "text-red-500";
            rules.digit.className = /[0-9]/.test(value) ? "text-green-400" : "text-red-500";
            rules.special.className = /[\W]/.test(value) ? "text-green-400" : "text-red-500";
        });
    </script>
</body>
</html>
