<?php
/**
 * @project		WebApp Generator
 * @author		Olivier Gaillard
 * @version		1.0 du 04/06/2012
 * @desc	   	Carte
 */


require_once( "inc/prepend.php" );


$smarty->assign("content", "misc/homepage.tpl.html");
$smarty->display("main.tpl.html");
?>