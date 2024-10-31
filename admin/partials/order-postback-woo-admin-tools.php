<?php 
settings_errors(); 
 $links_obj = new PostbackRegLinks_List();
 $sel_method_post = "";
 $sel_method_get = "";
 $sel_fire_yes = "";
 $sel_fire_no = "";
 $record_count = $links_obj->record_count();


 
 if(isset($_GET['link']) && (int)$_GET['link']>0){
 	  $link_id = (int)$_GET['link'];
 		$opw = $links_obj->get_link($link_id);
 		
 		if(isset($opw['opw_method']) && $opw['opw_method']=='post'){
 		  $sel_method_post = "selected='selected'";	
 		}
 		
 		if(isset($opw['opw_method']) && $opw['opw_method']=='get'){
 		  $sel_method_get = "selected='selected'";	
 		}
		 
		 if(isset($opw['opw_fire']) && $opw['opw_fire']=='yes'){
 		   $sel_fire_yes = "selected='selected'";	
 		}
 		
 		if(isset($opw['opw_fire']) && $opw['opw_fire']=='no'){
 		   $sel_fire_no = "selected='selected'";	
 		}
 	 	
 }else{
 		$opw = array();
 		$link_id = 0;
 }
 
 if($record_count==0 || ($link_id)){
 ?>
 
<form id="create-link-form" method="post" novalidate="novalidate">
	<input type="hidden" name="tab" value="tools"/>
	<?php if($link_id > 0):?>
	<input type="hidden" name="opw_link_id" id="opw_link_id" value="<?php echo $link_id?>"/>
	<input type="hidden" value="<?php echo "[".$opw['opw_key_values_number_old']."]";?>" id="opw_key_values_number_old" name="opw_key_values_number_old"/>
	<?php endif;?>
	<table class="form-table" role="presentation">
		<tr><td>Name</td><td><input type="text" class="widefat" name="opw_name" id="opw_name" value="<?php echo $opw['opw_name'];?>"></td></tr>
		<tr><td>Url to Post To</td><td><input type="text" class="widefat" name="opw_url" id="opw_url" value="<?php echo $opw['opw_url'];?>"></td></tr>
		<tr><td>Method</td><td><select aria-label="This will be the type of POST or GET that you send." class="widefat" id="opw_method" name="opw_method"><option value="post" <?php echo $sel_method_post;?>>post</option><option value="get" <?php echo $sel_method_get;?>>get</option></select></td></tr>
		<tr><td>Fireonly when click id is present</td><td><select aria-label="Fire only when click id is present." class="widefat" id="opw_fire" name="opw_fire"><option value="yes" <?php echo $sel_fire_yes;?>>yes</option><option value="no" <?php echo $sel_fire_no;?>>no</option></select></td></tr>
		<tr><td>Incoming Click Id Key</td><td><input type="text" class="wide" name="opw_incoming_click_id" id="opw_incoming_click_id" value="<?php echo $opw['opw_incoming_click_id'];?>"></td></tr>
		<tr><td>Outgoing Click Id Key</td><td><input type="text" class="wide" name="opw_outgoing_click_id" id="opw_outgoing_click_id" value="<?php echo $opw['opw_outgoing_click_id'];?>"></td></tr>
		<tr><td></td><td>
			<div id="opw_key_value_div">
			<?php 
			$key_counter=array();
			$counter=1;
			if(isset($opw['key_values']) && count($opw['key_values'])):
			foreach($opw['key_values'] as $values):
			$counter = trim($values['counter']);
			?>
			<table class="form-table" id="key_value_table_<?php echo $counter;?>">
				<tr>
				<td>Key <?php echo $counter;?><br>
					<input type="text" class="wide" value="<?php echo $values['key']?>" id="opw_key_<?php echo $counter;?>" name="opw_key_<?php echo $counter;?>" />
				</td>
				<td>Value <?php echo $counter;?><br>
					<input type="text" class="wide" value="<?php echo $values['value']?>" id="opw_value_<?php echo $counter;?>"  name="opw_value_<?php echo $counter;?>" /><a href="javascript:deleteOPWKeyValue(<?php echo $counter;?>);">X</a>
				</td>
			</tr></table>
			<?php 
			array_push($key_counter,$counter);
			endforeach;
			else:
			array_push($key_counter,$counter);
			?>
				<table class="form-table" id="key_value_table_<?php echo $counter;?>">
					<tr>
						<td>Key <?php echo $counter;?><br>
							<input type="text" class="wide" value="" id="opw_key_<?php echo $counter;?>" name="opw_key_<?php echo $counter;?>" />
						</td>
						<td>Value <?php echo $counter;?><br>
							<input type="text" class="wide" value="" id="opw_value_<?php echo $counter;?>" name="opw_value_<?php echo $counter;?>" /><a href="javascript:deleteOPWKeyValue(<?php echo $counter;?>);">X</a>
					</td>
					</tr>
			  </table>
			<?php 
			endif;
			?>
			
			</div>
			<input type="hidden" value="<?php echo"[".implode(",",$key_counter)."]";?>" id="opw_key_values_number" name="opw_key_values_number"/>
			
			<input type="button" name="add_more_key_values" value="Add More" id="add_more_key_values" class="button button-primary"/>
		</td></tr>
		<tr> <th scope="row"><label for="all-link-submit"></label></th>
                    <td id="td-link-submit">
                        <input type="submit" name="submit" id="submit" class="save-link button button-primary" value="Save Link">
                        <label id="saved-link-confirmation-message" class="confirmation-message"></label>
                    </td></tr>
	</table>
</form>
<br class="clear">
				<p>For documentation on setting up postback links see our help <a href="https://www.wpconcierges.com/plugins/order-postback-for-woocommerce/" target="_blank">documentation</a></p>
				
<script>
	jQuery("#add_more_key_values").click(function(e){
		var key_values_number = jQuery.parseJSON(jQuery("#opw_key_values_number").val());
		key_values_number.sort(function(a, b) {
           return a - b;
        });
	
		kv_number = parseInt(key_values_number[key_values_number.length-1])+1;
		
		jQuery("#opw_key_value_div").append("<table class=\"form-table\" id=\"key_value_table_"+kv_number+"\"><tr><td>Key "+kv_number+"<br><input type=\"text\" class=\"wide\" value=\"\" id=\"opw_key_"+kv_number+"\" name=\"opw_key_"+kv_number+"\" /></td><td>Value "+kv_number+"<br><input type=\"text\" class=\"wide\" value=\"\" id=\"opw_value_"+kv_number+"\" name=\"opw_value_"+kv_number+"\" /><a href=\"javascript:deleteOPWKeyValue("+kv_number+");\">X</a></td></tr></table>");
		key_values_number.push(kv_number);
		jQuery("#opw_key_values_number").val(JSON.stringify(key_values_number));
	});
	
	function deleteOPWKeyValue(key_value_row_id){
		  var kvid = "#key_value_table_"+key_value_row_id;
	    jQuery(kvid).remove();
	    	var key_values_number = jQuery.parseJSON(jQuery("#opw_key_values_number").val());
	    	
	    	  key_values_number = jQuery.grep(key_values_number, function(value) {
 						 return value != key_value_row_id;
					});
	    	
	    		jQuery("#opw_key_values_number").val(JSON.stringify(key_values_number));
	}
	
	jQuery("#submit").click(function(e){
		e.preventDefault();
		  var data = {
			'action': 'opw_save_new_link',
			'form_data': jQuery("#create-link-form").serializeArray()
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			var obj_response = jQuery.parseJSON(response);
			jQuery('#saved-link-confirmation-message').html(obj_response.message);
			
			if(obj_response.status == "success"){
				window.location="?page=order-postback-woo";
			}
		});
	});
	
	jQuery("#submit-and-new").click(function(e){
		e.preventDefault();
		  var data = {
			'action': 'opw_save_new_link',
			'form_data': jQuery("#create-link-form").serializeArray()
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			var obj_response = jQuery.parseJSON(response);
			jQuery('#saved-link-confirmation-message').html(obj_response.message);
			if(obj_response.status == "success"){
				jQuery("#create-link-form").find("input[type=text], textarea").val('');
				jQuery("#opw_link_id").remove();
				jQuery("#opw_key_values_number_old").remove();
				var key_values_number = jQuery.parseJSON(jQuery("#opw_key_values_number").val());
				
					jQuery("#opw_key_values_number").val(JSON.stringify(1));
					jQuery("#opw_key_value_div").append("<table class=\"form-table\" id=\"key_value_table_1\"><tr><td>Key 1<br><input type=\"text\" class=\"wide\" value=\"\" id=\"opw_key_1\" name=\"opw_key_1\" /></td><td>Value 1<br><input type=\"text\" class=\"wide\" value=\"\" id=\"opw_value_1\" name=\"opw_value_1\" /><a href=\"javascript:deleteOPWKeyValue(1);\">X</a></td></tr></table>");
						jQuery("#opw_key_1").val("");
						jQuery("#opw_value_1").val("");
				 jQuery.each(key_values_number, function(index,value) {
				 	
 						 var kvid = "#key_value_table_"+value;
 						 jQuery(kvid).remove();
					});
					let kvn = [1];
					jQuery("#opw_key_values_number").val(JSON.stringify(kvn));
	    	
			}
		});
	});
</script>				
<?php 
}else{
	wp_redirect("/wp-admin/tools.php?page=order-postback-woo");
	exit;
}
?>