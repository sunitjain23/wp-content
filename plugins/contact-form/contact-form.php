<?php
/*
Plugin Name: Glx Contact Form
Plugin URI: http://localhost/galaxy_test
Description: Contact Form Plugin.
Author: Sunit Jain
Version: 1.0.0
Author URI: http://localhost/galaxy_test
*/
ob_start();
defined('ABSPATH') or die('');

define('GLX_CONTACT_URL', plugin_dir_url(__FILE__));
define('GLX_CONTACT_DIR', plugin_dir_path(__FILE__));

require_once GLX_CONTACT_DIR.'shortcode.php';
require_once GLX_CONTACT_DIR.'class-table-contact.php';
require_once GLX_CONTACT_DIR.'functions.php';

function glxCreateMenu(){
	add_menu_page('Contacts', 'Contacts', 'manage_options', 'contacts', 'contactShow','',20);
}
add_action('admin_menu', 'glxCreateMenu');

function glxSetupPlugin(){
	global $wpdb;
	$table_name = $wpdb->prefix.'glx_contact';
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			first_name varchar(255),
			last_name varchar(255),
			email varchar(255),
			subject varchar(255),
			image varchar(255),
			comments text,
			date datetime,
			PRIMARY KEY  (id)
		) $charset_collate;";
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

}
register_activation_hook( __FILE__, 'glxSetupPlugin' );

function my_add_menu_items(){
	$hook = add_menu_page( 'Contacts', 'Contacts', 'manage_options', 'contacts', 'contactShow','',20 );
	add_action( "load-$hook", 'add_options' );
}

function add_options() {
	global $myListTable;
	$option = 'per_page';
	/*$args = array(
	     'label' => 'Books',
	     'default' => 10,
	     'option' => 'books_per_page'
	     );
	add_screen_option( $option, $args );*/
	$myListTable = new Contact_List_Table();
}
add_action( 'admin_menu', 'my_add_menu_items' );



function contactShow(){
	global $myListTable; ?>
	<div class="wrap">
		<h2>My List Table Test</h2>
		<?php $myListTable->prepare_items(); ?>
		<form method="get">
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
			<?php
				//$myListTable->search_box( 'search', 'search_id' );
				$myListTable->display(); 
			?>
		</form>
	</div> 
<?php } ?>