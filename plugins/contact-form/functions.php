<?php 
function submitForm($data, $file, $redirect){
	ob_start();
	global $wpdb;
	$table_name = $wpdb->prefix.'glx_contact';

	$upload = wp_upload_dir();

	$up_path = $upload['path'];
	$up_url = $upload['url'];

	$tmp_name = $_FILES["image"]["tmp_name"];
    $name = pathinfo($file['image']['name'], PATHINFO_FILENAME);

    $pathinfo = pathinfo($file['image']['name']);
    $extension = $pathinfo['extension'];

    $main_name = $name.'_'.time().'.'.$extension;

    move_uploaded_file($tmp_name, "$up_path/$main_name");

    $image_url = $up_url.'/'.$main_name;

    $first_name = $data['first_name'];
    $last_name = $data['last_name'];
    $email = $data['email'];
    $subject = $data['subject'];
    $comments = nl2br($data['comments']);

    $wpdb->insert(
    	$table_name,
    	array(
    		'first_name' => $first_name, 
    		'last_name' => $last_name, 
    		'email' => $email, 
    		'subject' => $subject, 
    		'image' => $image_url, 
    		'comments' => $comments, 
    		'date' => date('Y-m-d h:i:s'), 
    	)
    );

    wp_redirect($redirect.'?query=success');
}

?>