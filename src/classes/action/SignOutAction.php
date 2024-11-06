<?php
namespace iutnc\deefy\action;

class SignOutAction extends Action {

    public function __construct(){
        parent::__construct();
    }

    public function execute() : string {
        session_destroy();
        header("Location: ?action=sign-in");
        exit();
    }
}