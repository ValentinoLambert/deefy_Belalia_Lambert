<?php
namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\PlayList;

class SelectPlaylistAction extends Action {
    public function execute(): string {
        // Vérifier si un ID de playlist est passé dans l'URL
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $playlistId = (int)$_GET['id'];

            // Vérifier que la playlist existe
            $playlist = PlayList::find($playlistId);
            if ($playlist) {
                // Mettre à jour la session avec l'ID de la playlist courante
                $_SESSION['playlist'] = $playlistId;

                // Rediriger vers la page de la playlist courante
                header("Location: ?action=display-current-playlist");
                exit();
            } else {
                return "<p>Erreur : Playlist non trouvée.</p>";
            }
        } else {
            return "<p>Erreur : Aucun ID de playlist spécifié.</p>";
        }
    }
}
