<?php

declare(strict_types=1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\PodcastTrack;
use iutnc\deefy\audio\tracks\AudioTrack;

/**
 * Classe PodcastTrackRenderer qui permet de rendre une piste de podcast
 */
class PodcastTrackRenderer extends AudioTrackRenderer
{

    /**
     * Constructeur de la classe PodcastTrackRenderer
     * @var AudioTrack|PodcastTrack
     */
    public AudioTrack $piste;

    /**
     * Constructeur de la classe PodcastTrackRenderer
     * @param PodcastTrack $piste
     */
    public function __construct(PodcastTrack $piste){
        $this->piste = $piste;
    }

    /**
     * Méthode long qui permet de rendre une piste de podcast en rendu long
     * @return string
     */
    public function long():string{
        return  "<p>Titre: {$this->piste->titre}</p> 
                <p>Genre: {$this->piste->genre}</p> 
                <p>Duree: {$this->piste->duree}</p>  
                <p>Année: {$this->piste->annee}</p> 
                <p>Emplacement du fichier: {$this->piste->nomFichier}</p>";
    }

    /**
     * Méthode compact qui permet de rendre une piste de podcast en rendu compact
     * @return string
     */
    public function compact():string{
        return "<p>{$this->piste->__toString()}</p>";
    } 
}
