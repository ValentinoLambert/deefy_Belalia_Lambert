<?php
namespace iutnc\deefy\action;

use iutnc\deefy\auth\Auth;
use iutnc\deefy\audio\lists\PlayList;

class MyPlaylistsAction extends Action {

    public function __construct(){
        parent::__construct();
    }

    public function execute() : string {
    $res = "";
    if (isset($_SESSION['user']['id'])) {
        $userId = (int) $_SESSION['user']['id']; // Cast to integer
        $playlists = PlayList::findByUserId($userId);

        if (count($playlists) > 0) {
            $res .= "<h2>Mes Playlists</h2><ul>";
            foreach ($playlists as $playlist) {
                // Chaque lien redirige vers `select-playlist` avec l'ID de la playlist
                $res .= "<li><a href='?action=select-playlist&id={$playlist->id}'>{$playlist->nom}</a></li>";
            }
            $res .= "</ul>";
            $res .= "<br>";
            //bouton ajouter playlist, redirection vers add-playlist
            $res .= "<a href='?action=add-playlist'>Ajouter une playlist</a>";

        } else {
            $res .= "<p>Aucune playlist trouvée.</p>";
            $res .= "<br>";
            //bouton ajouter playlist, redirection vers add-playlist
            $res .= "<a href='?action=add-playlist'>Ajouter une playlist</a>";
        }
    } else {
        $res .= "<p>Vous devez être connecté pour voir vos playlists.</p>";
    }
    return $res;
}
}