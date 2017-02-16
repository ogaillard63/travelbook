<?php
/**
* @project		travelbook
* @author		Olivier Gaillard
* @version		1.0 du 12/02/2017
* @desc			Objet photo
*/

class Photo {
	public $id;
	public $name;
	public $directory;
	public $shooting_date;
	public $country_id;
	public $country;
	public $caption;
	public $state;

	public function __construct(array $data) {
		$this->hydrate($data);
	}

	public function hydrate(array $data){
		foreach ($data as $key => $value) {
			if (strpos($key, "_") !== false) {
				$method = 'set';
				foreach (explode("_", $key) as $part) {
					$method .= ucfirst($part);
				}
			}
			else $method = 'set'.ucfirst($key);
			if (method_exists($this, $method)) {
				$this->$method($value);
			}
		}
	}

	/* --- Getters et Setters --- */

	// id
	public function setId($id) {
		$this->id = (integer)$id;
	}
	public function getId() {
		return $this->id;
	}
	// name
	public function setName($name) {
		$this->name = $name;
	}
	public function getName() {
		return $this->name;
	}
	// directory
	public function setDirectory($directory) {
		$this->directory = $directory;
	}
	public function getDirectory() {
		return $this->directory;
	}
	// shooting_date
	public function setShootingDate($shooting_date) {
		$this->shooting_date = $shooting_date;
	}
	public function getShootingDate() {
		return $this->shooting_date;
	}
	// country_id
	public function setCountryId($country_id) {
		$this->country_id = (integer)$country_id;
	}
	public function getCountryId() {
		return $this->country_id;
	}
	// country
	public function setCountry($country) {
		$this->country = $country;
	}
	public function getCountry() {
		return $this->country;
	}
	// caption
	public function setCaption($caption) {
		$this->caption = $caption;
	}
	public function getCaption() {
		return $this->caption;
	}
	// state
	public function setState($state) {
		$this->state = $state;
	}
	public function getState() {
		return $this->state;
	}
}
?>
