<?php

namespace iutnc\deefy\action;
use iutnc\deefy\auth\Auth;

/**
 * Class AddUserAction qui permet d'ajouter un utilisateur
 */
class AddUserAction extends Action {

    /**
     * Constructeur de la classe AddUserAction
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * Méthode execute qui permet d'ajouter un utilisateur, si l'utilisateur n'est pas connecté il peut s'inscrire
     * @return string
     */
    public function execute() : string {
        $res = "";

        // Si la méthode HTTP est GET, afficher le formulaire d'inscription, sinon traiter les données du formulaire
        if ($this->http_method == "GET") {

            // Formulaire d'inscription
            $res = '<form method="post" action="?action=add-user">
                <input type="email" name="email" placeholder="email" autofocus>
                <input type="password" name="passwd1" placeholder="password 1">
                <input type="password" name="passwd2" placeholder="password 2">
                <input type="submit" name="connex" value="Connexion">
                </form>';
        } else {

            // Traitement des données du formulaire, vérification de la validité de l'email et de la longueur du mot de passe
            $e = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $p1 = $_POST['passwd1'];
            $p2 = $_POST['passwd2'];
            if (strlen($p1) < 10) {
                $res = '<p>Mot de passe trop court</p>
                <form method="post" action="?action=add-user">
                <input type="email" name="email" placeholder="email" autofocus>
                <input type="password" name="passwd1" placeholder="password 1">
                <input type="password" name="passwd2" placeholder="password 2">
                <input type="submit" name="connex" value="Connexion">
                </form>';

            // Si les mots de passe sont identiques, on appelle la méthode register de la classe Auth
            } elseif ($p1 === $p2) {
                $res = "<p>" . Auth::register($e, $p1) . "</p>";

            // Sinon on affiche un message d'erreur et on réaffiche le formulaire
            } else {
                $res = '<p>Mot de passe 1 et 2 différents</p>
                <form method="post" action="?action=add-user">
                <input type="email" name="email" placeholder="email" autofocus>
                <input type="password" name="passwd1" placeholder="password 1">
                <input type="password" name="passwd2" placeholder="password 2">
                <input type="submit" name="connex" value="Connexion">
                </form>';
            }
        }
        return $res;
    }
}