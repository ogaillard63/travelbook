<?php
/**
* @project		travelbook
* @author		Olivier Gaillard
* @version		1.0 du 12/02/2017
* @desc			Objet stage
*/

class Stage {
	public $id;
	public $travel_date;
	public $country_id;
	public $country;
	public $title;
	public $gps;
	public $story;
	public $distance;

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
	// travel_date
	public function setTravelDate($travel_date) {
		$this->travel_date = $travel_date;
	}
	public function getTravelDate() {
		return $this->travel_date;
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
	// title
	public function setTitle($title) {
		$this->title = $title;
	}
	public function getTitle() {
		return $this->title;
	}
	// gps
	public function setGps($gps) {
		$this->gps = $gps;
	}
	public function getGps() {
		return $this->gps;
	}
	// story
	public function setStory($story) {
		$this->story = $story;
	}
	public function getStory() {
		return $this->story;
	}
	// distance
	public function setDistance($distance) {
		$this->distance = (integer)$distance;
	}
	public function getDistance() {
		return $this->distance;
	}
}
?>
