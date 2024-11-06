<?php
namespace iutnc\deefy\action;

use iutnc\deefy\auth\Auth;
use iutnc\deefy\repository\Repository;
use iutnc\deefy\exception\AuthException;

class SigninAction extends Action
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(): string
    {
        $res = "";
        if ($this->http_method == "GET") {
            $res = '<form method="post" action="?action=sign-in">
                <input type="email" name="email" placeholder="email" autofocus>
                <input type="password" name="password" placeholder="mot de passe">
                <input type="submit" name="connex" value="Connexion">
                </form>';
        } else {
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            try {
                if (Auth::authenticate($email, $password)) {
                    $repository = new Repository();
                    $playlists = $repository->findPlaylistIdByName($email);

                    $res = "<h3>Connexion réussie pour $email</h3><h3>Playlists de l'utilisateur : </h3>";
                    foreach ($playlists as $playlist) {
                        $res .= '<a href="?action=display-playlist&id=' . $playlist['id'] . '"> - ' . $playlist['nom'] . '</a>';
                    }
                }
            } catch (AuthException $e) {
                $res = "<p>Identifiant ou mot de passe invalide</p>";
            }
        }
        return $res;
    }
}
