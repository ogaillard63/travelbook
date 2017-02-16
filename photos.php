<?php
/**
* @project		travelbook
* @author		Olivier Gaillard
* @version		1.0 du 12/02/2017
* @desc			Controleur des objets : photos
*/

require_once( "inc/prepend.php" );
//$user->isLoggedIn(); // Espace privé

// Récupération des variables
$action			= Utils::get_input('action','both');
$id				= Utils::get_input('id','both');
$page			= Utils::get_input('page','both');
$query			= Utils::get_input('query','post');
$name			= Utils::get_input('name','post');
$directory		= Utils::get_input('directory','post');
$shooting_date	= Utils::get_input('shooting_date','post');
$country_id		= Utils::get_input('country_id','post');
$caption		= Utils::get_input('caption','post');
$state			= Utils::get_input('state','post');
$referer		= Utils::get_input('referer','post');

$photo_manager = new PhotoManager($bdd);
$country_manager = new CountryManager($bdd);

switch($action) {
	
	case "add" :
		$smarty->assign("photo", new Photo(array("id" => -1)));
		$smarty->assign("countries", $country_manager->getCountriesForSelect());
		$smarty->assign("referer", basename($_SERVER['HTTP_REFERER']));
		$smarty->assign("content", "photos/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;
	
	case "edit" :
		$smarty->assign("photo", $photo_manager->getPhoto($id));
		$smarty->assign("countries", $country_manager->getCountriesForSelect());
		$smarty->assign("referer", basename($_SERVER['HTTP_REFERER']));
		$smarty->assign("content","photos/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;
	
	case "search" :
		$smarty->assign("content","photos/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search_results" :
		if (strlen($query) > 2) {
			$smarty->assign("photos", $photo_manager->searchPhotos($query));
		}
		else {
			$log->notification($translate->__('query_too_short'));
			Utils::redirection("photos.php?action=search");
		}
		$smarty->assign("query",$query);
		$smarty->assign("content","photos/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "save" :
		$data = array("id" => $id, "name" => $name, "directory" => $directory, 
			"shooting_date" => $shooting_date, "country_id" => $country_id, "caption" => $caption, "state" => $state);
		$photo_manager->savePhoto(new Photo($data));
		$log->notification($translate->__('the_photo_has_been_saved'));
		Utils::redirection($referer);
		break;

	case "delete" :
		$photo = $photo_manager->getPhoto($id);
		if ($photo_manager->deletePhoto($photo)) {
			$log->notification($translate->__('the_photo_has_been_deleted'));
		}
		Utils::redirection("photos.php");
		break;

	default:
		$smarty->assign("titre", $translate->__('list_of_photos'));
		$rpp = 10;
		if (empty($page)) $page = 1; // Display first page
		$smarty->assign("photos", $photo_manager->getPhotosByPage($page, $rpp, true));
		$pagination = new Pagination($page, $photo_manager->getMaxPhotos(), $rpp);
		$smarty->assign("btn_nav", $pagination->getNavigation());

		$smarty->assign("content", "photos/list.tpl.html");
		$smarty->display("main.tpl.html");
}
require_once( "inc/append.php" );
?>