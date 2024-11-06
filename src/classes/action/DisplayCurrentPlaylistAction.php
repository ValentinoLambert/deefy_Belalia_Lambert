<?php
namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\PlayList;
use iutnc\deefy\render\AudioListRenderer;

/**
 * Action pour afficher la playlist courante.
 */
class DisplayCurrentPlaylistAction extends Action {

    /**
     * Methe execution pour afficher la playlist courante, l'action est appele par le dispatcheur.
     * @return string
     */
    public function execute(): string {

        // Vérifier si une playlist est définie dans la session, sinon retourner une erreur
        if (isset($_SESSION['playlist'])) {
            $playlistId = $_SESSION['playlist'];

            // ici je recupere la playlist
            $playlist = PlayList::find($playlistId);

            // Si la playlist existe, on la rend avec le rendu de la liste audio (classe AudioListRenderer), sinon on retourne une erreur
            if ($playlist) {
                $tracks = $playlist->getTracks();
                $renderer = new AudioListRenderer($playlist, $tracks);
                return $renderer->render();
            } else {
                return "<p>Erreur : Playlist non trouvée.</p>";
            }
        } else {
            return "<p>Aucune playlist courante.</p>";
        }
    }
}
