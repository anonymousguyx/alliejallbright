<?php
//Page Slug Body Class
function add_slug_body_class($classes) {

    global $post;
    if (isset($post)) {
        $classes[] = $post->post_type . '-' . $post->post_name;
	}

	if ( is_page_template( 'page-dark-mode.php' ) ) {
        $classes[] = 'dark-mode';
	}
	

    return $classes;
}

add_filter('body_class', 'add_slug_body_class');

function my_theme_enqueue_styles() { 
	$cache_buster = wp_get_theme()->get('Version');

    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/css/style_child.css', array(), $cache_buster, 'all' );

    if ( ! is_admin() ) {
        wp_enqueue_script('guide-spark-custom', get_stylesheet_directory_uri() . '/js/champSchool.js', false, false, true);
      }


}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );



function load_custom_core_options() {
    if ( ! function_exists( 'et_load_core_options' ) ) {
        function et_load_core_options() {
            $options = require_once( get_stylesheet_directory() . esc_attr( "/epanel/custom_options_divi_child.php" ) );
        }
    }
}
add_action( 'after_setup_theme', 'load_custom_core_options' );


//shortcodes hooks
define('child_template_directory', dirname( get_bloginfo('stylesheet_url')) );
require_once($child_template_directory . "shortcodes.php");

add_shortcode('picture', 'random_picture'); //this one is for testing only

add_shortcode('redbutton', 'red_button'); //red button

add_shortcode('bluetitle', 'blue_title'); //blue title

add_shortcode('sponsors', 'show_sponsors'); //our sponsors

add_shortcode('my_calendar_list', 'my_calendar_list_function'); //our sponsors

add_shortcode('l4block', 'linkblockfunction'); //link block

add_shortcode('my_gallery', 'generate_my_gallery'); //my version of the video gallery

add_shortcode('activecalendarreader', 'activereader'); //reading from active.com

add_shortcode('n2tsc', 'n2tdreader'); //reading from n2td.org


if ( ! is_user_logged_in() ) {
	add_action('wp_admin_ajax_get_frontpage_calendar', 'frontpage_calendar');
	add_action("wp_ajax_nopriv_frontpage_calendar", "frontpage_calendar"); //frontpage calling ajax
} else {
	add_action('wp_admin_ajax_get_frontpage_calendar', 'frontpage_calendar');
	add_action('wp_ajax_frontpage_calendar', 'frontpage_calendar'); //login user calling ajax
}

function frontpage_calendar() { //showing calendar events using ajax
	//calendar
	if (isset($_GET['calendar'])) {
        echo do_shortcode( "[my_calendar_list calendar=" .  $_GET['calendar'] . " month=" .  $_GET['month'] . " year=" .  $_GET['year'] . " day=" .  $_GET['day'] . "]"  );
	} else { //subscription
				if (filter_input(INPUT_POST, 'subs_email', FILTER_VALIDATE_EMAIL) !== null) {
					//insert record
					$subs_email = filter_input(INPUT_POST, 'subs_email', FILTER_SANITIZE_STRING);
					$subs_first = filter_input(INPUT_POST, 'subs_first', FILTER_SANITIZE_STRING);
					$subs_last = filter_input(INPUT_POST, 'subs_last', FILTER_SANITIZE_STRING);
					  //print "$subs_email - $subs_first - $subs_last";
					 //insert record
						global $wpdb;
						//check if email already exist.
						$result_check = $wpdb->query("SELECT id FROM newsletter WHERE email LIKE '$subs_email' LIMIT 1");
						if ($result_check < 1) {
								$result_insert = $wpdb->query(
								$wpdb->prepare(
									"INSERT INTO newsletter (email, first, last, date) VALUES (%s, %s ,%s, CURDATE())", $subs_email, $subs_first, $subs_last)
							);
						} else {
							echo '<strong>&nbsp;&nbsp;&nbsp;&nbsp;The email <font color="#d13138">' . $subs_email . '</font> is already subscribed to the list.</strong>';
						}
					} else { die("Validation Error."); }
						if ($result_insert) {
							echo '<strong>&nbsp;&nbsp;&nbsp;&nbsp;Thank you for subscribing.</strong>';
						}
		}
	//
    exit(); //need this, or you'll have issues with getting '1' back as a value
}


add_action('wp_head','pluginname_ajaxurl');
function pluginname_ajaxurl() {

echo '<script type="text/javascript">
var ajaxurl = "' .  admin_url('admin-ajax.php') .'"
</script>';
}

function date_compare($a, $b)
{
    $t1 = $a[0];
    $t2 = $b[0];
    return $t1 - $t2;
}



/* https://www.businessbloomer.com/woocommerce-visual-hook-guide-account-pages
https://www.businessbloomer.com/woocommerce-hide-rename-account-tab/ 
https://www.businessbloomer.com/woocommerce-add-new-tab-account-page/ */


add_filter( 'woocommerce_account_menu_items', 'bbloomer_remove_address_my_account', 9999 );
function bbloomer_remove_address_my_account( $items ) {
   unset( $items['downloads'] );
   return $items;
}







 
function add_edit_avatar_endpoint() {
    add_rewrite_endpoint( 'edit-avatar', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'add_edit_avatar_endpoint' );

  
function edit_avatar_query_vars( $vars ) {
    $vars[] = 'edit-avatar';
    return $vars;
}
add_filter( 'query_vars', 'edit_avatar_query_vars', 0 );
  

function add_edit_avatar_link_my_account( $items ) {
    $items['edit-avatar'] = 'Edit Avatar';
    return $items;
}
add_filter( 'woocommerce_account_menu_items', 'add_edit_avatar_link_my_account' );
  

function edit_avatar_content() {
	echo do_shortcode( '[avatar_upload]' );
	echo '</br>';
}

add_action( 'woocommerce_account_edit-avatar_endpoint', 'edit_avatar_content' );



function my_account_menu_order() {
    $menuOrder = array(
		'dashboard'          => __( 'Dashboard', 'woocommerce' ),
        'orders'             => __( 'Your Orders', 'woocommerce' ),
        'edit-address'       => __( 'Addresses', 'woocommerce' ),
		'edit-account'        => __( 'Account Details', 'woocommerce' ),
		'edit-avatar'        => __( 'Edit Avatar', 'woocommerce' ),
        'customer-logout'    => __( 'Logout', 'woocommerce' )
    );
    return $menuOrder;
}
add_filter ( 'woocommerce_account_menu_items', 'my_account_menu_order' );


add_theme_support('woocommerce');

/*
* This will hide the Divi "Project" post type.
*/
add_filter( 'et_project_posttype_args', 'mytheme_et_project_posttype_args', 10, 1 );
function mytheme_et_project_posttype_args( $args ) {
	return array_merge( $args, array(
		'public'              => false,
		'exclude_from_search' => false,
		'publicly_queryable'  => false,
		'show_in_nav_menus'   => false,
		'show_ui'             => false
	));
}





