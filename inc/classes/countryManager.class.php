<?php
/**
* @project		travelbook
* @author		Olivier Gaillard
* @version		1.0 du 12/02/2017
* @desc			Gestion des countries
*/

class CountryManager {
	protected $bdd;

	public function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/**
	* Retourne l'objet country correspondant à l'Id
	* @param $id
	*/
	public function getCountry($id) {
		$q = $this->bdd->prepare("SELECT * FROM countries WHERE id = :id");
		$q->bindValue(':id', $id, PDO::PARAM_INT);
		$q->execute();
		return new Country($q->fetch(PDO::FETCH_ASSOC));
	}

	/**
	* Retourne la liste des countries
	*/
	public function getCountries($offset = null, $count = null) {
		$countries = array();
		if (isset($offset) && isset($count)) {
			$q = $this->bdd->prepare('SELECT * FROM countries ORDER BY id DESC LIMIT :offset, :count');
			$q->bindValue(':offset', $offset, PDO::PARAM_INT);
			$q->bindValue(':count', $count, PDO::PARAM_INT);
		}
		else {
			$q = $this->bdd->prepare('SELECT * FROM countries ORDER BY id');
		}

		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$countries[] = new Country($data);
		}
		return $countries;
	}

	/**
	* Recherche les countries
	*/
	public function searchCountries($query) {
		$countries = array();
		$q = $this->bdd->prepare('SELECT * FROM countries 
			WHERE name LIKE :query');
		$q->bindValue(':query', '%'.$query.'%', PDO::PARAM_STR);
		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$country = new Country($data);
			$_manager = new Manager($this->bdd);
			$country->set($_manager->get($country->getId()));
			$countries[] = $country;
		}
		return $countries;
	}

	/**
	 * Retourne la liste des countries par page
	 */
	 public function getCountriesByPage($page_num, $count) {
		return $this->getCountries(($page_num-1)*$count, $count);
	 }

	/* public function getCountriesByPage($page_num, $count, $isEagerFetch = false) {
		return $this->getCountries(($page_num-1)*$count, $count, $isEagerFetch);
	} */


	/**
	 * Retourne le nombre max de places
	 */
	public function getMaxCountries() {
		$q = $this->bdd->prepare('SELECT count(1) FROM countries');
		$q->execute();
		return intval($q->fetch(PDO::FETCH_COLUMN));
	}


	/**
	* Efface l'objet country de la bdd
	* @param Country $country
	*/
	public function deleteCountry(Country $country) {
		try {	
			$q = $this->bdd->prepare("DELETE FROM countries WHERE id = :id");
			$q->bindValue(':id', $country->getId(), PDO::PARAM_INT);
			return $q->execute();
			}
		catch( PDOException $Exception ) {
			return false;
		}
	}

	/**
	* Enregistre l'objet country en bdd
	* @param Country $country
	*/
	public function saveCountry(Country $country) {
		if ($country->getId() == -1) {
			$q = $this->bdd->prepare('INSERT INTO countries SET name = :name');
		} else {
			$q = $this->bdd->prepare('UPDATE countries SET name = :name WHERE id = :id');
			$q->bindValue(':id', $country->getId(), PDO::PARAM_INT);
		}
		$q->bindValue(':name', $country->getName(), PDO::PARAM_STR);
		$q->execute();
		if ($country->getId() == -1) $country->setId($this->bdd->lastInsertId());
	}


	/* ----------- fonctions optionnelles ----------- */

	/**
	 * Retourne une liste des countries formatés pour peupler un menu déroulant
	 */
	public function getCountriesForSelect() {
		$countries = array();
		$q = $this->bdd->prepare('SELECT id, name FROM countries ORDER BY id');
		$q->execute();
		while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
			$countries[$row["id"]] =  $row["name"];
		}
		return $countries;
	}

	/**
	 * Retourne la liste des countries par parent
	 */
	/*public function getCountriesByParent() {
		$countries = array();
		$q1 = $this->bdd->prepare('SELECT * FROM countries WHERE parent_id = 0');
		$q1->execute();
		while ($data = $q1->fetch(PDO::FETCH_ASSOC)) {
			$country = new Country($data);
			$countries[] = $country;
			$q2 = $this->bdd->prepare('SELECT * FROM countries WHERE parent_id = :parent_id');
			$q2->bindValue(':parent_id', $country->getId(), PDO::PARAM_INT);
			$q2->execute();
			while ($data = $q2->fetch(PDO::FETCH_ASSOC)) {
				$countries[] = new Country($data);
			}
		}
		return  $countries;
	}
	*/

}
?>