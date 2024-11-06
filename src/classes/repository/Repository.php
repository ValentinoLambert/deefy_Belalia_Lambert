<?php

namespace iutnc\deefy\repository;

use iutnc\deefy\db\ConnectionFactory;
use iutnc\deefy\lists\PlayList;

use PDO;

/**
 * Classe Repository, il permet de gérer les requêtes SQL
 */
class Repository
{

    /**
     * Attribut db qui est une instance de PDO
     * @var PDO|null $db
     */
    private PDO $db;

    /**
     * Constructeur de la classe Repository
     */
    public function __construct()
    {
        $this->db = ConnectionFactory::makeConnection();
    }

    /**
     * Méthode addPlaylist qui permet d'ajouter une playlist
     * @param string $nom
     * @return int
     */
    public function addPlaylist(string $nom): int
    {
        $stmt = $this->db->prepare("INSERT INTO playlist (nom) VALUES (?)");
        $stmt->bindParam(1, $nom);
        $stmt->execute();
        return $this->db->lastInsertId();
    }

    /**
     * Méthode linkPlaylistToUser qui permet de lier une playlist à un utilisateur
     * @param int $userId
     * @param int $playlistId
     * @return bool
     */
    public function linkPlaylistToUser(int $userId, int $playlistId): bool
    {
        $stmt = $this->db->prepare("INSERT INTO user2playlist (id_user, id_pl) VALUES (?, ?)");
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->bindParam(2, $playlistId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Méthode findPlaylistById qui permet de trouver une playlist par son ID
     * @param int $id
     * @return array
     */
    public function findPlaylistById(int $id)
    {
        $query = "SELECT p.nom as nom, p.id as idp FROM playlist p WHERE p.id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode findPlaylistsByUserId qui permet de trouver les playlists d'un utilisateur
     * @param int $userId
     * @return array
     */
    public function findPlaylistsByUserId(int $userId): array
    {
        $query = "SELECT p.id, p.nom FROM playlist p 
                  INNER JOIN user2playlist u2p ON p.id = u2p.id_pl
                  WHERE u2p.id_user = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->execute();

        $playlists = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $playlists[] = $data;
        }
        return $playlists;
    }

    /**
     * Méthode addTrack qui permet d'ajouter une piste dans le lien entre une playlist et une piste
     * @param int $playlistId
     * @param int $trackId
     * @param int $trackNumber
     * @return bool
     */
    public function addTrackToPlaylist(int $playlistId, int $trackId, int $trackNumber): bool
    {
        $insert = "INSERT INTO playlist2track (id_pl, id_track, no_piste_dans_liste) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($insert);
        $stmt->bindParam(1, $playlistId, PDO::PARAM_INT);
        $stmt->bindParam(2, $trackId, PDO::PARAM_INT);
        $stmt->bindParam(3, $trackNumber, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Méthode getPlaylistTracks qui permet de récupérer les pistes d'une playlist oar son ID
     * @param int $playlistId
     * @return array
     */
    public function getPlaylistTracks(int $playlistId): array
    {
        $query = "SELECT t.* 
                  FROM track t
                  INNER JOIN playlist2track p2t ON t.id = p2t.id_track
                  WHERE p2t.id_pl = ?
                  ORDER BY p2t.no_piste_dans_liste ASC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $playlistId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode getUserEmail qui permet de récupérer l'email d'un utilisateur par son ID
     * @param int $userId
     * @return string
     */
    public function getUserEmail(int $userId): string
    {
        $query = "SELECT email FROM user WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data['email'] ?? 'Email non trouvé';
    }

    /**
     * Méthode getUserPlaylists qui permet de récupérer les playlists d'un utilisateur par son email
     * @param string $email
     * @return array
     */
    public function getUserPlaylists(string $email): array
    {
        $query = "SELECT p.id as id, p.nom as nom FROM user u
                  INNER JOIN user2playlist u2 ON u.id = u2.id_user
                  INNER JOIN playlist p ON u2.id_pl = p.id
                  WHERE u.email = ?";
        $prep = $this->db->prepare($query);
        $prep->bindParam(1, $email);
        $prep->execute();

        $playlists = [];
        while ($data = $prep->fetch(PDO::FETCH_ASSOC)) {
            $playlists[$data['id']] = new PlayList((int)$data['id'], $data['nom'], []);
        }
        return $playlists;
    }

    /**
     * Méthode findPlaylistIdByName qui permet de trouver l'ID d'une playlist par son nom
     * @param string $name
     * @return array
     */
    public function findPlaylistIdByName(string $name): array
    {
        $query = "SELECT id FROM playlist WHERE nom LIKE ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $name);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Méthode findUserByEmail qui permet de trouver un utilisateur par son email
     * @param string $email
     * @return array|null
     */
    public function findUserByEmail(string $email): ?array
    {
        $query = "SELECT id, passwd, role FROM User WHERE email = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Méthode registerUser qui permet d'enregistrer un utilisateur
     * @param string $email
     * @param string $hashedPassword
     * @return bool
     */
    public function registerUser(string $email, string $hashedPassword): bool
    {
        $insert = "INSERT INTO user (email, passwd) VALUES (?, ?)";
        $stmt = $this->db->prepare($insert);
        $stmt->bindParam(1, $email);
        $stmt->bindParam(2, $hashedPassword);
        return $stmt->execute();
    }

    /**
     * Méthode checkPlaylistAccess qui permet de vérifier l'accès à une playlist
     * @param int $userId
     * @param int $playlistId
     * @return bool
     *
     */
    public function checkPlaylistAccess(int $userId, int $playlistId): bool
    {
        $query = "SELECT COUNT(*) FROM user2playlist WHERE id_user = ? AND id_pl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $userId, PDO::PARAM_INT);
        $stmt->bindParam(2, $playlistId, PDO::PARAM_INT);
        $stmt->execute();
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Méthode getNextTrackNumber qui permet de récupérer le numéro de piste suivant
     * @param int $playlistId
     * @return int
     */
    public function getNextTrackNumber(int $playlistId): int
    {
        $query = "SELECT COALESCE(MAX(no_piste_dans_liste), 0) + 1 AS next_track_number 
                  FROM playlist2track 
                  WHERE id_pl = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $playlistId, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data['next_track_number'] ?? 1;
    }
}
