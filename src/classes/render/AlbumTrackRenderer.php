<?php

declare(strict_types=1);
namespace iutnc\deefy\render;

/**
 * Class AlbumTrackRenderer qui permet de rendre une piste d'album
 */
class AlbumTrackRenderer extends AudioTrackRenderer
{

    /**
     * Constructeur de la classe AlbumTrackRenderer
     */
    public function __construct(){
        parent::__construct();
     }

    /**
     * Méthode long qui permet de rendre une piste d'album en rendu long
     * @return string
     */
    public function long():string{
        return "<p>Titre: {$this->piste->titre}</p> 
                <p>Album: {$this->piste->album}</p> 
                <p>Genre: {$this->piste->genre}</p> 
                <p>Durée: {$this->piste->duree}</p> 
                <p>Numéro: {$this->piste->numPiste}</p>  
                <p>Année: {$this->piste->annee}</p> 
                <p>Emplacement du fichier: {$this->piste->nomFichier}</p>";
    }

    /**
     * Méthode compact qui permet de rendre une piste d'album en rendu compact
     * @return string
     */
    public function compact():string{
        return "<p>{$this->piste->__toString()}</p>";
    }   
}
