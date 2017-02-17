<?php
/**
 * @project		WebApp Generator
 * @author		Olivier Gaillard
 * @version		1.0 du 04/06/2012
 * @desc	   	Accueil
 */


require_once( "inc/prepend.php" );
$date			= Utils::get_input('date','both');

$stage_manager = new StageManager($bdd);
$photo_manager = new PhotoManager($bdd);

if(empty($date)) $date = $stage_manager->getFirstDateStage();

$smarty->assign("next_date", $stage_manager->getNextDateStage($date));
$smarty->assign("prev_date", $stage_manager->getPrevDateStage($date));
$smarty->assign("stage", $stage_manager->getStageForDate($date));
$smarty->assign("photos", $photo_manager->getPhotosForDate($date));

$smarty->assign("content", "misc/stories.tpl.html");
$smarty->display("main.tpl.html");
?>