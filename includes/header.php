<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<header class="bg-gray-800 py-4 w-full flex justify-between items-center">
    <!-- GameConnect à gauche, collé au mur -->
    <h1 class="text-2xl font-bold text-white ml-0">
        <a href="index.php">🎮 GameConnect</a>
    </h1>

    <!-- Navigation à droite, collée au mur droit -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <nav class="flex items-center space-x-6 text-white mr-0">
            <a href="index.php" class="hover:text-blue-400">Accueil</a>
            <a href="profil.php" class="hover:text-blue-400">Profil</a>
            <a href="deconnexion.php" class="text-red-500 hover:text-red-600">Déconnexion</a>
        </nav>
    <?php else: ?>
        <nav class="flex items-center space-x-6 text-white mr-0">
            <a href="connexion.php" class="hover:text-blue-400">Connexion</a>
            <a href="inscription.php" class="hover:text-blue-400">Inscription</a>
        </nav>
    <?php endif; ?>
</header>
