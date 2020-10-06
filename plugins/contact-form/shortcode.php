<?php  
defined('ABSPATH') or die('');

function showContactForm(){
	ob_start();
	require_once GLX_CONTACT_DIR.'site/contact-form.php';
	return ob_get_clean();
}

add_shortcode('contact_form', 'showContactForm');

?>