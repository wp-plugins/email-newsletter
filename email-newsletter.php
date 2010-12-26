<?php
/*
Plugin Name: Email newsletter
Plugin URI: http://www.gopiplus.com/work/2010/09/25/email-newsletter/
Description: Sometimes you want an easy way to e-mail all the people who registered, commented on the website, now it's as easy as installing this plug-in.
Author: Gopi.R
Version: 2.0
Author http://www.gopiplus.com/work/2010/09/25/email-newsletter/
Donate link: http://www.gopiplus.com/work/2010/09/25/email-newsletter/
*/

global $wpdb, $wp_version;
define("WP_eemail_TABLE", $wpdb->prefix . "eemail_newsletter");

function eemail_show() 
{
	global $wpdb, $wp_version;
}

function eemail_install() 
{
	global $wpdb, $wp_version;
	
	add_option('eemail_title', "Email newsletter");
	add_option('eemail_bcc', "0");
	
	if($wpdb->get_var("show tables like '". WP_eemail_TABLE . "'") != WP_eemail_TABLE)  
	{
		$wpdb->query("
			CREATE TABLE IF NOT EXISTS `". WP_eemail_TABLE . "` (
			  `eemail_id` int(11) NOT NULL auto_increment,
			  `eemail_subject` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
			  `eemail_content` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,
			  `eemail_status` char(3) NOT NULL default 'YES',
			  `eemail_date` datetime NOT NULL default '0000-00-00 00:00:00',
			  PRIMARY KEY  (`eemail_id`) )
			");
	}
}

function eemail_admin_option() 
{
	echo "<div class='wrap'>";
	?>
	<br>
	<table width="100%">
		<tr>
		  <td align="center">
			<input name="btn_plugin_home" lang="btn_plugin_home" class="button-primary" onClick="location.href='options-general.php?page=email-newsletter/email-newsletter.php'" value="Plugin home" type="button" />
			<input name="btn_compose_email_page" lang="btn_compose_email_page" class="button-primary" onClick="location.href='options-general.php?page=email-newsletter/email-compose.php'" value="Compose email" type="button" />
			<input name="btn_send_email_registered_user" lang="btn_send_email_registered_user" class="button-primary" onClick="location.href='options-general.php?page=email-newsletter/email-to-registered-user.php'" value="Send email to registered user" type="button" />
			<input name="btn_send_email_comment_posted_user" lang="btn_send_email_comment_posted_user" class="button-primary" onClick="location.href='options-general.php?page=email-newsletter/email-to-comment-posed-user.php'" value="Send email to comment posed user" type="button" />
		  </td>
		</tr>
	</table>
	<?php
	echo "<h2>"; 
	echo wp_specialchars( "Email newsletter" ) ;
	echo "</h2>";
	include_once("help.php");
	echo "</div>";
}

function eemail_deactivation() 
{

}

function eemail_send_mail($recipients = array(), $subject = '', $message = '', $type='plaintext', $sender_name='', $sender_email='') 
{
	$num_sent = 0; // return value
	//print_r($recipients);
	if ( (empty($recipients)) ) { return $num_sent; }
	if ('' == $message) { return false; }

	$headers  = "From: \"$sender_name\" <$sender_email>\n";
	$headers .= "Return-Path: <" . $sender_email . ">\n";
	$headers .= "Reply-To: \"" . $sender_name . "\" <" . $sender_email . ">\n";
	$headers .= "X-Mailer: PHP" . phpversion() . "\n";

	$subject = stripslashes($subject);
	$message = stripslashes($message);

	if ('html' == $type) {
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: " . get_bloginfo('html_type') . "; charset=\"". get_bloginfo('charset') . "\"\n";
		$headers .= "Content-type: text/html\r\n"; 
		$mailtext = "<html><head><title>" . $subject . "</title></head><body>" . $message . "</body></html>";
	} else {
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-Type: text/plain; charset=\"". get_bloginfo('charset') . "\"\n";
		$message = preg_replace('|&[^a][^m][^p].{0,3};|', '', $message);
		$message = preg_replace('|&amp;|', '&', $message);
		$mailtext = wordwrap(strip_tags($message), 80, "\n");
	}


	// If unique recipient, send mail using to field.
	//--
	if (count($recipients)==1) 
	{
		
		if (eemail_valid_email($recipients[0]))//->user_email)) 
		{
			$headers .= "To: \"" . $recipients[0] . "\" <" . $recipients[0] . ">\n";
			$headers .= "Cc: " . $sender_email . "\n\n";
			@wp_mail($sender_email, $subject, $mailtext, $headers);
			
			$num_sent++;
		} 
		else 
		{
			echo "<p class=\"error\">The email address of the user you are trying to send mail to is not a valid email address format.</p>";
			return $num_sent;
		}
		return $num_sent;
	}
	
	
		// If multiple recipients, use the BCC field
	//--
	$bcc = '';
	$bcc_limit = eemail_get_max_bcc_recipients();
	
	if ( $bcc_limit>0 && (count($recipients)>$bcc_limit) ) 
	{
		$count = 0;
		$sender_emailed = false;

		for ($i=0; $i<count($recipients); $i++) 
		{
			$recipient = $recipients[$i]->user_email;

			if (!eemail_valid_email($recipient)) { continue; }
			if ( empty($recipient) || ($sender_email == $recipient) ) { continue; }

			if ($bcc=='') {
				$bcc = "Bcc: $recipient";
			} else {
				$bcc .= ", $recipient";
			}

			$count++;

			if (($bcc_limit == $count) || ($i==count($recipients)-1)) {
				if (!$sender_emailed) {
					$newheaders = $headers . "To: \"" . $sender_name . "\" <" . $sender_email . ">\n" . "$bcc\n\n";
					$sender_emailed = true;
				} else {
					$newheaders = $headers . "$bcc\n\n";
				}
				@wp_mail($sender_email, $subject, $mailtext, $newheaders);
				
				$count = 0;
				$bcc = '';
			}

			$num_sent++;
		}
	} 
	else 
	{
		$headers .= "To: \"" . $sender_name . "\" <" . $sender_email . ">\n";
		for ($i=0; $i<count($recipients); $i++) 
		{
			$recipient = $recipients[$i];//->user_email;
			if (!eemail_valid_email($recipient)) { echo "$recipient email not valid<br>"; continue; }
			if ( empty($recipient) || ($sender_email == $recipient) ) { continue; }

			if ($bcc=='') {
				$bcc = "Bcc: $recipient";
			} else {
				$bcc .= ", $recipient";
			}
			$num_sent++;
		}
		$newheaders = $headers . "$bcc\n\n";
		@wp_mail($sender_email, $subject, $mailtext, $newheaders);
		//echo "-1-" . $sender_email;
	}
	//echo $newheaders;
	return $num_sent;
}

function eemail_valid_email($email) {
   $regex = '/^[A-z0-9][\w.+-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/';
   return (preg_match($regex, $email));
}

function eemail_get_max_bcc_recipients() {
	return get_option( 'eemail_bcc' );
}

function eemail_get_email_content($eemail_id) 
{
	global $wpdb;
	$emailrecord = array();
	$data = $wpdb->get_results("select eemail_subject,eemail_content from ".WP_eemail_TABLE." where eemail_id=$eemail_id limit 1");
	if ( !empty($data) ) 
	{
		$data = $data[0];
		$emailrecord["eemail_subject"] = $data->eemail_subject;
		$emailrecord["eemail_content"] = $data->eemail_content;
	}
	return $emailrecord;
}

function eemail_add_to_menu() 
{
	//add_options_page('Email newsletter', 'Email newsletter', 7, __FILE__, 'eemail_admin_option' );
	//add_options_page('Email newsletter', '', 0, "email-newsletter/email-compose.php",'' );
	//add_options_page('Email newsletter', '', 0, "email-newsletter/email-to-registered-user.php",'' );
	//add_options_page('Email newsletter', '', 0, "email-newsletter/email-to-comment-posed-user.php",'' );
	
	add_options_page('Email newsletter', 'Email newsletter', 'manage_options', __FILE__, 'eemail_admin_option' );
	add_options_page('Email newsletter', '', 'manage_options', 'email-newsletter/email-compose.php','' );
	add_options_page('Email newsletter', '', 'manage_options', 'email-newsletter/email-to-registered-user.php','' );
	add_options_page('Email newsletter', '', 'manage_options', 'email-newsletter/email-to-comment-posed-user.php','' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'eemail_add_to_menu');
}

add_action('admin_menu', 'eemail_add_to_menu');
register_activation_hook(__FILE__, 'eemail_install');
register_deactivation_hook(__FILE__, 'eemail_deactivation');
?>
