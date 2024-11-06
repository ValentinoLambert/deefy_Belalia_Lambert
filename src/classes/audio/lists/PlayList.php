<?php

namespace iutnc\deefy\audio\lists;

use iutnc\deefy\repository\Repository;

/**
 * Classe représentant une playlist.
 */
class PlayList extends AudioList
{

    /**
     * Attributs de la classe PlayList.
     * @var int ID de la playlist.
     * @var String Nom de la playlist.
     */
    public int $id;
    public string $nom;

    /**
     * Constructeur de la classe PlayList.
     * @param int $id
     * @param string $nom
     * @param iterable $tab
     */
    public function __construct(int $id, string $nom, iterable $tab = [])
    {
        parent::__construct($id, $nom, $tab);
    }

    /**
     * Méthode statique pour trouver une playlist par son ID.
     * @param int $id
     * @return PlayList|null
     */
    public static function find(int $id): ?PlayList
    {
        // Appel du repository pour trouver la playlist
        $repository = new Repository();
        $data = $repository->findPlaylistById($id);

        // Si la playlist existe, on la retourne, sinon on retourne null
        if ($data) {
            return new PlayList($data['idp'], $data['nom']);
        }
        return null;
    }

    /**
     * Méthode statique pour trouver les playlists d'un utilisateur.
     * @param int $userId
     * @return array
     */
    public static function findByUserId(int $userId): array
    {

        // Appel du repository pour trouver les playlists de l'utilisateur
        $repository = new Repository();
        $playlistData = $repository->findPlaylistsByUserId($userId);

        // On retourne un tableau de playlists, et pour chaque playlist on crée une instance de PlayList
        $playlists = [];
        foreach ($playlistData as $data) {
            $playlists[] = new PlayList($data['id'], $data['nom']);
        }
        return $playlists;
    }

    /**
     * Méthode pour ajouter une piste à la playlist.
     * @param int $id_track
     */
    public function ajouterPiste(int $id_track): void
    {
        // Appel du repository pour ajouter la piste à la playlist; on récupère le numéro de piste, puis on ajoute la piste
        $repository = new Repository();
        $trackNumber = $repository->getNextTrackNumber($this->id);
        $repository->addTrackToPlaylist($this->id, $id_track, $trackNumber);
    }

    /**
     * Méthode pour récupérer les pistes de la playlist.
     * @return array
     */
    public function getTracks(): array
    {
        $repository = new Repository();
        return $repository->getPlaylistTracks($this->id);
    }
}
