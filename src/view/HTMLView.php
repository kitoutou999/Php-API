<?php

require_once("PathInfoRouter.php");
require_once("view/View.php");

class HTMLView implements View {

    private String $title;
    private String $content;
    private array $menu;
    private PathInfoRouter $router;
    private String $feedback;

    function __construct(PathInfoRouter $router, String $feedback = "") {
        $this->title = "";
        $this->content = "";
        $this->router = $router;
        $this->feedback = $feedback;
        $this->menu = array(
            "/TW4b-2024/groupe-3/site.php/liste" => "Liste",
            "/TW4b-2024/groupe-3/site.php/accueil" => "Accueil",
            $router->getAnimalCreationURL() => "Nouveau",
        );
    }

    public function render():void {
        include_once("squelette.html");
    }

    public function prepareTestPage(): void {
        $this->title = "Test";
        $this->content = "Page de test";
    }

    public function prepareDebugPage($variable) {
        $this->title = 'Debug';
        $this->content = '<pre>'.htmlspecialchars(var_export($variable, true)).'</pre>';
    }

    public function prepareAcceuilPage(): void {
        $this->title = "Accueil";
        $this->content = "Page de bienvenue";
    }

    public function prepareAnimalPage(String $name, String $species, int $age, String $image): void {
        $this->title = $name;
        $this->content = "<span>« " . $name . " est un animal de l'espèce " . $species . " qui a " . $age . " ans ».</span>";
        $this->content .= '<br><br><img class="fit-picture" src="../tmp/' . $image . '" alt="aucun image pour cet animal">';
    }

    public function prepareUnknowAnimalPage(): void {
        $this->title = "Erreur d'identifiant";
        $this->content = "Cette indentifiant n'existe pas";
    }

    public function prepareListPage(array $animalTab): void {
        $this->title = "Liste des animaux";
        $this->content = "<ul>";
        foreach($animalTab as $key => $animal) {
            $url = $this->router->getAnimalURL($animal->getId());
            $this->content .= "<li><a href=\"" . $url . "\">" . $animal->getNom() . "</a></li>";
        }
        $this->content .= "</ul>";
    }

    public function prepareAnimalCreationPage(AnimalBuilder $animal) {
        $error = $animal->getError();
        $data = $animal->getData();
        $this->title = "Formulaire";
        $url = $this->router->getAnimalSaveURL();
        include("formulaire.html");
        $this->content = $corps;
    }

    public function displayAnimalCreationSuccess($id): void {
        $this->router->POSTredirect("/TW4b-2024/groupe-3/site.php/$id", "<p style=\"color:#10a010;\">Animal créé avec succes</p>");
    }

}

?>
