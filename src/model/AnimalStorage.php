<?php

require_once("Animal.php");

interface AnimalStorage {

    function read($id);
    function readAll();
    function create(Animal $a);
    function delete($id);
    function update($id,Animal $a);

}

?>