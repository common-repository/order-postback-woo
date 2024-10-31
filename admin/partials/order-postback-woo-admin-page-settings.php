<?php

/**a
 * Provide a view for a section
 *
 * Enter text below to appear below the section title on the Settings page
 *
 * @link       https://www.wpconcierges.com/plugins/order_postback_woo/
 * @since      1.0.0
 *
 * @package    order_postback_woo
 * @subpackage order_postback_woo/admin/partials
 */

$default_tab = null;
  $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
?>
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
 <nav class="nav-tab-wrapper">
      <a href="?page=order-postback-woo" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">PostBacks</a>
      <a href="?page=order-postback-woo&tab=tools" class="nav-tab <?php if($tab==='tools'):?>nav-tab-active<?php endif; ?>">Add/Edit</a>
    </nav>
   <div class="tab-content"> 
   	<?php switch($tab) :
      case 'tools':
        include("order-postback-woo-admin-tools.php");
        break;
      default:
        include("order-postback-woo-admin-posts.php");
        break;
    endswitch; ?> 
   </div>

</div>