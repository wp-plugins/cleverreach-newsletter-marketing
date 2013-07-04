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




 $form_data = "";
	if($forms){
					$form_data =	'<select name="gbc_form_id" id="gbc_form_id" style="width:150px;">';
								foreach($forms as $form){ 
								$selected = "";
								if($cr_options["gbc_form_id"]==$form->id) $selected =("selected='selected'");
						$form_data .= '<option value="'. ($form->id) .'" '.$selected.'>'. $form->name .'</option>';
						}	
						$form_data .= '</select>';
							}else{ 
						$form_data = "<span style='font-weight:bold; color:#cc2222'>Sie haben noch keine Formulare die Sie verwenden k&ouml;nnten. </span>";
						 	} 
					$form_data .= '<br /><a href="http://'. $cr_options["domain"] .'/admin/forms_list.php" target="_blank">Neues Formular anlegen oder bestehende Formulare bearbeiten</a>';	
	
		
		echo '<br /><table class="form-table">
			
		
			<tr>
				<td colspan="2" >
				<h2>Abonnentengewinnung &uuml;ber Kommentare</h2>
				Durch die Aktivierung dieser Funktion wird jedem Kommentarformular in Ihrem Blog eine Newsletter-Bestellm&ouml;glichkeit hinzugef&uuml;gt.<br /> 
				Der User muss beim Kommentieren nur ein H&auml;kchen setzen und erh&auml;lt eine Best&auml;tigungsmail (Double-Opt-In). <br />
				Nach der Best&auml;tigung ist er als aktiver Abonnent in einer Empf&auml;ngergruppe des von Ihnen gew&auml;hlten Formulars eingetragen.*
				</td>
			</tr>
			<tr>
				<th scope="row" colspan="2" >Abonenntengewinnung &uuml;ber Kommentare aktivieren: 
				<input type="checkbox" name="gbc" id="gbc" value="checked" '. ($cr_options["gbc"]) .'></th>
			</tr>
			<tr>
				<th scope="row" >Beschreibungstext</th>
				<td><input style="width:400px" type="text" name="gbc_text" value="'. ($cr_options["gbc_text"]) .'"></td>
			</tr>
			<tr>
				<th scope="row" >Formular</th>
				<td>
					'. $form_data . '
					
				
				</td>
			</tr>

		</table>
		<br /><input type="submit" style="height:36px; padding-left:10px; padding-right:10px; cursor:pointer; font-size:16px; background-color:#E05F09; color:#fff; border:1px solid black" value="Einstellungen speichern" />
	<br />

		<p>*Wird das Anmeldeh&auml;ckchen nicht automatisch in das Kommentarformular eingef&uuml;gt, dann platzieren Sie bitte den folgenden Code innerhalb des "&lt;FORM&gt;" Tags der Datei "comments.php" Ihres aktiven Themes: <i>&lt;?php cr_add_checkbox();?&gt;</i>
		</p>
		 <input type="hidden" name="config_save" value="1" />';
        ?>