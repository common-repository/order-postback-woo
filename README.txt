== Order Postback for Woocommerce ==
Contributors: wpconcierges
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=support@otterstromcorp.com&lc=US&item_name=Otterstrom%20postback%29&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: affiliate pixel tracking, pixel tracking, woocommerce, order postback, affiliates
Requires at least: 3.1
Tested up to: 6.1.1
Stable tag: 1.1.1
Requires PHP: 5.6 or above
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

This plugin will post the order data from your Woocommerce store to any url of your choosing via a POST or GET. Useful for sending Affiliate Pixels and Order Data to a third party,  

== Description ==
This plugin will allow you to post/get to one url and append any of the stored Woocommerce order meta data.  See the documentation for the complete list of available fields.  

This plugin is great if you need to send data to a third party, such as an affiliate network without having to place the pixels on your thank you page.

Great for server 2 server post back pixel firing, or breaking up the image url from the affiliate pixel image.  You can choose to send the order information only when the clickid exists or send the data on every order.
You can set the incoming and outgoing clickid on the free version for your order postback.

Works with most affiliate networks:
Maxbounty
Rakuten
CJ
Pepperjam
Clickbooth
TrafficRoots
Commissionsoup
HasOffers
Clixgalore
Miva
AdStage
Nativo
Outbrain
Taboola
zapier webhooks
CAKE
MyLead
LemonAds
Affiliaxe
image url callback
Any affiliate network that supports image or server 2 server postback calls


If you need to post/get more than more than 5 url, check out the Pro version.   

Features of the Pro Version:

1.)  Allows you to post/get to any number or URLs.

2.) Will send order data on order status change to pending to processing.  Great for subscription orders that need to be sent to a third party script

3.) Sets a Cookie for all incoming querystring variables that can be used in the postback url parameters on firing.

4.) Create your own custom key value pairs.  You define the keys and values or use the order value replacements for the outgoing values. All standard billing shipping fields are replaceable from the order.  View the list on the Plugin Page.

5.) Add a retargeting pixel to your sites footer.

6.) Great for custom order scripts

7.) Works for affiliate network pixel server 2 server setups or any image based Affiliate pixel tracking.

8.) Custom headers for custom script requirements

9.) Use for any custom meta field on an order within the key value pairs. 

<a href="https://portal.wpconcierges.com/plugins/order-postback-for-woocommerce/" target="_blank" >https://portal.wpconcierges.com/plugins/order-postback-for-woocommerce/</a>

== Installation ==

1. Upload 'order-postback-woo' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to *Tools > Order Postback Woo*
4. Place your post back url to your order script and choose between a POST or GET


== Frequently Asked Questions ==
= How can I get support for this plugin
Reach out to the team at www.wpconcierges.com or email support@wpconcierges.com 
Check out the documentation at https://portal.wpconcierges.com/plugins/order-postback-for-woocommerce/

== Screenshots ==



== Changelog ==
= 1.1.0 =
tested woo 6.2.1, wordpress 5.9.1
Added check to not fire if already fired
Allowing 5 links on the free version 

= 1.0.9 =
fixed issue adding more than 10 key value pairs to the postback

= 1.0.8 =
Tested upto WooCommerce 5.6.0 and WP 5.8
fixed special characters for $curren as a key

= 1.0.7 =
fixed issue with incoming click id
Tested with WooCommerce 5.0.0

= 1.0.6 =
fixed removing uncessary variables on the post url

= 1.0.5 =
fixed incoming outgoing variable issues if on version 1.0.3 or 1.0.4

= 1.0.4 =
fixed replacement variable offset issue if on version 1.0.3 

= 1.0.3 =
added can fire when clickid is set for affiliate networks
added incoming and outgoing clickid fields

= 1.0.2 =
complete rewrite no uses a custom post and not the admin options 
create a custom url and parameters to fit your affiliate or custom script 
if you are on 1.0.1 please be prepared to create a new custom link structure that you new 
link can recieve without the json encoded values.

= 1.0.1 =
removing unncessary js files.

= 1.0.0 =
Initial Release.

== Additional Info ==
When you need to post your Woocommerce store order data to a third party script.