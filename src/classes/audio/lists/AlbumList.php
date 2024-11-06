<?php

declare(strict_types=1);
namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\NonEditablePropertyException;

/**
 * Classe représentant une liste d'albums.
 */
class AlbumList extends AudioList{

    /**
     * Attributs de la classe AlbumList.
     * @var String Nom de l'artiste.
     * @var int Date de sortie de l'album.
     */
    private String $artiste;
    private int $dateSortie;

    /**
     * Constructeur de la classe AlbumList.
     * @param String $nom Nom de l'album.
     * @param iterable $tab Tableau de pistes.
     * @param String $artiste Nom de l'artiste.
     * @param int $date Date de sortie de l'album.
     */
    public function __construct(String $nom, iterable $tab, String $artiste, int $date){
        parent::__construct($nom, $tab);
        $this->artiste = $artiste;
        $this->dateSortie = $date;
    }

    /**
     * Setter de la classe AlbumList, dans ce cas on essaye de faire que les attributs nom, nbPiste, dureeTotale et list soient non modifiables.
     * @param String $arg1 Nom de l'attribut.
     * @return mixed
     */
    public function __set(String $arg1, mixed $arg2):void{
        if($arg1==="nom"||$arg1==="nbPiste"||$arg1==="dureeTotale"||$arg1==="list") throw new NonEditablePropertyException("On ne peux pas modifier : $arg1");;
        if(property_exists($this, $arg1)){$this->$arg1=$arg2;
        }else{throw new InvalidPropertyNameException ("$arg1: invalid property");}
    }

}