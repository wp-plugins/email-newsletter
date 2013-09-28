<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<script language="javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/email-newsletter/pages/pages-setting.js"></script>
<div class="wrap">
  <div class="form-wrap">
    <div id="icon-plugins" class="icon32"></div>
    <h2><?php echo WP_eemail_TITLE; ?></h2>
    <h3>Opt In Page Configuration</h3>
	<?php
	$eemail_opt_option = get_option('eemail_opt_option');
	$eemail_opt_subject = get_option('eemail_opt_subject');
	$eemail_opt_content = get_option('eemail_opt_content');
	$eemail_opt_link = get_option('eemail_opt_link');
	$eemail_msgdis_1 = get_option('eemail_msgdis_1');
	$eemail_msgdis_2 = get_option('eemail_msgdis_2');
	$eemail_msgdis_6 = get_option('eemail_msgdis_6');
	
	if (isset($_POST['eemail_form_submit_opt']) && $_POST['eemail_form_submit_opt'] == 'yes')
	{
		check_admin_referer('eemail_form_opt');
		$eemail_opt_option = stripslashes($_POST['eemail_opt_option']);
		$eemail_opt_subject = stripslashes($_POST['eemail_opt_subject']);
		$eemail_opt_content = stripslashes($_POST['eemail_opt_content']);
		$eemail_opt_link = stripslashes($_POST['eemail_opt_link']);	
		$eemail_msgdis_1 = stripslashes($_POST['eemail_msgdis_1']);	
		$eemail_msgdis_2 = stripslashes($_POST['eemail_msgdis_2']);	
		$eemail_msgdis_6 = stripslashes($_POST['eemail_msgdis_6']);	
		
		update_option('eemail_opt_option', $eemail_opt_option );
		update_option('eemail_opt_subject', $eemail_opt_subject );
		update_option('eemail_opt_content', $eemail_opt_content );
		update_option('eemail_opt_link', $eemail_opt_link );
		update_option('eemail_msgdis_1', $eemail_msgdis_1 );
		update_option('eemail_msgdis_2', $eemail_msgdis_2 );
		update_option('eemail_msgdis_6', $eemail_msgdis_6 );
		?>
		<div class="updated fade">
			<p><strong>Details successfully updated.</strong></p>
		</div>
		<?php
	}
	?>
	<form name="form_eemail" method="post" action="">
	
	<label for="tag-title">Opt-In option</label>
	<select name="eemail_opt_option" id="eemail_opt_option">
		<option value="double-optin" <?php if($eemail_opt_option=='double-optin') { echo 'selected' ; } ?>>Double Opt In</option>
		<option value="single-optin" <?php if($eemail_opt_option=='single-optin') { echo 'selected' ; } ?>>Single Opt In </option>
	</select>
	<p>Double Opt In, means subscribers need to confirm their email address by an activation link sent them on a activation email message. <br />
	Single Opt In, means subscribers do not need to confirm their email address.</p>
	
	<label for="tag-title">Opt-In email subject</label>
	<input name="eemail_opt_subject" id="eemail_opt_subject" type="text" size="120" value="<?php echo $eemail_opt_subject; ?>" />
	<p>Please enter Opt-In email subject</p>
	
	<label for="tag-title">Opt-In email content</label>
	<textarea name="eemail_opt_content" cols="80" rows="5"><?php echo $eemail_opt_content; ?></textarea>
	<p>Please enter Opt-In email content. ##LINK## is a key word.</p>
	
	<label for="tag-title">Opt-In link</label>
	<input name="eemail_opt_link" id="eemail_opt_link" type="text" size="120" value="<?php echo $eemail_opt_link; ?>" />
	<p>Please enter your Opt-In link.</p>
	
	<label for="tag-title">Static message 1</label>
	<textarea name="eemail_msgdis_1" id="eemail_msgdis_1" cols="100" rows="5"><?php echo $eemail_msgdis_1; ?></textarea>
	<p>Static message after Double Opt In confirmation.</p>
	
	<label for="tag-title">Static message2</label>
	<textarea name="eemail_msgdis_2" id="eemail_msgdis_2" cols="100" rows="5"><?php echo $eemail_msgdis_2; ?></textarea>
	<p>Static message in Double Opt In confirmation page, if no email found.</p>
	
	<label for="tag-title">Static message 6</label>
	<textarea name="eemail_msgdis_6" id="eemail_msgdis_6" cols="100" rows="5"><?php echo $eemail_msgdis_6; ?></textarea>
	<p>Static message for unexpected error.</p>
	
	<p style="padding-top:10px;">
		<input type="submit" id="eemail_submit" name="eemail_submit" lang="publish" class="button add-new-h2" value="Update Settings" />
		<input name="publish" lang="publish" class="button add-new-h2" onclick="_eemail_redirect()" value="Cancel" type="button" />
		<input name="Help" lang="publish" class="button add-new-h2" onclick="_eemail_help()" value="Help" type="button" />
	</p>
	<?php wp_nonce_field('eemail_form_opt'); ?>
	<input type="hidden" name="eemail_form_submit_opt" value="yes"/>
	</form>
	</div>
  <p class="description"><?php echo WP_eemail_LINK; ?></p>
</div>