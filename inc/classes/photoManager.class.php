<?php
/**
* @project		travelbook
* @author		Olivier Gaillard
* @version		1.0 du 12/02/2017
* @desc			Gestion des photos
*/

class PhotoManager {
	protected $bdd;

	public function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/**
	* Retourne l'objet photo correspondant à l'Id
	* @param $id
	*/
	public function getPhoto($id) {
		$q = $this->bdd->prepare("SELECT * FROM photos WHERE id = :id");
		$q->bindValue(':id', $id, PDO::PARAM_INT);
		$q->execute();
		return new Photo($q->fetch(PDO::FETCH_ASSOC));
	}

	/**
	* Retourne la liste des photos
	*/
	public function getPhotos($offset = null, $count = null, $isEagerFetch = false) {
			$photos = array();
			if (isset($offset) && isset($count)) {
				$q = $this->bdd->prepare('SELECT * FROM photos ORDER BY shooting_date LIMIT :offset, :count');
				$q->bindValue(':offset', $offset, PDO::PARAM_INT);
				$q->bindValue(':count', $count, PDO::PARAM_INT);
				}
			else {
				$q = $this->bdd->prepare('SELECT * FROM photos ORDER BY shooting_date');
				}
			$q->execute();
			while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
				$photo = new Photo($data);
				if ($isEagerFetch) {
					$country_manager = new CountryManager($this->bdd);
					$photo->setCountry($country_manager->getCountry($photo->getCountryId()));
					}
				$photos[] = $photo;
			}
			return $photos;
		}

	/**
	 * Retourne la liste des photos par page
	 */

	public function getPhotosByPage($page_num, $count, $isEagerFetch = false) {
		return $this->getPhotos(($page_num-1)*$count, $count, $isEagerFetch);
	}

	/**
	* Retourne la liste des photos pour une date donnée
	* @param $date
	*/
	public function getPhotosForDate($date) {
		$photos = array();
		$q = $this->bdd->prepare('SELECT * FROM photos 
			WHERE state = 1 AND DATE(shooting_date) = :shooting_date ORDER BY shooting_date');
		$q->bindValue(':shooting_date', $date, PDO::PARAM_STR);
		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$photos[] = new Photo($data);
		}
		return $photos;
	}
	


	/**
	* Recherche les photos
	*/
	public function searchPhotos($query) {
		$photos = array();
		$q = $this->bdd->prepare('SELECT * FROM photos 
			WHERE name LIKE :query OR caption LIKE :query');
		$q->bindValue(':query', '%'.$query.'%', PDO::PARAM_STR);
		$q->execute();
		while ($data = $q->fetch(PDO::FETCH_ASSOC)) {
			$photo = new Photo($data);
			$country_manager = new CountryManager($this->bdd);
			$photo->setCountry($country_manager->getCountry($photo->getCountryId()));
			$photos[] = $photo;
		}
		return $photos;
	}




	/**
	 * Retourne le nombre max de places
	 */
	public function getMaxPhotos() {
		$q = $this->bdd->prepare('SELECT count(1) FROM photos');
		$q->execute();
		return intval($q->fetch(PDO::FETCH_COLUMN));
	}


	/**
	* Efface l'objet photo de la bdd
	* @param Photo $photo
	*/
	public function deletePhoto(Photo $photo) {
		try {	
			$q = $this->bdd->prepare("DELETE FROM photos WHERE id = :id");
			$q->bindValue(':id', $photo->getId(), PDO::PARAM_INT);
			return $q->execute();
			}
		catch( PDOException $Exception ) {
			return false;
		}
	}

	/**
	* Enregistre l'objet photo en bdd
	* @param Photo $photo
	*/
	public function savePhoto(Photo $photo) {
		if ($photo->getId() == -1) {
			$q = $this->bdd->prepare('INSERT INTO photos SET name = :name, directory = :directory, shooting_date = :shooting_date, country_id = :country_id, caption = :caption, state = :state');
		} else {
			$q = $this->bdd->prepare('UPDATE photos SET name = :name, directory = :directory, shooting_date = :shooting_date, country_id = :country_id, caption = :caption, state = :state WHERE id = :id');
			$q->bindValue(':id', $photo->getId(), PDO::PARAM_INT);
		}
		$q->bindValue(':name', $photo->getName(), PDO::PARAM_STR);
		$q->bindValue(':directory', $photo->getDirectory(), PDO::PARAM_STR);
		$q->bindValue(':shooting_date', $photo->getShootingDate(), PDO::PARAM_STR);
		$q->bindValue(':country_id', $photo->getCountryId(), PDO::PARAM_INT);
		$q->bindValue(':caption', $photo->getCaption(), PDO::PARAM_STR);
		$q->bindValue(':state', $photo->getState(), PDO::PARAM_INT);
		$q->execute();
		if ($photo->getId() == -1) $photo->setId($this->bdd->lastInsertId());
	}


	/* ----------- fonctions optionnelles ----------- */

	/**
	 * Retourne une liste des photos formatés pour peupler un menu déroulant
	 */
	/*public function getPhotosForSelect() {
		$photos = array();
		$q = $this->bdd->prepare('SELECT id, name FROM photos ORDER BY id');
		$q->execute();
		while ($row = $q->fetch(PDO::FETCH_ASSOC)) {
			$photos[$row["id"]] =  $row["name"];
		}
		return $photos;
	}*/

	/**
	 * Retourne la liste des photos par parent
	 */
	/*public function getPhotosByParent() {
		$photos = array();
		$q1 = $this->bdd->prepare('SELECT * FROM photos WHERE parent_id = 0');
		$q1->execute();
		while ($data = $q1->fetch(PDO::FETCH_ASSOC)) {
			$photo = new Photo($data);
			$photos[] = $photo;
			$q2 = $this->bdd->prepare('SELECT * FROM photos WHERE parent_id = :parent_id');
			$q2->bindValue(':parent_id', $photo->getId(), PDO::PARAM_INT);
			$q2->execute();
			while ($data = $q2->fetch(PDO::FETCH_ASSOC)) {
				$photos[] = new Photo($data);
			}
		}
		return  $photos;
	}
	*/

}
?>