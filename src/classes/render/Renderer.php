<?php

namespace iutnc\deefy\render;

/**
 * Interface Renderer
 */
interface Renderer{

    /**
     * Constantes pour le rendu
     */
    const COMPACT = 1;
    const LONG = 2;

    /**
     * Méthode render qui permet de rendre un objet
     * @param int $selector
     * @return string
     */
    public function render(int $selector): string;
}