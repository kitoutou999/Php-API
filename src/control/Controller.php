<?php

require_once("view/View.php");
require_once("model/Animal.php");
require_once("model/AnimalStorage.php");
require_once("model/AnimalBuilder.php");

class Controller {

    private View $view;
    private AnimalStorage $animalStorage;

    function __construct(View $view, AnimalStorage $animalStorage) {
        $this->view = $view;
        $this->animalStorage = $animalStorage;
    }

    public function showInformation(String $id=""):void {
        $animal = $this->animalStorage->read($id);
        if($animal !== null) {
            $this->view->prepareAnimalPage($animal->getNom(), $animal->getEspece(), $animal->getAge(), $animal->getPathImage());
        } else {
            $this->view->prepareUnknowAnimalPage();
        }
        $this->view->render();
    }

    public function showList(): void {
        $this->view->prepareListPage($this->animalStorage->readAll());
        $this->view->render();
    }

    public function showAccueil():void {
        $this->view->prepareAcceuilPage();
        $this->view->render();
    }

    public function createNewAnimal(){
        $this->view->prepareAnimalCreationPage(new AnimalBuilder());
        $this->view->render();
    }

    public function showError($message) {
        $this->view->renderError($message);
        $this->view->render();
    }

    public function saveNewAnimal(array $data, array $file) {
        $animalBuilder = new AnimalBuilder($data, $file);
        if ($animalBuilder->isValid()) {
            $animal = $animalBuilder->createAnimal();
            $id = $this->animalStorage->create($animal);
            $this->view->displayAnimalCreationSuccess($id);
        } else {
            $this->view->prepareAnimalCreationPage($animalBuilder);
            $this->view->render();
        }
    }

}

?>