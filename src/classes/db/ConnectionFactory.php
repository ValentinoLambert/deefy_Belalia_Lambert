<?php

declare(strict_types=1);

namespace iutnc\deefy\db;

use PDO;

/**
 * Classe qui sert à créer une connexion à la base de données.
 */
class ConnectionFactory{

    /**
     * Attribut de la classe ConnectionFactory.
     * @var array Tableau de configuration.
     */
    private static array $tab = [];
    public static ?PDO $bd = null;

    /**
     * Methode pour configurer la connexion à la base de donnéesn on met le fichier de configuration dans un tableau.
     * @param String $file
     * @return void
     */
    public static function setConfig(String $file ){
        self::$tab = parse_ini_file($file);
    }

    /**
     * Methode pour créer une connexion à la base de données pdo
     * @return PDO|null
     */
    public static function makeConnection(){
        if(is_null(self::$bd)){
            $res = self::$tab['driver'].":host=".self::$tab['host'].";dbname=".self::$tab['database'];
            self::$bd = new PDO($res, self::$tab['username'], self::$tab['password']);
        }
        return self::$bd ;
    }

}