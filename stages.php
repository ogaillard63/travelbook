<?php
/**
* @project		travelbook
* @author		Olivier Gaillard
* @version		1.0 du 12/02/2017
* @desc			Controleur des objets : stages
*/

require_once( "inc/prepend.php" );
//$user->isLoggedIn(); // Espace privé

// Récupération des variables
$action			= Utils::get_input('action','both');
$id				= Utils::get_input('id','both');
$page			= Utils::get_input('page','both');
$query			= Utils::get_input('query','post');
$travel_date	= Utils::date2Sql(Utils::get_input('travel_date','both'));
$country_id		= Utils::get_input('country_id','post');
$title			= Utils::get_input('title','post');
$gps			= Utils::get_input('gps','post');
$story			= Utils::get_input('story','post');
$distance		= Utils::get_input('distance','post');
$referer		= Utils::get_input('referer','post');

$stage_manager = new StageManager($bdd);
$country_manager = new CountryManager($bdd);

switch($action) {
	
	case "add" :
		$smarty->assign("stage", new Stage(array("id" => -1)));
		$smarty->assign("countries", $country_manager->getCountriesForSelect());
		$smarty->assign("content", "stages/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;
	
	case "edit" :
		$smarty->assign("stage", $stage_manager->getStage($id));
		$smarty->assign("countries", $country_manager->getCountriesForSelect());
		$smarty->assign("referer", basename($_SERVER['HTTP_REFERER']));
		$smarty->assign("content","stages/edit.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search" :
		$smarty->assign("content","stages/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "search_results" :
		if (strlen($query) > 2) {
			$smarty->assign("stages", $stage_manager->searchStages($query));
		}
		else {
			$log->notification($translate->__('query_too_short'));
			Utils::redirection("stages.php?action=search");
		}
		$smarty->assign("query",$query);
		$smarty->assign("content","stages/search.tpl.html");
		$smarty->display("main.tpl.html");
		break;

	case "save" :
		$data = array("id" => $id, "travel_date" => $travel_date, "country_id" => $country_id, "title" => $title, "gps" => $gps, "story" => $story, "distance" => $distance);
		$stage_manager->saveStage(new Stage($data));
		$log->notification($translate->__('the_stage_has_been_saved'));
		//Utils::redirection("stages.php");
		Utils::redirection($referer);
	break;

	case "delete" :
		$stage = $stage_manager->getStage($id);
		if ($stage_manager->deleteStage($stage)) {
			$log->notification($translate->__('the_stage_has_been_deleted'));
		}
		Utils::redirection("stages.php");
		break;

	default:
		$smarty->assign("titre", $translate->__('list_of_stages'));
		$rpp = 10;
		if (empty($page)) $page = 1; // Display first page
		$smarty->assign("stages", $stage_manager->getStagesByPage($page, $rpp, true));
		$pagination = new Pagination($page, $stage_manager->getMaxStages(), $rpp);
		$smarty->assign("btn_nav", $pagination->getNavigation());

		$smarty->assign("content", "stages/list.tpl.html");
		$smarty->display("main.tpl.html");
}
require_once( "inc/append.php" );
?>