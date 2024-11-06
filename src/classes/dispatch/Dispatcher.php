<?php

namespace iutnc\deefy\dispatch;

use iutnc\deefy\action\AddUserAction;
use iutnc\deefy\action\AddPlaylistAction;
use iutnc\deefy\action\AddPodcasttrackAction;
use iutnc\deefy\action\SigninAction;
use iutnc\deefy\action\DisplayPlaylistAction;
use iutnc\deefy\action\MyPlaylistsAction;
use iutnc\deefy\action\DisplayCurrentPlaylistAction;
use iutnc\deefy\action\ProfileAction;
use iutnc\deefy\action\SignOutAction;
use iutnc\deefy\auth\Auth;
use iutnc\deefy\action\SelectPlaylistAction;

/**
 * Classe pour dispatcher les actions.
 */
class Dispatcher {

    /**
     * Attribut de la classe Dispatcher.
     * @var string|mixed Action à effectuer.
     */
    private string $action;

    /**
     * Constructeur de la classe Dispatcher.
     */
    public function __construct(){
        $this->action = "";
        if (isset($_GET['action'])) $this->action = $_GET['action'];
    }

    /**
     * Methode run, celle ci permet de lancer l'action demandée parmi les actions possibles.
     * @return void
     */
    public function run(): void {

        // On démarre la session si elle n'est pas déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $res = "Bienvenue !";
        $action = null;

        // On choisit l'action à effectuer en fonction de l'action demandée
        switch ($this->action) {
            case 'sign-in':
                $action = new SigninAction();
                break;
            case 'add-user':
                $action = new AddUserAction();
                break;
            case 'sign-out':
                $action = new SignOutAction();
                break;
            case 'my-playlists':
                $action = new MyPlaylistsAction();
                break;
            case 'add-playlist':
                $action = new AddPlaylistAction();
                break;
            case 'add-podcasttrack':
                $action = new AddPodcasttrackAction();
                break;
            case 'display-playlist':
                $action = new DisplayPlaylistAction();
                break;
            case 'display-current-playlist':
                $action = new DisplayCurrentPlaylistAction();
                break;
            case 'profile':
                $action = new ProfileAction();
                break;
            case 'select-playlist':
                $action = new SelectPlaylistAction();
                break;
            default:
                $res = "Bienvenue !";
                break;
        }

        // On exécute l'action si elle a été trouvée
        if ($action !== null) {
            $res = $action->execute();
        }

        // On affiche la page
        $this->renderPage($res);
    }

    /**
     * Methode pour afficher la page.
     * @param string $html
     * @return void
     */
    private function renderPage(string $html): void {
        $authLinks = '';

        // On affiche les liens en fonction du rôle de l'utilisateur, admin, standard ou non authentifié
        if (Auth::isStandard()) {
            $authLinks = <<<HTML
                <li><a href="?action=profile">Mon profil</a></li>
                <li><a href="?action=my-playlists">Mes playlists</a></li>
                <li><a href="?action=display-current-playlist">Playlist courante</a></li>
                <li><a href="?action=sign-out">Se déconnecter</a></li>
            HTML;
        } else if (Auth::isAdmin()) {
            $authLinks = <<<HTML
                <li><a href="?action=profile">Mon profil</a></li>
                <li><a href="?action=my-playlists">Mes playlists</a></li>
                <li><a href="?action=display-current-playlist">Playlist courante</a></li>
                <li><a href="?action=display-playlist">Afficher une playlist</a></li>
                <li><a href="?action=sign-out">Se déconnecter</a></li>
            HTML;
        } else {
            $authLinks = <<<HTML
                <li><a href="?action=sign-in">Se connecter</a></li>
                <li><a href="?action=add-user">Inscription</a></li>
            HTML;
        }

        // On affiche la page
        echo <<<end
            <!DOCTYPE html>
            <html lang="fr" dir="ltr">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="initial-scale=1.0">
                <link rel="stylesheet" href="./style.css">
                <title>Index</title>
            </head>
            <body>
                <h1>Deefy</h1>
                <nav class="navbar navbar-expand-lg navbar-light bg-light">
                        <ul class="navbar-nav mr-auto">
                            $authLinks
                        </ul>
                </nav>
                <div class="container">
                    $html
                </div>
            </body>
            <footer>
                <p>&copy; 2024 - BELALIA BENDJAFAR, Amin - LAMBERT, Valentino</p>
            </footer>
            </html>
            end;
    }
}