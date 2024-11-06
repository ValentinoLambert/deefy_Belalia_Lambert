<?php

namespace iutnc\deefy\render;

/**
 * Classe AudioListRenderer qui permet de rendre une liste audio
 */
class AudioListRenderer {

    /**
     * Attributs de la classe AudioListRenderer, playlist et tracks
     * @var PlayList
     */
    protected $playlist;
    protected $tracks;

    /**
     * Constructeur de la classe AudioListRenderer
     * @param $playlist
     * @param $tracks
     */
    public function __construct($playlist, $tracks = []) {
        $this->playlist = $playlist;
        $this->tracks = $tracks;
    }

    /**
     * Méthode render qui permet de rendre une liste audio
     * @return string
     */
    public function render(): string {
        // Affichage du nom de la playlist
        $output = "<h2>Playlist : {$this->playlist->nom}</h2>";

        // Si la playlist est vide, afficher un message, sinon afficher les pistes
        if (empty($this->tracks)) {
            $output .= "<p>Aucune piste dans cette playlist.</p>";
        } else {
            $output .= "<ul>";

            // Pour chaque piste, afficher un lecteur audio
            foreach ($this->tracks as $track) {

                // Créer le chemin du fichier audio
                $audioPath = 'uploads/' . $track['filename'];

                // Affichage de chaque piste avec un lecteur audio
                $output .= "<li>";
                $output .= "<p><strong>{$track['titre']}</strong> - Genre : {$track['genre']} - Durée : {$track['duree']} sec</p>";
                $output .= "<audio controls>
                              <source src='$audioPath' type='audio/mpeg'>
                              Votre navigateur ne supporte pas la lecture audio.
                            </audio>";
                $output .= "</li>";
            }
            $output .= "</ul>";
        }

        // bouton qui redirige vers la page d'ajout de piste
        $output .= "<br>";
        $output .= "<a href='?action=add-podcasttrack&playlist_id={$this->playlist->id}'>Ajouter une piste</a>";
        return $output;
    }
}
