<?php

require_once("AnimalStorage.php");
require_once("Animal.php");

class AnimalBuilder {

    public const NAME_REF = 'nom';
    public const SPECIES_REF = 'espece';
    public const AGE_REF = 'age';
    public const IMG_REF = 'image';
    public const DEST_IMG = "tmp/";
    private array $data;
    private array $file;
    private array $error;

    function __construct(array $data = [], array $file = []) {
        $this->data = array_merge([
            self::NAME_REF => '',
            self::SPECIES_REF => '',
            self::AGE_REF => 0,
        ], $data);
        $this->file = $file;
        $this->error = array(AnimalBuilder::NAME_REF => "", AnimalBuilder::SPECIES_REF => "", AnimalBuilder::AGE_REF => "", AnimalBuilder::IMG_REF => "");
    }

    public function getError(): array {
        return $this->error;
    }

    public function getData(): array {
        return $this->data;
    }

    public function getFile(): array {
        return $this->file;
    }

    public function createAnimal(): Animal {
        $nameImage = uniqid() . '.' . pathinfo($this->file['image']['name'], PATHINFO_EXTENSION);
        if (move_uploaded_file($this->file['image']['tmp_name'], AnimalBuilder::DEST_IMG . $nameImage)) {
            return new Animal(
                htmlspecialchars($this->data[self::NAME_REF]),
                htmlspecialchars($this->data[self::SPECIES_REF]),
                $this->data[self::AGE_REF],
                0,
                $nameImage
            );
        } else {
            throw new \Exception("Erreur lors de la sauvegarde de l'image");
        }
    }

    public function isValid(): bool {
        if ($this->data[AnimalBuilder::NAME_REF] === "") {
            $this->error[AnimalBuilder::NAME_REF] = "Erreur : champ vide";
        }
        if ($this->data[AnimalBuilder::SPECIES_REF] === "") {
            $this->error[AnimalBuilder::SPECIES_REF] = "Erreur : champ vide";
        }
        if ($this->data[AnimalBuilder::AGE_REF] <= 0) {
            $this->error[AnimalBuilder::AGE_REF] = "Erreur : Age positif requis";
        }
        if (key_exists('image', $this->file)) {
            switch ($this->file['image']['error']) {
                case UPLOAD_ERR_NO_FILE:
                    $this->error[AnimalBuilder::IMG_REF] = "Veuillez ajouter une image !";
                    break;
                case UPLOAD_ERR_INI_SIZE:
                    $this->error[AnimalBuilder::IMG_REF] = "L'image dépasse la taille maximale autorisée !";
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $this->error[AnimalBuilder::IMG_REF] = "Image partiellement uploadé !";
                    break;
            }
        }
        if ($this->file['image']['error'] === UPLOAD_ERR_OK) {
            $imageType = exif_imagetype($this->file['image']['tmp_name']);
            if ($imageType === false) {
                $this->error[AnimalBuilder::IMG_REF] = "Ce n'est pas une image !";
            }
        }
        return empty(array_filter($this->error));
    }
}

?>