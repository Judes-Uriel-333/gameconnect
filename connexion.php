<?php
session_start();
$erreurs = ["email" => "", "password" => ""];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=gameconnect", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données.");
    }

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs['email'] = "Adresse email invalide.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            $erreurs['email'] = "Aucun compte n'est associé à cet email.";
        } else {
            if (!password_verify($password, $user['mot_de_passe'])) {
                $erreurs['password'] = "Mot de passe incorrect.";
            } else {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['pseudo'] = $user['pseudo'];
                $_SESSION['avatar'] = $user['avatar'];

                header("Location: index.php");
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion - GameConnect</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-6 bg-gray-800 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4 text-center">Connexion à GameConnect</h2>

        <form method="post" class="space-y-4">
            <div>
                <input type="email" name="email" placeholder="Adresse email"
                       class="w-full p-2 rounded bg-gray-700 text-white <?= $erreurs['email'] ? 'border border-red-500' : '' ?>"
                       required value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">
                <?php if ($erreurs['email']): ?>
                    <p class="text-red-400 text-sm mt-1"><?= $erreurs['email'] ?></p>
                <?php endif; ?>
            </div>

            <div>
                <input type="password" name="password" placeholder="Mot de passe"
                       class="w-full p-2 rounded bg-gray-700 text-white <?= $erreurs['password'] ? 'border border-red-500' : '' ?>"
                       required>
                <?php if ($erreurs['password']): ?>
                    <p class="text-red-400 text-sm mt-1"><?= $erreurs['password'] ?></p>
                <?php endif; ?>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 p-2 rounded text-white font-semibold">
                Se connecter
            </button>
        </form>

        <p class="text-sm text-center mt-4">
            Vous n'avez pas encore de compte ? <a href="inscription.php" class="text-indigo-400 hover:underline">Inscrivez-vous ici</a>
        </p>
    </div>
</body>
</html>
