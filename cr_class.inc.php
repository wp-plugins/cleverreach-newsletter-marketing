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



	
	
class cleverreach_api {
	var $api_client;
	var $client;
	
	var $api_key		= false;
	var $list_id		= false;

	function cleverreach_api($api_key = false, $list_id = false){
		$cr_options = get_option("cr_options");

		$this->api_client = new SoapClient(null, array("location" => "http://api.cleverreach.com/soap/interface_v5.1.php?wsdl", "uri" => "urn:".get_option('siteurl')));
		if($api_key){
			$this->api_key = trim($api_key);
		}else{
			$this->api_key = $cr_options["api_key"];
		}
		if($list_id){
			$this->list_id = trim($list_id);
		}else{
			$this->list_id = $cr_options["list_id"];
		}
		
	}
	
	function login($api_key = false){
		if($api_key){
			$this->api_key = $api_key; 
		}
		$this->client = @$this->api_client->clientGetDetails($this->api_key);

		if($this->client->status=="SUCCESS"){
			return true;
		}else
			return false;

	}
	
	function get_domain(){
		return $this->client->data->login_domain;
	}
	
	function get_forms(){
		$ret = @$this->api_client->formsGetList($this->api_key, $this->list_id);
		return $ret->data;
	}
	
function get_list(){

		$ret = @$this->api_client->groupGetList($this->api_key);

		return $ret->data;

	}
}

?>