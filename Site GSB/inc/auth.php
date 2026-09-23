<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

/**
 * Retourne les informations de l'utilisateur connecté ou null.
 *
 * @return array|null
 */
function currentUser(): ?array
{
    return $_SESSION['user'] ?? null;
}

/**
 * Indique si un utilisateur est connecté.
 *
 * @return bool
 */
function isLoggedIn(): bool
{
    return !empty($_SESSION['user']['id']);
}

/**
 * Liste des IDs visiteur qui sont admins.
 * À modifier selon les admins réels de ton système.
 *
 * @return array
 */
function getAdminIds(): array
{
    return ['a00']; // Admin système (à adapter)
}

/**
 * Vérifie si l'utilisateur actuel est un admin.
 *
 * @return bool
 */
function isAdmin(): bool
{
    $user = currentUser();
    if (!$user) {
        return false;
    }
    return in_array($user['id'], getAdminIds(), true);
}

/**
 * Redirige vers la connexion si l'utilisateur n'est pas un admin.
 */
function requireAdmin(): void
{
    if (!isAdmin()) {
        header('Location: tableau-bord.php');
        exit;
    }
}

/**
 * Redirige vers la page de connexion si l'utilisateur n'est pas connecté.
 */
function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: connexion.php');
        exit;
    }
}

/**
 * Tente de connecter un utilisateur.
 *
 * @param string $login
 * @param string $password
 * @return bool
 */
function login(string $login, string $password): bool
{
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT id, nom, prenom, login, mdp FROM Visiteur WHERE login = :login LIMIT 1');
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch();
    if (!$user) {
        return false;
    }

    // Les mots de passe des utilisateurs sont stockés en clair dans la base.
    // La comparaison se fait donc directement.
    $stored = $user['mdp'];
    if (!hash_equals($stored, $password)) {
        return false;
    }

    $_SESSION['user'] = [
        'id' => $user['id'],
        'login' => $user['login'],
        'nom' => $user['nom'],
        'prenom' => $user['prenom'],
    ];

    return true;
}

/**
 * Déconnecte l'utilisateur courant.
 */
function logout(): void
{
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}
