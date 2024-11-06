<?php

namespace iutnc\deefy\user;

use iutnc\deefy\repository\Repository;

/**
 * Classe User qui permet de gérer un utilisateur
 */
class User
{

    /**
     * Attributs de la classe User
     * @var string
     */
    private string $email;
    private string $password;
    private int $role;

    /**
     * Constructeur de la classe User
     * @param string $e
     * @param string $p
     * @param int $r
     */
    public function __construct(string $e, string $p, int $r)
    {
        $this->email = $e;
        $this->password = $p;
        $this->role = $r;
    }

    /**
     * Méthode getPlaylists qui permet de récupérer les playlists d'un utilisateur
     * @return array
     */
    public function getPlaylists(): array
    {
        $repository = new Repository();
        return $repository->getUserPlaylists($this->email);
    }

    /**
     * Méthode getEmail qui permet de récupérer l'email d'un utilisateur
     * @return string
     */
    public function getEmail(): string
    {
        $repository = new Repository();
        return $repository->getUserEmail($this->email);
    }
}
