<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.wpconcierges.com/plugins/order_postback_woo/
 * @since      1.0.0
 *
 * @package    order_postback_woo
 * @subpackage order_postback_woo/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    order_postback_woo
 * @subpackage order_postback_woo/public
 * @author     WpConcierges <support@wpconcierges.com>
 */
class order_postback_woo_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $order_postback_woo    The ID of this plugin.
	 */
	private $order_postback_woo;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
   
	/**
	 * The postback url
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current postback url.
	 */
	private $postback_url;

/**
	 * The postback type
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current postback url.
	 */
	private $postback_type;
	
	/**
	 * The postback params
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current postback params.
	 */
	private $postback_params;
    private $can_fire;
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $order_postback_woo       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $order_postback_woo, $version ) {
    
		$this->plugin_name = $this->order_postback_woo = $order_postback_woo;
		$this->version = $version;
		$this->post_type_slug = 'opw_post_link';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in order_postback_woo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The order_postback_woo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( $this->order_postback_woo, plugin_dir_url( __FILE__ ) . 'css/order_postback_woo-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in order_postback_woo_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The order_postback_woo_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_script( $this->order_postback_woo, plugin_dir_url( __FILE__ ) . 'js/order_postback_woo-public.js', array( 'jquery' ), $this->version, false );

	}

	public function send_woo_thankyou_order($order_id){
		
		$order = wc_get_order( $order_id );
		$this->order_values = $this->get_order_values($order,$order_id);
		$this->do_postback_woo();
	}  

	public function get_order_values($order,$order_id){
	    $values =array();
        
	  $order_items = array();
	  $order_type =$order->get_type();
		$order_meta = get_post_meta($order_id);
	  $values['billing_email']=$order_meta['_billing_email'][0];
    $values['billing_phone']=$order_meta['_billing_phone'][0];
    $values['billing_first_name']=$order_meta['_billing_first_name'][0];
    $values['billing_last_name']=$order_meta['_billing_last_name'][0];
    $values['billing_company']=$order_meta['_billing_company'][0];
    $values['billing_address_1']=$order_meta['_billing_address_1'][0];
    $values['billing_address_2']=$order_meta['_billing_address_2'][0];
    $values['billing_city']=$order_meta['_billing_city'][0];
    $values['billing_state']=$order_meta['_billing_state'][0];
    $values['billing_postcode']=$order_meta['_billing_postcode'][0];
    $values['billing_country']=$order_meta['_billing_country'][0];
    
    $values['shipping_first_name']=$order_meta['_shipping_first_name'][0];
    $values['shipping_last_name']=$order_meta['_shipping_last_name'][0];
    $values['shipping_company']=$order_meta['_shipping_company'][0];
    $values['shipping_address_1']=$order_meta['_shipping_address_1'][0];
    $values['shipping_address_2']=$order_meta['_shipping_address_2'][0];
    $values['shipping_city']=$order_meta['_shipping_city'][0];
    $values['shipping_state']=$order_meta['_shipping_state'][0];
    $values['shipping_postcode']=$order_meta['_shipping_postcode'][0];
    $values['shipping_country']=$order_meta['_shipping_country'][0];
    
    $values['order_tax']= $order_meta['_order_tax'][0];
    $values['order_total']= $order_meta['_order_total'][0];
    
    $values['cart_discount']= $order_meta['_cart_discount'][0];
    $values['cart_discount_tax']= $order_meta['_cart_discount_tax'][0];
    
    $values['order_shipping']= $order_meta['_order_shipping'][0];
    $values['order_shipping_tax']= $order_meta['_order_shipping_tax'][0];
    
    $values['order_currency']= $order_meta['_order_currency'][0];
    $values['payment_method']= $order_meta['_payment_method'][0];
    
    $values['customer_ip_address'] = $order_meta['_customer_ip_address'][0];
    
    if(isset($order_meta['_transaction_id'][0]))
    $values['transaction_id'] = $order_meta['_transaction_id'][0];
    else
    $values['transaction_id'] = "";
    
    if(isset($order_meta['_paid_date'][0]))
    $values['paid_date'] = $order_meta['_paid_date'][0];
    else
    $values['paid_date'] = "";
    
    if(isset($order_meta['_customer_user'][0]))
    	$values['customer_user'] = $order_meta['_customer_user'][0];
    else
    	$values['customer_user'] = 0;
    
   	if(isset($order_meta['_order_number'][0])) 
    	$values['order_number']=$order_meta['_order_number'][0];
   	else
    	$values['order_number']=$order_id;
    	
    	  $is_subscription = false;
        if(function_exists("wcs_order_contains_subscription") && wcs_order_contains_subscription($order,$order_type)){
        	$subscription = new WC_Subscription($order_id);
        	$items= $subscription->order->get_items();
        	$is_subscription = true;
        	
        }else{
        	$items = $order->get_items();
        }
      
        
        $total_items=0;
		    $total_discount_amount=0;
		 
		 
        foreach($items as  $item_id => $item){
        
					$variation_id=$item->get_variation_id();
		 			if($variation_id>0){
		 			  $product = new WC_Product_Variation($variation_id);
		 			}elseif($is_subscription){
		 				$product = $item->get_product();
		 			}else{
		 				$product = new WC_Product($item['product_id']);
		 			}
		     
		      if(isset($product)){
		      	$tmp = array();
		      	$tmp['product_id'] = $item['product_id'];
						$tmp['name']=$product->get_name();
         
      			$tmp['cost'] =$item_cost =strip_tags($product->get_price());
      		
      			$tmp['quantity'] = $quantity = $order->get_item_meta($item_id, '_qty', true);
           	$tmp['sku'] = $product->get_sku();
           	
           	array_push($order_items,$tmp);
      			$total_items+=($item_cost*$quantity);
      	  }
        }
		   $values['product_total']=$total_items;
		   $values['products']=$order_items;
      
       
      return $values;
	}

	private function set_parameters($metas){
		
		$params = array();
		$cookie_keys = array('opw_incoming_click_id','opw_outgoing_click_id');
		$keys = array('product_total','products','billing_email','billing_phone',
		'billing_first_name','billing_last_name','billing_company','billing_address_1','billing_address_2','billing_city',
		'billing_state','billing_postcode','billing_country','shipping_first_name','shipping_last_name','shipping_company',
		'shipping_address_1','shipping_address_2','shipping_city','shipping_state','shipping_postcode','shipping_country',
		'order_tax','order_total','cart_discount','cart_discount_tax','order_shipping','order_shipping_tax','order_currency',
		'payment_method','customer_ip_address','transaction_id','paid_date','customer_user','order_number');
		
     foreach($metas as $meta_key => $meta_value){			

			   if(in_array($meta_value,$keys)){
			     $params[$meta_key]=$this->order_values[$meta_value];
			   }elseif(!in_array($meta_key,$cookie_keys) && isset($_COOKIE[$meta_key])){
			   	if(stripos($_COOKIE[$meta_key],'_order_')>0){
			   		$params[$meta_key]=$meta_value;
			   	}else{
			   		$params[$meta_value]=$_COOKIE[$meta_key]; 
			   	}
			   }else{
           $params[$meta_key]=$meta_value; 
			   }
		 }
		
		if(isset($_COOKIE[$metas['opw_incoming_click_id']])){
       $params[$metas['opw_outgoing_click_id']] = $_COOKIE[$metas['opw_incoming_click_id']]; 
		}  
     
		return $params;
	}
	
	public function get_all_links(){
		global $wpdb;

	  $sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts WHERE post_type=%s LIMIT 1",array($this->post_type_slug));
  
	  $results = $wpdb->get_results( $sql, 'ARRAY_A' );

	  return $results;
  }
	
	 public function set_click_id(){
		
	    $links = $this->get_all_links();
        $host = parse_url(get_option('siteurl'), PHP_URL_HOST);
    
	    foreach($links as $link){
		         $click_id = get_post_meta($link['ID'],'opw_incoming_click_id',true);
				
				
				if(isset($_GET[$click_id])){
					
					$get_click_id = $_GET[$click_id];
					if(strlen($click_id) && strlen($get_click_id)){
						 $expiry = strtotime('+1 month');
						 setcookie($click_id,$get_click_id,$expiry,"/",$host);
				  }
			 }
	   }
	   
	
   }

   private function can_fire_check($params){

	if(isset($_COOKIE['wpc_opwf'])){
		$this->can_fire = false;
		return;  
     }

	  if($params['opw_fire']=="no"){
		  $this->can_fire = true;
	  }else{
		  $this->can_fire = false;
	  }
	  $click_id = $params['opw_incoming_click_id'];
	  $out_click_id = $params['opw_outgoing_click_id'];
	  
	  
	  	if(isset($_COOKIE[$click_id])){
	 		 $this->postback_params[$out_click_id] = $_COOKIE[$click_id]; 
		   
	 		 if(($params['opw_fire'] == "yes") && strlen($this->postback_params[$out_click_id])){
				$this->can_fire = true;
	 		 }
	   }  
   }	

	private function get_postback_link($link,$field_to_get=''){
		$fields = array();
		
	  $fields =$this->get_postback_link_fields($link);
		
		return $fields;
	}
	
	
	private function get_postback_link_fields($link){
	  $field_check = array('opw_url','opw_method','opw_fire','opw_incoming_click_id','opw_outgoing_click_id','opw_key_values_number');
  	$fields = array();
  	$fields['opw_url'] = $link['post_content'];
  	$post_id = $link['ID'];
  	
  	$metas = get_post_meta($post_id);
  	$datas = array();
  	foreach($metas as $meta_key => $meta_value){
  		if(in_array($meta_key,$field_check)){
  			$fields[$meta_key]=current($meta_value);
  		}else{
  		
  			$tmp = explode("_",$meta_key);
				
				if(isset($tmp[2]) && in_array($tmp[1],array('key','value'))){
					$counter = $tmp[2];
				  if(is_numeric($counter)){
						if((strpos($meta_key,"_key_"))>0){
							$datas['key_values'][$counter]['key'] = current($meta_value);
			  		}
				
						if((strpos($meta_key,"_value_"))>0){
							$datas['key_values'][$counter]['value'] = current($meta_value);	
						}
				  } 
			  }
  		}
  	}
  		
  	foreach($datas['key_values'] as $data){
  		$fields[$data['key']]=$data['value'];
  	}
  	
  	$fields = $this->set_parameters($fields);
  	
  	return $fields;
	}
	
	private function get_postback_params($fields){
		$field_unset = array('opw_url','opw_method','opw_fire','opw_incoming_click_id','opw_outgoing_click_id','opw_key_values_number','opw_key_values_number_old','opw_link_id');
		foreach($field_unset as $field){
			unset($fields[$field]);
		}
		
		return $fields;
		
	}
	
	private function build_query_string($params){
		$query="";
		$count=0;
		$specail_chars = array("currency");
		
		foreach($params as $key => $value){
			if($count==0){
				$query=$key."=".$value;
			}else{
				$and ="&";
				if(in_array($key,$specail_chars)){
					$and="&amp;";
				}
				$query.=$and.$key."=".$value;
			}
			$count++;
		}
		return $query;
	}
	

	private function do_postback_woo() 
    {      
    	 setlocale(LC_ALL, 'us_En');
        $links = $this->get_all_links();
      $response = array();
      
       foreach($links as $link){ 
       	$this->postback_params = array();
       	$this->postback_url = '';
       	$opw = $this->get_postback_link($link);
      
		   $this->can_fire_check($opw);   
		   
		   if($this->can_fire){ 
       	 	$this->postback_url = $opw['opw_url'];
       	 	
       	   $this->postback_params = $this->get_postback_params($opw); 
			  $host = parse_url(get_option('siteurl'), PHP_URL_HOST);
     
			setcookie("wpc_opwf",1,0,"/",$host);
			  
       	   
       	    $args = array();
      
            if($opw['opw_method'] == "get"){
          	  if(strpos($this->postback_url,"?")>0){
          		$this->postback_url = $this->postback_url."&amp;".$this->build_query_string($this->postback_params);
          	  }else{
          		$this->postback_url = $this->postback_url."?".$this->build_query_string($this->postback_params);
          	  }
           		
          	  $response = wp_remote_get($this->postback_url,$args);
     
            }else{
          	  $args = array("body"=>array($this->postback_params));
              $response = wp_remote_post($this->postback_url,$args);	
              
		        }
	   }
         
	} 
      
        return $response;    
    }   

}
