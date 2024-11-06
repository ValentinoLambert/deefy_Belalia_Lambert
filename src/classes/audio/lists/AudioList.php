<?php

declare(strict_types=1);
namespace iutnc\deefy\audio\lists;

use iutnc\deefy\exception\InvalidPropertyNameException;

/**
 * Classe qui sert comme base pour les listes d'audio.
 */
class AudioList {

    /**
     * Attributs de la classe AudioList.
     * @var int ID de la liste.
     * @var String Nom de la liste.
     * @var int Nombre de pistes dans la liste.
     * @var int Durée totale de la liste.
     * @var iterable Liste de pistes.
     */
    protected int $id;
    protected string $nom;
    protected int $nbPiste;
    protected int $dureeTotale;
    protected iterable $list;

    /**
     * Constructeur de la classe AudioList.
     * @param int $id ID de la liste.
     * @param String $nom Nom de la liste.
     * @param iterable $tab Tableau de pistes.
     */
    public function __construct(int $id, string $nom, iterable $tab = []) {
        $this->id = $id;  // Initialisation de l'ID
        $this->nom = $nom;
        $this->list = $tab;
        $this->dureeTotale = 0;
        $this->nbPiste = 0;

        foreach ($tab as $value) {
            $this->nbPiste++;
            $this->dureeTotale += $value->duree;
        }
    }

    /**
     * Getter magique de la classe AudioList.
     * @param String $arg Nom de l'attribut.
     * @return mixed
     */
    public function __get(string $arg): mixed {
        if (property_exists($this, $arg)) return $this->$arg;
        throw new InvalidPropertyNameException("$arg: invalid property");
    }
}
