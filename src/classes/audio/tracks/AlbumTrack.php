<?php

declare(strict_types=1);
namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\NonEditablePropertyException;
use iutnc\deefy\exception\InvalidPropertyValueException;

/**
 * Classe représentant une piste d'album.
 */
class AlbumTrack extends AudioTrack {

    /**
     * Attributs de la classe AlbumTrack.
     * @var string Nom de l'album.
     * @var int Numéro de la piste.
     */
    private string $album;
    private int $numPiste;

    /**
     * Constructeur de la classe AlbumTrack.
     * @param string $nom Nom de la piste.
     * @param string $chemin Chemin de la piste.
     */
    public function __construct(string $nom, string $chemin){
        parent::__construct($nom,$chemin);
        $this->album = "rien";
        $this->numPiste = 0;
    }

    /**
     * Getter magique de la classe AlbumTrack.
     * @param String $arg Nom de l'attribut.
     * @return mixed
     * @throws InvalidPropertyNameException
     */
    public function __get(String $arg):mixed{
        if(property_exists($this, $arg)) return $this->$arg;
        throw new InvalidPropertyNameException ("$arg: invalid property");
    }

    /**
     * Setter de la classe AlbumTrack, dans ce cas on essaye de faire que les attributs titre et nomFichier soient non modifiables.
     * @param String $arg1
     * @param mixed $arg2
     * @return void
     * @throws InvalidPropertyNameException
     * @throws InvalidPropertyValueException
     * @throws NonEditablePropertyException
     */
    public function __set(String $arg1, mixed $arg2):void{
        if($arg1==="titre"||$arg1==="nomFichier") throw new NonEditablePropertyException("On ne peux pas modifier : $arg1");
        if($arg1==="duree"&&(gettype($arg2)!="integer"||$arg2<0)) throw new InvalidPropertyValueException("$arg1 | $arg2 : valeur invalide");
        if(property_exists($this, $arg1)){$this->$arg1=$arg2;
        }else{
            throw new InvalidPropertyNameException ("$arg1: invalid property");
        }
    }
}