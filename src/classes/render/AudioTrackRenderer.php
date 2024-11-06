<?php

declare(strict_types=1);

namespace iutnc\deefy\render;

use iutnc\deefy\audio\tracks\AudioTrack;

/**
 * Classe AudioTrackRenderer qui permet de rendre une piste audio
 */
abstract class AudioTrackRenderer implements Renderer
{

    /**
     * Attribut de la classe AudioTrackRenderer, piste
     * @var AudioTrack
     */
    protected AudioTrack $piste;

    /**
     * Constructeur de la classe AudioTrackRenderer
     * @param AudioTrack $piste
     */
    public function __construct(AudioTrack $piste){
        $this->piste = $piste;
    }

    /**
     * Méthode render qui permet de rendre une piste audio soit en rendu long soit en rendu compact
     * @param int $selector
     * @return string
     */
    public function render(int $selector): string{
        $res = "";
        // Switch pour choisir le rendu
        switch($selector){
            case (Renderer::COMPACT):
                $res = $this->compact();
                break;
            case (Renderer::LONG):
                $res = $this->long();
                break;
        }
        return $res;
    }

    /**
     * Méthode long qui permet de rendre une piste audio en rendu long
     * @return string
     */
    public abstract function long():string;

    /**
     * Méthode compact qui permet de rendre une piste audio en rendu compact
     * @return string
     */
    public abstract function compact():string;
}
