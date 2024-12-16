<?php
include_once("AnimalStorage.php");

class AnimalStorageMySQL implements AnimalStorage {
    
    private $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function create(Animal $animal) {
        try {
            $rq = "INSERT INTO animals (name, species, age, image) VALUES (:name, :species, :age, :image)";
            $stmt = $this->pdo->prepare($rq);
            $data = array(":name" => $animal->getNom(), ":species" => $animal->getEspece(), ":age" => $animal->getAge(), ":image" => $animal->getPathImage());
            $stmt->execute($data);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            unlink("tmp/" . $animal->getPathImage());
            throw new \Exception("Erreur de creation de l'animal : " . $e->getMessage());
        }
    }

    public function read($id) {
        try {
            $rq = "SELECT * FROM animals WHERE id = :id";
            $stmt = $this->pdo->prepare($rq);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row === false) {
                return null;
            }
            return new Animal($row["name"], $row["species"], $row["age"], $row["id"], $row['image']);

        } catch (PDOException $e) {
            throw new \Exception("Erreur de lecture de l'animal : " . $e->getMessage());
        }
    }

    public function update($id, Animal $animal) {
        throw new \Exception("Not implemented yet");
    }

    public function delete($id) {
        throw new \Exception("Not implemented yet");
    }

    public function readAll() {
        try {
            $rq = "SELECT * FROM animals";
            $stmt = $this->pdo->prepare($rq);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $animals = [];

            foreach ($rows as $row) {
                $animals[] = new Animal($row['name'], $row['species'], $row['age'], $row['id'], $row['image']);
                
            }
            return $animals;

        } catch (PDOException $e) {
            throw new \Exception("Erreur de lecture des animaux :  " . $e->getMessage());
        }
    }


}
?>