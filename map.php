<?php
/**
 * @project		WebApp Generator
 * @author		Olivier Gaillard
 * @version		1.0 du 04/06/2012
 * @desc	   	Carte
 */


require_once( "inc/prepend.php" );


$smarty->assign("gpx_filepath", "gps_files/travel_book.gpx");
$smarty->assign("content", "misc/homepage.tpl.html");
$smarty->display("main.tpl.html");
?>