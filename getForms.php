<?php


###############################################################
#	CleverReach API
#
#	Author: Inter Medien Networks www.inter-medien.com
#	
#	Date: 25.06.13
#	
#	Redistribution is NOT permitted	
#	
###############################################################



require($_SERVER['DOCUMENT_ROOT'] .'/wp-load.php');


	$ca = new cleverreach_api($_GET["api_key"], $_GET["list_id"]);
	$forms = $ca->get_forms();
	

	 include("form.inc.php");


?>
