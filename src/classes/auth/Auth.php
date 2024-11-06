<?php

namespace iutnc\deefy\auth;

use iutnc\deefy\exception\AuthException;
use iutnc\deefy\repository\Repository;

/**
 * Classe pour l'authentification.
 */
class Auth
{

    /**
     * Méthode pour authentifier un utilisateur.
     * @param string $email
     * @param string $password
     * @return bool
     * @throws AuthException
     */
    public static function authenticate(string $email, string $password): bool
    {

        // Appel du repository pour trouver l'utilisateur
        $repository = new Repository();
        $data = $repository->findUserByEmail($email);

        // Si l'utilisateur n'existe pas ou que le mot de passe est incorrect, on lève une exception
        if (!$data || !password_verify($password, $data['passwd'])) {
            throw new AuthException("Mot de passe incorrect.");
        }

        // Sinon on stocke l'ID et le rôle de l'utilisateur dans la session
        $_SESSION['user']['id'] = $data['id'];
        $_SESSION['user']['role'] = $data['role'];
        return true;
    }

    /**
     * Methode pour etablire si c admin
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 100;
    }

    /**
     * Methode pour etablire si c un utilisateur normal
     * @return bool
     */
    public static function isStandard (): bool
    {
        return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 1;
    }

    /**
     * Methode pour register un utilisateur
     * @param string $email
     * @param string $password
     * @return string
     */
    public static function register(string $email, string $password): string
    {

        // Appel du repository pour trouver l'utilisateur
        $repository = new Repository();
        $existingUser = $repository->findUserByEmail($email);
        $response = "Echec inscription";

        // Si le mot de passe est assez long et que l'utilisateur n'existe pas, on hash le mot de passe et on l'ajoute à la base de données
        if (strlen($password) >= 10 && !$existingUser) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
            if ($repository->registerUser($email, $hashedPassword)) {
                $response = "Inscription réussie";
            }
        }
        return $response;
    }

    /**
     * Methode pour verifier l'acces a une playlist
     * @param int $playlistId
     * @return bool
     */
    public static function checkAccess(int $playlistId): bool
    {
        // Si l'utilisateur n'est pas connecté, on retourne une erreur
        if (!isset($_SESSION['user']['id'])) {
            echo "<p>Erreur : Utilisateur non connecté.</p>";
            return false;
        }

        // Si l'utilisateur est administrateur, on lui accorde l'accès
        $userId = $_SESSION['user']['id'];
        if (self::isAdmin()) {
            echo "<p>Utilisateur administrateur - accès accordé.</p>";
            return true;
        }

        // Appel du repository pour vérifier l'accès à la playlist
        $repository = new Repository();
        return $repository->checkPlaylistAccess($userId, $playlistId);
    }
}
