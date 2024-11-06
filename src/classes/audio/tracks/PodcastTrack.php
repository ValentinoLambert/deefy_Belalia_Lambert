<?php
declare(strict_types=1);
namespace iutnc\deefy\audio\tracks;

/**
 * Classe représentant une piste de podcast.
 */
class PodcastTrack extends AudioTrack {

    /**
     * Constructeur de la classe PodcastTrack.
     * @param string $nom Nom de la piste.
     * @param string $chemin Chemin de la piste.
     */
    public function __construct(string $nom, string $chemin){
        parent::__construct($nom, $chemin);
    }
}