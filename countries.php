<?php
/**
* @project		travelbook
* @author		Olivier Gaillard
* @version		1.0 du 12/02/2017
* @desc			Controleur des objets : countries
*/

require_once( "inc/prepend.php" );
//$user->isLoggedIn(); // Espace privé

// Récupération des variables
$action			= Utils::get_input('action','both');
$id				= Utils::get_input('id','both');
$page			= Utils::get_input('page','both');
$name			= Utils::get_input('name','post');

$country_manager = new CountryManager($bdd);

switch($action) {
	
	case "add" :
		$smarty->assign("country", new Country(array("id" => -1)));
		$smarty->assign("content", "countries/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;
	
	case "edit" :
		$smarty->assign("country", $country_manager->getCountry($id));
		$smarty->assign("content","countries/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "save" :
		$data = array("id" => $id, "name" => $name);
		$country_manager->saveCountry(new Country($data));
		$log->notification($translate->__('the_country_has_been_saved'));
		Utils::redirection("countries.php");
		break;

	case "delete" :
		$country = $country_manager->getCountry($id);
		if ($country_manager->deleteCountry($country)) {
			$log->notification($translate->__('the_country_has_been_deleted'));
		}
		Utils::redirection("countries.php");
		break;

	default:
		$smarty->assign("titre", $translate->__('list_of_countries'));
		$rpp = 10;
		if (empty($page)) $page = 1; // Display first page
		$smarty->assign("countries", $country_manager->getCountriesByPage($page, $rpp));
		$pagination = new Pagination($page, $country_manager->getMaxCountries(), $rpp);
		$smarty->assign("btn_nav", $pagination->getNavigation());

		$smarty->assign("content", "countries/list.tpl.html");
		$smarty->display("main.tpl.html");
}
require_once( "inc/append.php" );
?>