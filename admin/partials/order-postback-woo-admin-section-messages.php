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

?>
 <div class="order-postback-woo-note">
                            <h3><?php 
            echo  esc_html__( 'Instructions', 'order-postback-woo' ) ;
            ?></h3>
                            <p><?php 
            echo  sprintf( wp_kses( __( 'Set the url where the POST/GET script lives.  The key value pair of the POST/GET are meta_key - meta_value relationship from Order Meta and Product Meta of the Woocommerce Order. This fires everytime a Woocommerce order thank you is fired', 'order_postback_woo_pro' ), array(
                'a' => array(
                'href'   => array(),
                'target' => array(),
            ),
            ) ), esc_url( 'https://portal.wpconcierges.com/plugins/order-postback-for-woocommerce/' ) ) ;
            ?></p>
            <h3><?php 
            echo  esc_html__( 'Documentation', 'order-postback-woo' ) ;
            ?></h3>
              <p><?php 
            echo  sprintf( wp_kses( __( 'You will need to create the KEY name of the variable you want to send to your url, then place the value of the replacement variable names in the VALUE field <a href="%s" target="_blank">Documenation</a>. Enjoy.', 'order_postback_woo_pro' ), array(
                'a' => array(
                'href'   => array(),
                'target' => array(),
            ),
            ) ), esc_url( 'https://portal.wpconcierges.com/plugins/order-postback-for-woocommerce/' ) ) ;
            ?></p>

                        </div>