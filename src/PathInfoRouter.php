<?php

require_once("control/Controller.php");
require_once("view/HTMLView.php");

class PathInfoRouter {

    private Controller $controller;
    private View $view;

    public function __construct(AnimalStorage $animalStorage) {
        $feedback = key_exists("feedback", $_SESSION) ? $_SESSION["feedback"] : "";
        $_SESSION['feedback'] = "";
        $this->view = new HTMLView($this, $feedback);
        $this->controller = new Controller($this->view, $animalStorage);
    }

    public function WebRoutes(string $pathInfo): void {
        if ($pathInfo === "liste") {
            $this->controller->showList();
        } elseif ($pathInfo === "accueil") {
            $this->controller->showAccueil();
        } elseif ($pathInfo === "nouveau") {
            $this->controller->createNewAnimal();
        } elseif ($pathInfo === "sauverNouveau") {
            $this->controller->saveNewAnimal($_POST, $_FILES);
        } else {
            $this->controller->showInformation($pathInfo);
        }
    }

    public function main(): void {
        if (key_exists("PATH_INFO", $_SERVER)) {
            $pathInfo = ltrim($_SERVER["PATH_INFO"], '/');
            $this->WebRoutes($pathInfo);
        } else {
            $this->controller->showAccueil();
        }
    }

    public function getAnimalCreationURL(): string {
        return "/TW4b-2024/groupe-3/site.php/nouveau";
    }

    public function getAnimalSaveURL(): string {
        return "/TW4b-2024/groupe-3/site.php/sauverNouveau";
    }

    public function getAnimalURL(string $id): string {
        return "/TW4b-2024/groupe-3/site.php/" . $id;  
    }

    public function POSTredirect($url, $feedback): void {
        $_SESSION['feedback'] = $feedback;
        header("Location: " . $url, true, 303);
        exit;
    }

}

?>