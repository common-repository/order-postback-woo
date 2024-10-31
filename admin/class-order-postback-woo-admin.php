<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.wpconcierges.com/plugins/order_postback_woo/
 * @since      1.0.0
 *
 * @package    order_postback_woo
 * @subpackage order_postback_woo/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    order_postback_woo
 * @subpackage order_postback_woo/admin
 * @author     WpConcierges <support@wpconcierges.com>
 */
class order_postback_woo_admin{

  /**
	 * The plugin options.
	 *
	 * @since 		1.0.0
	 * @access 		private
	 * @var 		string 			$options    The plugin options.
	 */
	private $options;


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->post_type_slug = 'opw_post_link';
        $this->set_options();
	}

  	/**
	 * Registers plugin settings
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function register_settings() {

		// register_setting( $option_group, $option_name, $sanitize_callback );

		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-options',
			array( $this, 'validate_options' )
		);

	} // register_settings()
	
	/**
	 * Validates saved options
	 *
	 * @since 		1.0.0
	 * @param 		array 		$input 			array of submitted plugin options
	 * @return 		array 						array of validated plugin options
	 */
	public function validate_options( $input ) {

		//wp_die( print_r( $input ) );

		$valid 		= array();
		$options 	= $this->get_options_list();

		foreach ( $options as $option ) {

			$name = $option[0];
			$type = $option[1];

			if ( 'repeater' === $type && is_array( $option[2] ) ) {

				$clean = array();

				foreach ( $option[2] as $field ) {

					foreach ( $input[$field[0]] as $data ) {

						if ( empty( $data ) ) { continue; }

						$clean[$field[0]][] = $this->sanitizer( $field[1], $data );

					} // foreach

				} // foreach

				$count = count( $clean );

				for ( $i = 0; $i < $count; $i++ ) {

					foreach ( $clean as $field_name => $field ) {

						$valid[$option[0]][$i][$field_name] = $field[$i];

					} // foreach $clean

				} // for

			} else {

				$valid[$option[0]] = $this->sanitizer( $type, $input[$name] );

			}
			
		}

		return $valid;

	} // validate_options()
	
	private function sanitizer( $type, $data ) {

		if ( empty( $type ) ) { return; }
		if ( empty( $data ) ) { return; }

		$return 	= '';
		$sanitizer 	= new order_postback_woo_Sanitize();

		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );

		$return = $sanitizer->clean();

		unset( $sanitizer );

		return $return;

	} // sanitizer()

  /**
	 * Registers settings fields with WordPress
	 */
  public function register_fields() {

  }
  
  /**
	 * Creates a select field
	 *
	 * Note: label is blank since its created in the Settings API
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_select( $args ) {

		$defaults['aria'] 			= '';
		$defaults['blank'] 			= '';
		$defaults['class'] 			= 'widefat';
		$defaults['context'] 		= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['selections'] 	=array('post','get');
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-select-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		if ( empty( $atts['aria'] ) && ! empty( $atts['description'] ) ) {

			$atts['aria'] = $atts['description'];

		} elseif ( empty( $atts['aria'] ) && ! empty( $atts['label'] ) ) {

			$atts['aria'] = $atts['label'];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/order-postback-woo-admin-field-select.php' );

	} // field_select()

  public function field_text( $args ) {

		$defaults['class'] 			= 'text wide';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['placeholder'] 	= '';
		$defaults['type'] 			= 'text';
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/order-postback-woo-admin-field-text.php' );

	} // field_text()
 
	 /**
	 * Creates a textarea field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_textarea( $args ) {

		$defaults['class'] 			= 'large-text';
		$defaults['cols'] 			= 50;
		$defaults['context'] 		= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['rows'] 			= 10;
		$defaults['value'] 			= '';

		apply_filters( $this->plugin_name . '-field-textarea-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/order-postback-woo-admin-field-textarea.php' );

	} // field_textarea()

    /**
	 * Creates a checkbox field
	 *
	 * @param 	array 		$args 			The arguments for the field
	 * @return 	string 						The HTML field
	 */
	public function field_checkbox( $args ) {

		$defaults['class'] 			= '';
		$defaults['description'] 	= '';
		$defaults['label'] 			= '';
		$defaults['name'] 			= $this->plugin_name . '-options[' . $args['id'] . ']';
		$defaults['value'] 			= 0;

		apply_filters( $this->plugin_name . '-field-checkbox-options-defaults', $defaults );

		$atts = wp_parse_args( $args, $defaults );

		if ( ! empty( $this->options[$atts['id']] ) ) {

			$atts['value'] = $this->options[$atts['id']];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/order-postback-woo-admin-field-checkbox.php' );
	}
	
	/**
	 * Returns an array of options names, fields types, and default values
	 *
	 * @return 		array 			An array of options
	 */
	public static function get_options_list() {

  
		$options = array();

	
	
		return $options;

	} // get_options_list()
	
	/**
	 * Registers settings sections with WordPress
	 */
	public function register_sections() {

		add_settings_section(
			$this->plugin_name . '-messages',
			apply_filters( $this->plugin_name . 'section-title-messages', esc_html__( '',$this->plugin_name) ),
			array( $this, 'section_messages' ),
			$this->plugin_name
		);

	} // register_sections()
	
	/**
	 * Creates a settings section
	 *
	 * @since 		1.0.0
	 * @param 		array 		$params 		Array of parameters for the section
	 * @return 		mixed 						The settings section
	 */
	public function section_messages( $params ) {

		include( plugin_dir_path( __FILE__ ) . 'partials/order-postback-woo-admin-section-messages.php' );

	} // section_messages()
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		
		//wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/'.$this->plugin_name.'-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/'.$this->plugin_name.'-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	
	public function order_postback_woo_admin_menu(){
    	add_management_page( 'Order Postback','Order Postback','manage_options',$this->plugin_name,array($this,'page_options'));  
    }
  
  private function set_options() {
    
		$this->options = get_option( $this->plugin_name . '-options' );
   
	} // set_options()
	
	public function page_options() {
  
		include( plugin_dir_path( __FILE__ ) . 'partials/order-postback-woo-admin-page-settings.php' );

	} // page_options()

	public function save_new_postback(){
		//print_r($_POST);
		$datas = $_POST['form_data'];
		$pure_datas = $datas;
		$fields = array('opw_name','opw_url','opw_method','opw_fire','opw_incoming_click_id','opw_outgoing_click_id','opw_key_values_number','opw_key_values_number_old','opw_link_id');
	  $form_data = array();
		$meta_data = array();
		
		foreach($datas as $data){
			if(in_array($data['name'],$fields)){
				$form_data[$data['name']] = $data['value'];
			}
		}
		
		if(isset($form_data['opw_link_id']) && ((int)$form_data['opw_link_id']>0)){
			$post_id = $this->save_update_link($form_data,$pure_datas);
			$link_type ="Updated";
		}else{
			$post_id = $this->save_new_link($form_data,$pure_datas);
			$link_type ="Created";
		}
	  
	  if($post_id >0){
	  	$status =array('status'=>"success",'message'=>'Great job Link '.$link_type.'!');
		}else{
			$status =array('status'=>"failed",'message'=>'failed to create/update link');
		}
		
		 
		print json_encode($status);
		exit;
	}
	
	public function save_update_link($form_data,$datas){

		$my_post = array();
		$my_post['post_title']    = $this->sanitizer('text',$form_data['opw_name']);
		$my_post['post_content']  = $this->sanitizer('text',$form_data['opw_url']);
	  $my_post['ID']=$form_data['opw_link_id'];
	  
	   $post_id = wp_update_post($my_post);
	   
		
		 if((int)$post_id){
		  unset($form_data['opw_name']);
		  unset($form_data['opw_url']);
		  unset($form_data['opw_link_id']);
		  $key_values_number = json_decode($form_data['opw_key_values_number']);
		  $key_values_number_old = json_decode($form_data['opw_key_values_number_old']);
		  $form_data['opw_key_values_number_old'] = $key_values_number;
		  
		 foreach($form_data as $meta_key => $meta_value){
		 	if($meta_key=="opw_outgoing_retarget"){
		 		update_post_meta($post_id,$meta_key,$this->sanitizer('textarea',$meta_value));
		 	}elseif($meta_key=="opw_key_values_number" || $meta_key=="opw_key_values_number_old"){
		 	  update_post_meta($post_id,$meta_key,$this->sanitizer('text',implode(",",$meta_value)));
		 	}else{
		 	  update_post_meta($post_id,$meta_key,$this->sanitizer('text',$meta_value));
		  }
		 }
		 
	   
	   
		  foreach($key_values_number_old as $id => $key_id){
		    $key = "opw_key_".$key_id;
		     $value = "opw_value_".$key_id;
		    delete_post_meta($post_id,$key);
		    delete_post_meta($post_id,$value);
		 }
		 
		 $key_value_pairs = array();
		 
		 foreach($key_values_number as $id => $key_id){
		    $key = "opw_key_".$key_id;
		     $value = "opw_value_".$key_id;
		    array_push($key_value_pairs,$key);
		    array_push($key_value_pairs,$value);
		 }
		
		 foreach($datas as $data){
			 if(strlen($data['value']) && in_array($data['name'],$key_value_pairs)){
				 $meta_data[$data['name']] = $data['value'];
			 }
		 }
		
		 foreach($meta_data as $meta_key => $meta_value){
		 	update_post_meta($post_id,$meta_key,$this->sanitizer('text',$meta_value));
		 }
		 $status = $post_id;
		}else{
			$status = 0;
		}
		return $status;
	}
	
	public function save_new_link($form_data,$datas){
		
		$my_post = array();
		$my_post['post_title']    = $this->sanitizer('text',$form_data['opw_name']);
		$my_post['post_content']  = $this->sanitizer('text',$form_data['opw_url']);
		$my_post['post_status']   = 'publish';
		$my_post['post_author']   = get_current_user_id();
		$my_post['post_category'] = array(0);
		$my_post['post_type'] = $this->post_type_slug;

		// Insert the post into the database
		 $post_id = wp_insert_post($my_post);
		 if((int)$post_id){
		 unset($form_data['opw_name']);
		 unset($form_data['opw_url']);
		 $key_values_number = json_decode($form_data['opw_key_values_number']);
		
		 
		 $form_data['opw_key_values_number_old'] = $key_values_number;
		  
		 foreach($form_data as $meta_key => $meta_value){
		 	if($meta_key=="opw_outgoing_retarget"){
		 		add_post_meta($post_id,$meta_key,$this->sanitizer('textarea',$meta_value));
		 	}elseif($meta_key=="opw_key_values_number" || $meta_key=="opw_key_values_number_old"){
		 		add_post_meta($post_id,$meta_key,$this->sanitizer('text',implode(",",$meta_value)));
			}else{
		 		add_post_meta($post_id,$meta_key,$this->sanitizer('text',$meta_value));
			}
		 }
		 $key_value_pairs = array();
		 
		  foreach($key_values_number as $id){
		    $key = "opw_key_".$id;
		     $value = "opw_value_".$id;
		    array_push($key_value_pairs,$key);
		    array_push($key_value_pairs,$value);
		 }
		 
		 
		 foreach($datas as $data){		
			if(strlen($data['value']) && in_array($data['name'],$key_value_pairs)){
				$meta_data[$data['name']] = $data['value'];
			}
		 }
		 
		
		 foreach($meta_data as $meta_key => $meta_value){
		 	add_post_meta($post_id,$meta_key,$this->sanitizer('text',$meta_value));
		 }
		 $status =$post_id;
		}else{
			$status =0;
		}
		return $status;
	}
}
