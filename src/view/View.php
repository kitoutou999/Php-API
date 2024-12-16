<?php

interface View {
    public function render(): void;
    public function prepareAnimalPage(String $name, String $species, int $age, String $image): void;
    public function prepareUnknowAnimalPage(): void;
    public function prepareListPage(array $animalTab): void;
}

?>