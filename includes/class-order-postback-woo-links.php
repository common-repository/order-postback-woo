<?php 

if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class PostbackRegLinks_List extends WP_List_Table {

	/** Class constructor */
	private $post_type_slug;
	
	public function __construct() {

		parent::__construct( [
			'singular' => __( 'Postback Link', 'order-postback-woo' ), //singular name of the listed records
			'plural'   => __( 'Postback Links', 'order-postback-woo' ), //plural name of the listed records
			'ajax'     => false //does this table support ajax?
		] );
   $this->post_type_slug = 'opw_post_link';
	}

  public function get_all_links(){
  		global $wpdb;

        $sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts WHERE post_type=%s LIMIT 5",array($this->post_type_slug));
    
		$results = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $results;
  }

	/**
	 * Retrieve customers data from the database
	 *
	 * @param int $per_page
	 * @param int $page_number
	 *
	 * @return mixed
	 */
	public static function get_links( $per_page = 1, $page_number = 1, $post_type='opw_post_link' ) {

		global $wpdb;

		$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts WHERE post_type=%s LIMIT 1",array($post_type));

		if ( ! empty( $_REQUEST['orderby'] ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		}
  
		$result = $wpdb->get_results( $sql, 'ARRAY_A' );

		return $result;
	}


	/**
	 * Delete a customer record.
	 *
	 * @param int $id customer ID
	 */
	public static function delete_link( $id ) {
		global $wpdb;

		$wpdb->delete(
			"{$wpdb->prefix}posts",
			[ 'ID' => $id ],
			[ '%d' ]
		);
		
		$wpdb->delete(
			"{$wpdb->prefix}postmeta",
			[ 'post_id' => $id ],
			[ '%d' ]
		);
		
	}


	/**
	 * Returns the count of records in the database.
	 *
	 * @return null|string
	 */
	public static function record_count($post_type='opw_post_link') {
		
		global $wpdb;
		
    $sql = $wpdb->prepare("SELECT count(*) FROM {$wpdb->prefix}posts WHERE post_type=%s LIMIT 1", array($post_type)); 
		
		return $wpdb->get_var( $sql );
	}


	/** Text displayed when no customer data is available */
	public function no_items() {
		_e( 'No links avaliable.', 'order-postback-woo' );
	}


	/**
	 * Render a column when no column specific method exist.
	 *
	 * @param array $item
	 * @param string $column_name
	 *
	 * @return mixed
	 */
	public function column_default( $item, $column_name ) {
		
		
		switch ( $column_name ) {
			case 'post_title':
			case 'post_content':
				return $item[ $column_name ];
			default:
				return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}
	}

	/**
	 * Render the bulk edit checkbox
	 *
	 * @param array $item
	 *
	 * @return string
	 */
	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}


	/**
	 * Method for name column
	 *
	 * @param array $item an array of DB data
	 *
	 * @return string
	 */
	function column_post_title( $item ) {
    
		$delete_nonce = wp_create_nonce( 'sp_delete_link' );
        $edit_nonce = wp_create_nonce( 'sp_edit_link' );
    	
		$title = '<strong>' . $item['post_title'] . '</strong>';

		$actions = [
			'edit' => sprintf( '<a href="?page=%s&action=%s&link=%s&_wpnonce=%s">Edit</a>', esc_attr( $_REQUEST['page'] )."&tab=tools", 'edit', absint( $item['ID'] ), $edit_nonce ),
			'delete' => sprintf( '<a href="?page=%s&action=%s&link=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
		];

		return $title . $this->row_actions( $actions );
	}


  function column_post_content( $item ) {
    
		$title = '<a href="'.$item['post_content'].'">' . $item['post_content'] . '</a>';
		return $title;
	}

	/**
	 *  Associative array of columns
	 *
	 * @return array
	 */
	function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'post_title'    =>'Name',
			'post_content' => 'Link'
		];
   
		return $columns;
	}


	/**
	 * Columns to make sortable.
	 *
	 * @return array
	 */
	public function get_sortable_columns() {
		$sortable_columns = array(
			'post_title' => array( 'Name', true ),
			'post_content' => array( 'Link', false )
		);

		return $sortable_columns;
	}

	/**
	 * Returns an associative array containing the bulk action
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}


	/**
	 * Handles data query and filter, sorting, and pagination.
	 */
	public function prepare_items() {
    $columns = $this->get_columns();
    $hidden = array();
    $sortable = $this->get_sortable_columns();

		$this->_column_headers = array($columns,$hidden,$sortable);
   
		/** Process bulk action */
		$this->process_bulk_action();
    
		$per_page     = $this->get_items_per_page( 'links_per_page', 1 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count($this->post_type_slug);
    
		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );

		$this->items = self::get_links($per_page, $current_page,$this->post_type_slug );
		
	}
 
  public function get_link($ID){
  		global $wpdb;
    $link = array();
    $fields = array('opw_method','opw_fire','opw_incoming_click_id','opw_outgoing_click_id','opw_key_values_number','opw_key_values_number_old');
    $link['key_values'] = array();
    
		$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}posts WHERE ID=%d",array($ID));
    
		$posts = $wpdb->get_results( $sql, 'ARRAY_A');
		$post = current($posts);
		
        $link['opw_name'] = $post['post_title'];
        $link['opw_url'] = $post['post_content'];
    
    
        $sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}postmeta WHERE post_id=%d",array($ID));
		$metas = $wpdb->get_results( $sql, 'ARRAY_A');
		
		foreach($metas as $meta){
			
			if(in_array($meta['meta_key'],$fields)){
				
				
		    	$link[$meta['meta_key']] = $meta['meta_value'];		 
		    
			}else{
				
				$tmp = explode("_",$meta['meta_key']);
				
				if(isset($tmp[2]) && in_array($tmp[1],array('key','value'))){
					$counter = $tmp[2];
				
					if((strpos($meta['meta_key'],"_key_"))>0){
						$link['key_values'][$counter]['key'] = $meta['meta_value'];
			  	}
				
					if((strpos($meta['meta_key'],"_value_"))>0){
						$link['key_values'][$counter]['value'] = $meta['meta_value'];	
					}
				
					$link['key_values'][$counter]['counter'] = $counter;
			  }
			}
		}
		
		return $link;
  }
	public function process_bulk_action() {

		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {

			// In our file that handles the request, verify the nonce.
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );

			if ( ! wp_verify_nonce( $nonce, 'sp_delete_link' ) ) {
				die( 'dang something went wrong' );
			}
			else {
				self::delete_link( absint( $_GET['link'] ) );
                    
		                // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		                // add_query_arg() return the current url
		               
		                wp_redirect( esc_url_raw(add_query_arg( array('page' => 'order-postback-woo'),admin_url('tools.php'))));
				exit;
			}

		}

		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {

			$delete_ids = esc_sql( $_POST['bulk-delete'] );

			// loop over the array of record IDs and delete them
			foreach ( $delete_ids as $id ) {
				self::delete_link( $id );

			}
      
       
			// esc_url_raw() is used to prevent converting ampersand in url to "#038;"
		        // add_query_arg() return the current url
		        wp_redirect( esc_url_raw(add_query_arg( array('page' => 'order-postback-woo'),admin_url('tools.php'))));
			exit;
		}
	}

}
?>