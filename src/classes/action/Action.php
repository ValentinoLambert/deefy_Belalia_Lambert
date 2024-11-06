<?php

namespace iutnc\deefy\action;

/**
 * Classe abstraite Action, celui ci sert a définir les actions que l'utilisateur peut effectuer
 */
abstract class Action {

    /**
     * Attributs de la classe Action, http_method, hostname et script_name
     * @var string|mixed|null
     */
    protected ?string $http_method = null;
    protected ?string $hostname = null;
    protected ?string $script_name = null;

    /**
     * Constructeur de la classe Action
     */
    public function __construct(){
        $this->http_method = $_SERVER['REQUEST_METHOD'];
        $this->hostname = $_SERVER['HTTP_HOST'];
        $this->script_name = $_SERVER['SCRIPT_NAME'];
    }

    /**
     * Méthode abstraite execute, qui sera implémentée dans les classes filles
     * @return string
     */
    abstract public function execute() : string;
    
}