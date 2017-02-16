<?php
/**
* @project		travelbook
* @author		Olivier Gaillard
* @version		1.0 du 12/02/2017
* @desc			Gestion des stages
*/

class StageManager {
	protected $bdd;

	public function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	
	/**
	* Retourne l'objet stage correspondant à l'Id
	* @param $id
	*/
	public function getStage($id) {
		$q = $this->bdd->prepare("SELECT * FROM stages WHERE id = :id");
		$q->bindValue(':id', $id, PDO::PARAM_INT);
		$q->execute();
		return new Stage($q->fetch(PDO::FETCH_ASSOC));
	}
	
	/**
	* Retourne l'objet stage pour la date donnée
	* @param $date
	*/
	public function getStageForDate($date) {
		$q = $this->bdd->prepare("SELECT * FROM stages WHERE travel_date = :travel_date");
		$q->bindValue(':travel_date', $date, PDO::PARAM_STR);
		$q->execute();
		return new Stage($q->fetch(PDO::FETCH_ASSOC));
	}

	/**
	* Retourne la liste des stages
	*/
	public function getStages($offset = null, $count = null, $isEagerFetch = false) {
			$stages = array();
			if (isset($offset) && isset($count)) {
				$q = $this->bdd->prepare('SELECT * FROM stages ORDER BY travel_date LIMIT :offset, :count');
				$q->bindValue(':offset', $offset, PDO::PARAM_INT);
				$q->bindValue(':count', $count, PDO::PARAM_INT);
				}
			else {
				$q = $this->bdd->prepare('SELECT * FROM stages ORDER BY travel_date');
				}
			$q->execute();
			while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
				$stage = new Stage($data);
				if ($isEagerFetch) {
					$country_manager = new CountryManager($this->bdd);
					$stage->setCountry($country_manager->getCountry($stage->getCountryId()));
					}
				$stages[] = $stage;
			}
			return $stages;
		}

	/**
	 * Retourne la liste des stages par page
	 */
	public function getStagesByPage($page_num, $count, $isEagerFetch = false) {
		return $this->getStages(($page_num-1)*$count, $count, $isEagerFetch);
	}


	/**
	* Recherche les stages
	*/
	public function searchStages($query) {
		$stages = array();
		$q = $this->bdd->prepare('SELECT * FROM stages 
			WHERE title LIKE :query OR story LIKE :query');
		$q->bindValue(':query', '%'.$query.'%', PDO::PARAM_STR);
		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$stage = new Stage($data);
			$country_manager = new CountryManager($this->bdd);
			$stage->setCountry($country_manager->getCountry($stage->getCountryId()));
			$stages[] = $stage;
		}
		return $stages;
	}




	/**
	 * Retourne le nombre max de places
	 */
	public function getMaxStages() {
		$q = $this->bdd->prepare('SELECT count(1) FROM stages');
		$q->execute();
		return intval($q->fetch(PDO::FETCH_COLUMN));
	}


	/**
	* Efface l'objet stage de la bdd
	* @param Stage $stage
	*/
	public function deleteStage(Stage $stage) {
		try {	
			$q = $this->bdd->prepare("DELETE FROM stages WHERE id = :id");
			$q->bindValue(':id', $stage->getId(), PDO::PARAM_INT);
			return $q->execute();
			}
		catch( PDOException $Exception ) {
			return false;
		}
	}

	/**
	* Enregistre l'objet stage en bdd
	* @param Stage $stage
	*/
	public function saveStage(Stage $stage) {
		if ($stage->getId() == -1) {
			$q = $this->bdd->prepare('INSERT INTO stages SET travel_date = :travel_date, country_id = :country_id, title = :title, gps = :gps, story = :story, distance = :distance');
		} else {
			$q = $this->bdd->prepare('UPDATE stages SET travel_date = :travel_date, country_id = :country_id, title = :title, gps = :gps, story = :story, distance = :distance WHERE id = :id');
			$q->bindValue(':id', $stage->getId(), PDO::PARAM_INT);
		}
		$q->bindValue(':travel_date', $stage->getTravelDate(), PDO::PARAM_STR);
		$q->bindValue(':country_id', $stage->getCountryId(), PDO::PARAM_INT);
		$q->bindValue(':title', $stage->getTitle(), PDO::PARAM_STR);
		$q->bindValue(':gps', $stage->getGps(), PDO::PARAM_STR);
		$q->bindValue(':story', $stage->getStory(), PDO::PARAM_STR);
		$q->bindValue(':distance', $stage->getDistance(), PDO::PARAM_INT);
		$q->execute();
		if ($stage->getId() == -1) $stage->setId($this->bdd->lastInsertId());
	}


	/* ----------- fonctions optionnelles ----------- */


	public function getFirstDateStage() {
		$q = $this->bdd->prepare('SELECT travel_date FROM stages ORDER BY travel_date');
		$q->execute();
		return $q->fetch(PDO::FETCH_COLUMN);
	}

	public function getNextDateStage($travel_date) {
		$q = $this->bdd->prepare('SELECT travel_date FROM stages WHERE travel_date > :travel_date ORDER BY travel_date');
		$q->bindValue(':travel_date', $travel_date, PDO::PARAM_STR);
		$q->execute();
		return $q->fetch(PDO::FETCH_COLUMN);
	}	

	public function getPrevDateStage($travel_date) {
		$q = $this->bdd->prepare('SELECT travel_date FROM stages WHERE travel_date < :travel_date ORDER BY travel_date DESC');
		$q->bindValue(':travel_date', $travel_date, PDO::PARAM_STR);
		$q->execute();
		return $q->fetch(PDO::FETCH_COLUMN);
		}


	/**
	 * Retourne une liste des stages formatés pour peupler un menu déroulant
	 */
	/*public function getStagesForSelect() {
		$stages = array();
		$q = $this->bdd->prepare('SELECT id, name FROM stages ORDER BY id');
		$q->execute();
		while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
			$stages[$row["id"]] =  $row["name"];
		}
		return $stages;
	}*/

	/**
	 * Retourne la liste des stages par parent
	 */
	/*public function getStagesByParent() {
		$stages = array();
		$q1 = $this->bdd->prepare('SELECT * FROM stages WHERE parent_id = 0');
		$q1->execute();
		while ($data = $q1->fetch(PDO::FETCH_ASSOC)) {
			$stage = new Stage($data);
			$stages[] = $stage;
			$q2 = $this->bdd->prepare('SELECT * FROM stages WHERE parent_id = :parent_id');
			$q2->bindValue(':parent_id', $stage->getId(), PDO::PARAM_INT);
			$q2->execute();
			while ($data = $q2->fetch(PDO::FETCH_ASSOC)) {
				$stages[] = new Stage($data);
			}
		}
		return  $stages;
	}
	*/

}
?>