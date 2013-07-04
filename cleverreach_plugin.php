<?php
/*
Plugin Name: CleverReach
Plugin URI: http://www.cleverreach.de/
Description: Widget f&uuml;r die Integration eines CleverReach Anmeldeformulars in der Sidebar und Plugin für die Abonnentengewinnung über Kommentare. PHP5 und Soap Extension werden vorausgesetzt.
Version: 2.0
Author: Inter Medien Networks
Author URI: http://www.inter-medien.com
Update Server: http://www.cleverreach.de/docs/de
Min WP Version: 2.5
*/

$cr_plugin_url = get_option('siteurl').'/wp-content/plugins/cr_plugin/';
$cr_source_name = "WordPress comment";
include("cr_class.inc.php");
/* ----------- Administration part ------------ */


function cr_menu() {
  add_options_page('CleverReach Options', 'CleverReach', 8, __FILE__, 'cr_options');
}

function cr_options() {
	global $cr_plugin_url, $cr_prefix, $api;
	$cr_options = get_option("cr_options");

	if(!$cr_options["gbc_text"])
		$cr_options["gbc_text"] = "Newsletter abonnieren (Jederzeit wieder abbestellbar)";
	
	if($_POST["key_save"]){
		$cr_options["api_key"] 	=  $_POST['api_key'];
	
		$ca = new cleverreach_api($cr_options["api_key"]);
		$cr_login_ok = $ca->login();
			if($cr_login_ok){
				$domain = $ca->get_domain();
				$cr_options["domain"] = $domain;

				update_option("cr_options", $cr_options);
			} 
		if($_POST["config_save"]){
		$cr_options["list_id"] 		=  $_POST['list_id'];
		
		if($_POST['gbc_form_id']){
			$cr_options["gbc"] 			=  $_POST['gbc'];
			$cr_options["gbc_text"]		=  $_POST['gbc_text'];
			$cr_options["gbc_form_id"]	=  $_POST['gbc_form_id'];
		}				
		
		
		update_option("cr_options", $cr_options);
		}
		
	}
	$ca = new cleverreach_api($cr_options["api_key"], $cr_options["list_id"]);
	$cr_login_ok = $ca->login();
	if($cr_login_ok){
		$domain = $ca->get_domain();
		$cr_options["domain"] = $domain;

		update_option("cr_options", $cr_options);
		$list = $ca->get_list();
		if($list){
			$list_id_ok = true;
			$forms = $ca->get_forms();
			if($forms){
				$forms_ok = true;
			}
		}else{
			$cr_login_ok = false;
		}
		
		$api_key_ok = true;
	}
	
	?>
	<script type="text/javascript" src="<?php _e($cr_plugin_url) ?>/js/suggest.js"></script>
    
	<div class="wrap"><br />
	<img src="<?php _e($cr_plugin_url) ?>/images/logo.png" />
	<h3>Grundlegende Einstellungen</h3>
	<p>Bitte hinterlegen Sie hier Ihren CleverReach-API-Key. <br />Sie haben noch keinen CleverReach-Account? <a href="http://www.cleverreach.de/frontend/?rk=23978ytdeudol" target="_blank">Melden Sie sich jetzt kostenlos an!</a></p>
	</div>
    <br />

	<form id="cr_form" name="cr_form" enctype="application/x-www-form-urlencoded"  method="post" action="<?php str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<table width="100%" class="form-table">
			<tr>
				<td style="width:100px;"><span style="font-size:24px">API Key</span></td>
				<td style="width:440px;" valign="top"><input type="text" name="api_key" value="<?php _e($cr_options["api_key"]); ?>" style="width:440px; height:40px; font-size:24px;"></td>

			    <td valign="top"><?php if($api_key_ok){ ?>
                  <img src="<?php _e($cr_plugin_url); ?>/images/ok.png" />
                  <?php } else { ?>
                  <input type="submit" style="height:40px; padding-left:10px; padding-right:10px; cursor:pointer; font-size:16px; background-color:#FFF; border:1px solid black" value="Speichern" />
                <? } ?></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			  <td colspan="2" valign="top"><?php if(!$api_key_ok){ ?><? if($cr_options["api_key"] != "") { ?><span style='font-weight:bold; color:#cc2222'>API Key nicht korrekt.</span><br /><? } ?>
              <small>Den API Key finden Sie in Ihrem CleverReach Kundencenter unter Account-&gt;API</small><? } ?></td>
	      </tr>
		</table>
        <input type="hidden" name="key_save" value="1" />
        
        
        <?php if($cr_login_ok){ ?>
       
        <table width="100%" class="form-table">
			<tr>
				<td width="100"><span style="font-size:24px">Gruppe</span></td>
				<td>
                
                <?php	
				
				$lists = $ca->get_list();
				
				if($lists){	?>

			<select name="list_id" id="list_id" style="width:300px;" onchange="loadForms()">

			<option value="0" onclick="document.getElementById('list_div').style.display = 'none';" <?php if(!$cr_options["list_id"]) _e("selected"); ?>>Bitte wählen Sie die Gruppe...</option>
            
			<?php		foreach($lists as $list){ ?>
            
            
			<option value="<?php _e($list->id) ?>" <?php if($cr_options["list_id"]==$list->id) _e("selected"); ?>><?php _e($list->name) ?></option>

			<?php 		}	?>

			</select>

			<?php	}else{ 	?>

			<p><span style='font-weight:bold; color:#cc2222'>Sie haben noch keine Gruppen die Sie verwenden k&ouml;nnten. </span></p>

			<?php 	} ?> 

		&nbsp;<a href="http://<?php _e($cr_options["domain"]) ?>/admin/customer_groups.php" target="_blank">Neue Gruppe anlegen</a>

	

        
                </td>

			</tr>
			
		</table>
        
           <div id="list_div"><?php if($cr_options["gbc_form_id"]) { 
		  include("form.inc.php");	
	} ?>
           </div>
        <? } ?>
        



       

        
        

       
          
</form>
 <?php }  

 
function cr_add_checkbox(){
	$cr_options = get_option("cr_options");
	echo "<input type='checkbox' name='cr_gbc_form_id' id='cr_gbc_form_id' value='".$cr_options["gbc_form_id"]."' style='width:20px' />".$cr_options["gbc_text"];
}

function cr_gbc_subscribe(){

	global $_POST, $user_email, $cr_source_name, $user_login;
	if($_POST["cr_gbc_form_id"]){
		
		$cr_options = get_option("cr_options");
		$form_id = $_POST["cr_gbc_form_id"];
		$ca = new cleverreach_api($cr_options["api_key"], $cr_options["list_id"]);
		
		if(!$name = $_POST["author"])
			$name = $user_login;
		if(! $email = $_POST["email"])
			$email = $user_email;
		
		$cr_receiver = array (
			'email' => $email,
			'registered' => time(),
			'source' => 'Wordpress',
			'attributes' => array(
				array(
					'key' => 'firstname',
					'value' => $name
				)
			)
		);
		
		$result = @$ca->api_client->receiverAdd($cr_options['api_key'], $cr_options['list_id'], $cr_receiver);
		
		if($result->status == "SUCCESS"){
		
			$result = @$ca->api_client->formsActivationMail($cr_options['api_key'], $form_id, $email);
		}else{
			unset($cr_receiver["registered"]);
			$result = @$ca->api_client->receiverUpdate($cr_options['api_key'], $cr_options['list_id'], $cr_receiver);
		}
	}
}


/* ------------------ Widget part ---------------- */
function cr_widget_init() 
{
  
  
  if ( !function_exists('register_sidebar_widget') )
    return;
    
    function cr_widget() {
		$form = get_option("cr_form");
    	echo $form;
	}

	function cr_widget_options() {
		$cr_options = get_option("cr_options");
		$ca = new cleverreach_api($cr_options["api_key"], $cr_options["list_id"]);

		if(!$cr_options['widget_init']){
			$cr_options['widget_title']="Newsletter Anmeldung";
			$cr_options['widget_text']="Tragen Sie sich hier in unseren Newsletter ein";
			$cr_options['widget_show_powered']="checked";
			$cr_options['widget_submit_text']="Eintragen";
			$cr_options['widget_init']=$_POST["cr_widget_submit"];
			$cr_options['widget_form_id']='none';
		}
		
		if ($_POST['cr_widget_submit']) {
			$cr_options['widget_title']=strip_tags($_POST["cr_widget_title"]);
			$cr_options['widget_text']=$_POST["cr_widget_text"];
			$cr_options['widget_show_powered']=$_POST["cr_widget_show_powered"];
			$cr_options['widget_form_id']=$_POST["cr_widget_form_id"];
			$cr_options['widget_submit_text']=$_POST["cr_submit_text"];
			$cr_options['widget_formcode']=stripslashes($_POST["cr_widget_formcode"]);
			$cr_options['widget_border']=stripslashes($_POST["cr_widget_border"]);
			$cr_options['widget_init']=$_POST["cr_widget_submit"];
			
			update_option('cr_options', $cr_options);
			$cr_border = "";
			if($cr_options["widget_border"])
				$cr_border=" style='background:#fafafa; border:1px solid #ccc; padding: 3px;'";

			$ca = new cleverreach_api($cr_options["api_key"], $cr_options["list_id"]);
			 
			if($cr_options['widget_show_powered'] == "checked") $badget = true;
			if($cr_options['widget_ssl'] == "checked") $ssl = true;
			
			$echo = $ca->api_client->formsGetCode($cr_options['api_key'], $cr_options['widget_form_id'], $ssl, $badget);
			$form = "";
			if($echo->status == "SUCCESS"){
				$form = "<li id='cleverreach-form' class='widget widget_cr'".$cr_border.">
		    	<h2 class='widgettitle'>".$cr_options["widget_title"]."</h2>".$echo->data."\n</li>\n";
			}
			update_option("cr_form", $form);
			
		}
		$cr_display = "none";
		?>
		<label for="cr_widget_title"><strong><?php _e('&Uuml;berschrift:'); ?></strong></label>
		<input style="width: 100%; margin-bottom:1em;" id="cr_widget_title" name="cr_widget_title" type="text" value="<?php _e(htmlspecialchars(stripslashes($cr_options['widget_title']))); ?>" />

		<label for="cr_widget_border"><strong><?php _e('Rahmen anzeigen?'); ?></strong></label>
		<input type="checkbox" id="cr_widget_border" value="checked" name="cr_widget_border" <?php _e($cr_options['widget_border']) ?> />
		<br/>
		<br/>
		
		<?php if(!$ca->login()){ $cr_display = "block"?>
			<span style="color:#ff2222;">
			<b>Status: </b>Plugin nicht konfiguriert<br>  
			<a href="options-general.php?page=cr_plugin/cleverreach_plugin.php">CleverReach Plugin konfigurieren</a>
			</span>
			<hr />
		<?php }else{ $forms = $ca->get_forms();?>
		
		<label for="cr_widget_form_id"><strong><?php _e('CleverReach Formular:'); ?></strong></label>
		<script>
			function cr_switchl(val){
				var l = document.getElementById('cr_formcode_layer');
				if(val==""){
					l.style.display='block';
				}else{
					l.style.display='none';
				}
			}
		</script>
		
			<?php	if($forms){	?>
			<select name="cr_widget_form_id" id="cr_widget_form_id" style="width: 100%;" onchange="cr_switchl(this.value);">
			<?php		foreach($forms as $form){ ?>
			<option value="<?php _e($form->id) ?>" <?php if($cr_options["widget_form_id"]==$form->id) _e("selected"); ?>><?php _e($form->name) ?></option>
			<?php 		}	?>
			</select>
			<?php	}else{ 	?>
			<p><span style='font-weight:bold; color:#cc2222'>Sie haben noch keine Formulare die Sie verwenden k&ouml;nnten. </span></p>
			<?php 	} ?> 
		<a href="http://<?php _e($cr_options["domain"]) ?>/admin/forms_list.php" target="_blank">Neues Formular anlegen</a>
		<br /><br />
		<?php } ?>

		<label for="cr_widget_show_powered"><strong><?php _e('CleverReach Banner anzeigen?'); ?></strong></label>
		<input type="checkbox" id="cr_widget_show_powered" value="checked" name="cr_widget_show_powered" <?php _e($cr_options['widget_show_powered']) ?> />
		<br /><br />
		<input type="hidden" name="cr_widget_submit" value="true">
	<?php
	}
	if(class_exists("SoapClient")){
		register_sidebar_widget(array('CleverReach', 'CleverReach'), 'cr_widget');
		register_widget_control(array('CleverReach', 'CleverReach'), 'cr_widget_options');
	}
}


/* ------------------ hooks ---------------- */
add_action('widgets_init', 'cr_widget_init');
add_action('admin_menu', 'cr_menu');

$cr_options = get_option("cr_options");

if($cr_options["gbc"] && $cr_options["gbc_form_id"]){
	// This will be overridden if the user manually places the function
	// in the comments form before the comment_form do_action() call
	add_action('comment_form', 'cr_add_checkbox');

	add_action('comment_post', "cr_gbc_subscribe", 50);
	//add_action('comment_post', create_function('$a', 'global $sg_subscribe; sg_subscribe_start(); return $sg_subscribe->add_subscriber($a);'));
}


?>
