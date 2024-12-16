<?php
require_once("control/Controller.php");
require_once("view/JsonView.php");

class APIRouter {
    private $controller;
    private $view;

    public function __construct(AnimalStorage $animalStorage) {
        $this->view = new JsonView($this, "");
        $this->controller = new Controller($this->view, $animalStorage);
    }

    public function ApiRoutes(): void {
        if (isset($_GET['collection']) && $_GET['collection'] === 'animaux') {
            if (isset($_GET['id'])) {
                $this->controller->showInformation($_GET['id']);
            } else {
                $this->controller->showList();
            }
        } else {
            $this->controller->showError("API endpoint pas valide");
        }
    }

    public function main(): void {
        $this->ApiRoutes();
    }
}
?>