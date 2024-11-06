<?php
namespace iutnc\deefy\action;

use iutnc\deefy\user\User;

class ProfileAction extends Action {

    public function __construct(){
        parent::__construct();
    }

    public function execute() : string {
        $res = "";
        if (isset($_SESSION['user']) && is_array($_SESSION['user']) && isset($_SESSION['user']['id']) && isset($_SESSION['user']['role'])) {
            // MONTRER LE PROFIL DE L'UTILISATEUR
            $email = $_SESSION['user']['email'] ?? 'Email inconnu';
            $u = new User($_SESSION['user']['id'], $email, $_SESSION['user']['role']);
            $res = "<h2>Profil de l'utilisateur : " . $u->getEmail() . "</h2>";
            if ($_SESSION['user']['role'] == 1) {
                $r = "<h3> Vous etes un utilisateur standard</h3>";
            } elseif ($_SESSION['user']['role'] == 100) {
                $r = "<h3> Vous etes un administrateur</h3>";
            } else {
                $r = "<h3> Vous etes un utilisateur inconnu</h3>";
            }
            $res .= $r;
        } else {
            $res = "<p>Vous devez être connecté pour voir votre profil.</p>";
        }
        return $res;
    }
}