<?php

class Animal {

    private String $nom;
    private String $espece;
    private int $age;
    private int $id;
    private String $pathImage;

    function __construct(String $nom, String $espece, int $age, int $id, String $pathImage) {
        $this->nom = $nom;
        $this->espece = $espece;
        $this->age = $age;
        $this->id = $id;
        $this->pathImage = $pathImage;
    }

    public function getNom(): String {
        return $this->nom;
    }

    public function getEspece(): String {
        return $this->espece;
    }

    public function getAge(): int {
        return $this->age;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getPathImage(): String {
        return $this->pathImage;
    }

}

?>