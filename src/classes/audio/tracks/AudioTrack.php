<?php

declare(strict_types=1);
namespace iutnc\deefy\audio\tracks;

use iutnc\deefy\exception\InvalidPropertyNameException;
use iutnc\deefy\exception\NonEditablePropertyException;
use iutnc\deefy\exception\InvalidPropertyValueException;

/**
 * Classe etant la base pour les pistes audio.
 */
class AudioTrack{

    /***
     * Attributs de la classe AudioTrack.
     * @var string Titre de la piste.
     * @var string Artiste de la piste.
     * @var string Annee de sortie de la piste.
     *  @var string Genre de la piste.
     * @var int Duree de la piste.
     * @var string Nom du fichier de la piste
     */
    protected string $titre;
    protected string $artiste;
    protected string $annee;
    protected string $genre;
    protected int $duree; 
    protected string $nomFichier;

    /**
     * Constructeur de la classe AudioTrack.
     * @param string $nom Nom de la piste.
     * @param string $chemin Chemin de la piste.
     */
    public function __construct(string $nom, string $chemin){
        $this->titre = $nom;
        $this->nomFichier = $chemin;
        $this->artiste='';
        $this->genre='';
        $this->duree = 0;
        $this->annee='';
    }

    /**
     * Methode magique pour afficher la piste.
     * @return string
     */
    public function __toString() : string{
        return ("<p>{$this->titre} | {$this->artiste}<p><audio controls src={$this->nomFichier}>");
    }

    /**
     * Getter magique de la classe AudioTrack.
     * @param String $arg
     * @return mixed
     * @throws InvalidPropertyNameException
     */
    public function __get(String $arg):mixed{
        if(property_exists($this, $arg)) return $this->$arg;
        throw new InvalidPropertyNameException ("$arg: invalid property");
    }

    /**
     * Setter de la classe AudioTrack, dans ce cas on essaye de faire que les attributs titre et nomFichier soient non modifiables.
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
        }else{throw new InvalidPropertyNameException ("$arg1: invalid property");}
    }

}
