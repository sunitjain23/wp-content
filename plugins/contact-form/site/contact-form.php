<?php  
if(isset($_POST['submit_contact']) && $_POST['submit_contact']!=''){
	submitForm($_POST, $_FILES, get_permalink());
}
?>
<?php if(isset($_GET['query']) &&  $_GET['query'] == 'success') {
	echo '<h2>Thank you for contacting Us.</h2>';
}
else{
?>
<form method="post" enctype="multipart/form-data" id="contact_form">
	<table>
		<tr>
			<td>First Name</td>
			<td><input type="text" name="first_name" required></td>
		</tr>
		<tr>
			<td>Last Name</td>
			<td><input type="text" name="last_name" required></td>
		</tr>
		<tr>
			<td>Email</td>
			<td><input type="email" name="email" class="required" required></td>
		</tr>
		<tr>
			<td>Subject</td>
			<td><input type="text" name="subject" required></td>
		</tr>
		<tr>
			<td>Comments</td>
			<td><textarea name="comments"></textarea></td>
		</tr>
		<tr>
			<td>Image</td>
			<td><input type="file" name="image"></td>
		</tr>
		<tr>
			<td style="text-align: center;" colspan="2">
				<input type="submit" name="submit_contact" value="Send Message">
			</td>
		</tr>
	</table>
	
</form>
<?php } ?>