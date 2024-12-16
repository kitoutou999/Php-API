<?php

/* Inclusion des dépendances de cette classe */
require_once("model/Animal.php");
require_once("model/AnimalStorage.php");
require_once("model/AnimalStorageStub.php");

/**
 * Classe de test de l'application. Utilise un tableau
 * d'animaux qui est enregistré en session après chaque requête,
 * ce qui permet une pseudo-persistance des données.
 *
 * Ce n'est pas une vraie persistence :
 *  - chaque internaute voit sa propre version de la base de données
 *  - les modifications sont perdues à la fin de la session (ou si le
 *    cookie de session est supprimé).
 */
class AnimalStorageSession implements AnimalStorage {

	/** Le tableau d'animaux. */
	protected $db;

	private const SESSION_KEY = '__AnimalStorageSession_db';

	/**
	 * Construit une nouvelle instance.
	 * Si une base existe en session, elle est utilisée. Sinon,
	 * une nouvelle base est reconstruite en utilisant AnimalStorageStub.
	 */
	public function __construct() {
		/* Il faut avoir mis le session_start() avant de créer une instance. */
		if (session_status() !== PHP_SESSION_ACTIVE) {
			throw new Exception('Creating an instance of AnimalStorageSession requires an active session.');
		}
		if (key_exists(self::SESSION_KEY, $_SESSION)) {
			$this->db = $_SESSION[self::SESSION_KEY];
		} else {
			$this->db = (new AnimalStorageStub())->readAll();
		}
	}

	/** Sérialise et stocke la base avant de détruire l'instance. */
	public function __destruct() {
		$_SESSION[self::SESSION_KEY] = $this->db;
	}

	/** Génère un nouvel identifiant aléatoire qui n'existe pas
	 * encore dans la BD donnée en paramètre. */
	static private function generate_id($db) {
		do {
			/* implémentation simple avec un générateur de relativement
			 * bonne qualité ; mais les identifiants sont longs si
			 * on veut en avoir beaucoup. (avec 8 octets on en a seulement
			 * 10^20 environ -- c'est pas mal, mais pas gigantesque
			 * en termes de probabilité de collision lors de la génération) */
			$id = bin2hex(openssl_random_pseudo_bytes(8));

			/* on recommence le tirage si le premier caractère est un chiffre
			 * (pour éviter les problèmes d'interprétation de chaînes en
			 * nombres avec PHP) ou si l'identifiant est déjà utilisé */
		} while (is_numeric($id[0]) || key_exists($id, $db));

		return $id;
	}

	/**
	 * Initialise la base avec un tableau vide.
	 */
	public function init() {
		$this->db = array();
	}

	/** Implémentation de la méthode de AnimalStorage */
	public function read($id) {
		if (key_exists($id, $this->db)) {
			return $this->db[$id];
		} else {
			return null;
		}
	}

	/** Implémentation de la méthode de AnimalStorage */
	public function readAll() {
		return $this->db;
	}

	/** Implémentation de la méthode de AnimalStorage */
	public function create(Animal $a) {
		$id = self::generate_id($this->db);
		$this->db[$id] = $a;
		return $id;
	}

	/** Implémentation de la méthode de AnimalStorage */
	public function update($id, Animal $a) {
		if (array_key_exists($id, $this->db)) {
            $this->db[$id] = $a;
			return true;
		}
		return false;
	}

	/** Implémentation de la méthode de AnimalStorage */
	public function delete($id) {
		if (!array_key_exists($id, $this->db))
			return false;
		unset($this->db[$id]);
		return true;
	}

}

