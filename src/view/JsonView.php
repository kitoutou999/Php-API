<?php
require_once("View.php");

class JsonView implements View {

    private String $feedback;
    private String $title;
    private array $content;
    private APIRouter $router;

    function __construct(APIRouter $router, String $feedback = "") {
        $this->title = "";
        $this->content = array(); 
        $this->feedback = $feedback;
        $this->router = $router;
   
    }

    public function render(): void {
        header('Content-Type: application/json');
        echo json_encode($this->content, JSON_PRETTY_PRINT);
    }

    public function prepareAnimalPage(String $name, String $species, int $age, String $image): void {
        $this->content = array(
            "nom" => $name,
            "espece" => $species,
            "age" => $age
        );
    }
    public function prepareUnknowAnimalPage(): void {
        $this->content = array(
            "error" => "Animal inconnu"
        );
    }

    public function prepareListPage(array $animalTab): void {
        $this->title = "Liste des animaux";
        $this->content = array();

        foreach($animalTab as $key => $animal) {
            $this->content[] = array(
                "id" => $animal->getId(), 
                "nom" => $animal->getNom()
            );
        }
    }

    public function renderError(String $message): void {
        $this->content = array("status" => "error","message" => $message);
    }

    public function prepareAcceuilPage(): void {
        $this->content = array("status" => "error","message" => "Il manque des paramètres");
    }

}

?>