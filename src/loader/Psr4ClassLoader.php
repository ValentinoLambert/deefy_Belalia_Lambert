<?php

declare(strict_types=1);

namespace loader\Psr4ClassLoader;
class Psr4ClassLoader{
    private String $prefixe, $root;

    public function __construct(String $p, String $r){
        $this->$prefixe = $p;
        $this->$root = $r;
    }   

    public function loadClass(String $nom):void{
    }
    public function register():void{
        spl_autoload_register([$this, 'loadClass']);
    }
}