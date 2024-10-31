
<?php
	function set_screen( $status, $option, $value ) {
		return $value;
	}

	/**
	 * Plugin settings page
	 */
  function plugin_settings_page() {
		$links_obj = new PostbackRegLinks_List();
		?>
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
					<div id="post-body-content">
						<div class="meta-box-sortables ui-sortable">
							<form method="post">
								<?php
								$links_obj->prepare_items();
								$links_obj->display(); ?>
							</form>
						</div>
					</div>
				</div>
				<br class="clear">
				<p>For documentation on setting up postback links see our help <a href="https://www.wpconcierges.com/plugins/order-postback-for-woocommerce/" target="_blank">documentation</a></p>
				
			</div>
	<?php
	}

	/**
	 * Screen options
	 */
	function screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => 'Postback Links',
			'default' => 5,
			'option'  => 'links_per_page'
		];

		add_screen_option( $option, $args );

		
	}

screen_option();
add_filter( 'set-screen-option','set_screen', 10, 3 );
plugin_settings_page(); 
	