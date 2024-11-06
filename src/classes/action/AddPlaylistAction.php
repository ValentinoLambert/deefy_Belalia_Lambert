<?php

namespace iutnc\deefy\action;

use iutnc\deefy\repository\Repository;

/**
 * Classe AddPlaylistAction, il permet d'ajouter une playlist
 */
class AddPlaylistAction extends Action
{

    /**
     * Constructeur de la classe AddPlaylistAction
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Méthode execute, qui permet d'ajouter une playlist
     * @return string
     */
    public function execute(): string
    {

        // Si la méthode HTTP est POST on ajoute la playlist, sinon on affiche le formulaire
        if ($this->http_method == "POST") {

            // Vérifier si le nom de la playlist est défini, et le nettoyer
            $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);

            // Appel du repository pour ajouter la playlist
            $repository = new Repository();
            $playlistId = $repository->addPlaylist($nom);

            // Si la playlist n'a pas été ajoutée, on retourne une erreur, si l'utilisateur n'est pas connecté on retourne une erreur
            if (!$playlistId) {
                return "<p>Erreur : Impossible de créer la playlist.</p>";
            }
            if (!isset($_SESSION['user']['id'])) {
                return "<p>Erreur : utilisateur non connecté.</p>";
            }

            // On lie la playlist à l'utilisateur via la requete SQL
            $userId = $_SESSION['user']['id'];
            $repository->linkPlaylistToUser($userId, $playlistId);

            // On met à jour la session avec l'ID de la playlist courante
            $_SESSION['playlist'] = $playlistId;
            return "<p>Playlist '$nom' créée et définie comme playlist courante.</p>";
        } else {

            // Formulaire pour ajouter une playlist
            return <<<HTML
                <form method="post" action="?action=add-playlist">
                    <label for="nom">Nom de la Playlist :</label>
                    <input type="text" name="nom" id="nom" required>
                    <input type="submit" value="Créer Playlist">
                </form>
            HTML;
        }
    }
}
