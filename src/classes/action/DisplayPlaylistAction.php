<?php
namespace iutnc\deefy\action;

use iutnc\deefy\audio\lists\PlayList;
use iutnc\deefy\render\AudioListRenderer;

/**
 * Action pour afficher une playlist en fonction de son ID.
 */
class DisplayPlaylistAction extends Action {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Exécute l'action pour afficher une playlist en fonction de son ID.
     * @return string
     */
    public function execute(): string {
        $res = "";
        if (isset($_GET['id']) && is_numeric($_GET['id'])) {
            $playlistId = intval($_GET['id']);
            $playlist = PlayList::find($playlistId);
            if ($playlist) {
                $renderer = new AudioListRenderer($playlist);
                $res = $renderer->render();
            } else {
                $res = "<p>Erreur : Playlist non trouvée pour l'ID spécifié.</p>";
            }
        }
        else if ($this->http_method == "GET") {
            $res = <<<HTML
                <h2>Rechercher une Playlist par ID</h2>
                <form method="post" action="?action=display-playlist">
                    <label for="playlist_id">ID de la Playlist :</label>
                    <input type="number" name="playlist_id" id="playlist_id" required>
                    <input type="submit" value="Afficher la Playlist">
                </form>
            HTML;
        }
        else if ($this->http_method == "POST" && isset($_POST['playlist_id']) && is_numeric($_POST['playlist_id'])) {
            $playlistId = intval($_POST['playlist_id']);
            $playlist = PlayList::find($playlistId);

            if ($playlist) {
                $renderer = new AudioListRenderer($playlist);
                $res = $renderer->render();
            } else {
                $res = "<p>Erreur : Playlist non trouvée pour l'ID spécifié.</p>";
            }
        } else {
            $res = "<p>Erreur : Aucun ID de playlist spécifié ou ID invalide.</p>";
        }

        return $res;
    }
}
