<?php
/*
Plugin Name: Email newsletter
Plugin URI: http://www.gopiplus.com/work/2010/09/25/email-newsletter/
Description: Sometimes you want an easy way to e-mail all the people who registered, commented on the website, now it's as easy as installing this plug-in. also we have email subscriber option.
Author: Gopi.R
Version: 10.0
Author http://www.gopiplus.com/work/2010/09/25/email-newsletter/
Donate link: http://www.gopiplus.com/work/2010/09/25/email-newsletter/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

global $wpdb, $wp_version;
define("WP_eemail_TABLE", $wpdb->prefix . "eemail_newsletter");
define("WP_eemail_TABLE_SUB", $wpdb->prefix . "eemail_newsletter_sub");
define("WP_eemail_TABLE_SCF", $wpdb->prefix . "gCF");

if ( ! defined( 'EMAIL_PLUGIN_BASENAME' ) )
	define( 'EMAIL_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'EMAIL_PLUGIN_NAME' ) )
	define( 'EMAIL_PLUGIN_NAME', trim( dirname( EMAIL_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'EMAIL_PLUGIN_DIR' ) )
	define( 'EMAIL_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . EMAIL_PLUGIN_NAME );

if ( ! defined( 'EMAIL_PLUGIN_URL' ) )
	define( 'EMAIL_PLUGIN_URL', WP_PLUGIN_URL . '/' . EMAIL_PLUGIN_NAME );

function emailnews_plugin_path( $path = '' ) {
	return path_join( FIFO_PLUGIN_DIR, trim( $path, '/' ) );
}

function emailnews_plugin_url( $path = '' ) {
	return plugins_url( $path, EMAIL_PLUGIN_BASENAME );
}

function eemail_install() 
{
	global $wpdb, $wp_version;
	
	add_option('eemail_title', "Email newsletter");
	add_option('eemail_bcc', "0");
	add_option('eemail_widget_cap', "Subscribe your email");
	add_option('eemail_widget_txt_cap', "Enter email");
	add_option('eemail_widget_but_cap', "Submit");
	
	add_option('eemail_on_homepage', "YES");
	add_option('eemail_on_posts', "YES");
	add_option('eemail_on_pages', "YES");
	add_option('eemail_on_search', "NO");
	add_option('eemail_on_archives', "NO");
	
	add_option('eemail_from_name', "noreply");
	add_option('eemail_from_email', "noreply@mysitename.com");
	
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
		
		$sql = "insert into ".WP_eemail_TABLE.""
					. " set `eemail_subject` = '" . 'Sample Subject'
					. "', `eemail_content` = '" . 'This is sample mail content, Can add HTML content here.'
					. "', `eemail_status` = '" . 'YES'
					. "', `eemail_date` = CURDATE()";
					
		$wpdb->get_results($sql);
	}
	
	if($wpdb->get_var("show tables like '". WP_eemail_TABLE_SUB . "'") != WP_eemail_TABLE_SUB)  
	{
		$wpdb->query("
			CREATE TABLE `". WP_eemail_TABLE_SUB . "` (
				`eemail_id_sub` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
				`eemail_name_sub` VARCHAR( 250 ) NOT NULL ,
				`eemail_email_sub` VARCHAR( 250 ) NOT NULL ,
				`eemail_status_sub` VARCHAR( 3 ) NOT NULL ,
				`eemail_date_sub` DATE NOT NULL )
			");
	}
}

function eemail_admin_option() 
{
	echo "<div class='wrap'>";
	include_once("inc/button.php");
	echo "<h2>"; 
	// Start v 7.0
	//echo "Email newsletter";
	// End v 7.0
	echo "</h2>";
	include_once("inc/help.php");
	echo "</div>";
}

function eemail_deactivation() 
{

}

function eemail_send_mail($recipients = array(), $subject = '', $message = '', $type='plaintext', $sender_name='', $sender_email='') 
{
	
	global $wpdb;
	global $user_login , $user_email;
	
	if($sender_email == "" || $sender_name == '')
	{
        get_currentuserinfo();
		$sender_email = $user_email;
		$sender_name = $user_login;
	}
	
	$eemail_from_name = get_option('eemail_from_name');
	if($eemail_from_name!="")
	{
		$sender_name = $eemail_from_name;
	}
	
	$eemail_from_email = get_option('eemail_from_email');
	if($eemail_from_email!="")
	{
		$sender_email = $eemail_from_email;
	}
	
	$num_sent = 0; // return value
	
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
	$mailtext = str_replace("\r\n", "<br />", $mailtext);
	if(count($recipients) > 0)
	{
		for ($i=0; $i<count($recipients); $i++) 
		{
			@$to = @$recipients[$i];
			if (!eemail_valid_email($to)) 
			{ 
				echo "$to email not valid<br>"; 
				continue; 
			}
			//@$newheaders = $headers . "To: \"" . $to . "\" <" . $to . ">\n" ;
			@wp_mail($to, $subject, $mailtext, $headers);
       		@$num_sent = @$num_sent + 1;
		}
	}
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

function eemail_show() 
{
	global $wpdb, $wp_version;
	include_once("widget/widget.php");
}

function eemail_widget($args) 
{
	
	if(is_home() && get_option('eemail_on_homepage') == 'YES') { $display = "show";	}
	if(is_single() && get_option('eemail_on_posts') == 'YES') {	$display = "show"; }
	if(is_page() && get_option('eemail_on_pages') == 'YES') { $display = "show"; }
	if(is_archive() && get_option('eemail_on_search') == 'YES') { $display = "show"; }
	if(is_search() && get_option('eemail_on_archives') == 'YES') { $display = "show"; }
	if($display == "show")
	{
		extract($args);
		echo $before_widget;
		echo $before_title;
		echo get_option('eemail_title');
		echo $after_title;
		eemail_show();
		echo $after_widget;
	}
}
	
function eemail_control() 
{
	echo "Email newsletter";
}

function eemai_widget_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget("Email newsletter", "Email newsletter", 'eemail_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control("Email newsletter", array("Email newsletter", 'widgets'), 'eemail_control');
	} 
}

function add_admin_menu_email_compose() {
	global $wpdb;
	include_once("email-compose.php");
}

function add_admin_menu_email_to_registered_user() {
	global $wpdb;
	include_once("email-to-registered-user.php");
}

function add_admin_menu_email_to_comment_posed_user() {
	global $wpdb;
	include_once("email-to-comment-posed-user.php");
}

function add_admin_menu_email_to_subscriber() {
	global $wpdb;
	include_once("email-to-subscriber.php");
}

function add_admin_menu_view_subscriber() {
	global $wpdb;
	include_once("view-subscriber.php");
}

function add_admin_menu_widget_option() {
	global $wpdb;
	include_once("widget-option.php");
}

function add_admin_menu_email_to_simple_contact_form() {
	global $wpdb;
	include_once("email-to-simple-contact-form.php");
}

// Start v 7.0
function add_admin_menu_export_csv() {
	//global $wpdb;
	//include_once("export-csv.php");
}
// End v 7.0

// Start v 8.0
function add_admin_menu_import_emails() {
	global $wpdb;
	include_once("import-emails.php");
}
// End v 8.0

function add_admin_menu_option() 
{
	add_menu_page( __( 'Email Newsletter', 'email-newsletter' ), __( 'Email Newsletter', 'email-newsletter' ), 'administrator', 'email-newsletter', 'eemail_admin_option' );
	add_submenu_page('email-newsletter', 'Compose email', 'Compose email', 'administrator', 'add_admin_menu_email_compose', 'add_admin_menu_email_compose');
	add_submenu_page('email-newsletter', 'Send email to registered user', 'Send email to registered user', 'administrator', 'add_admin_menu_email_to_registered_user', 'add_admin_menu_email_to_registered_user');
	add_submenu_page('email-newsletter', 'Send email to comments posed user', 'Send email to comments posed user', 'administrator', 'add_admin_menu_email_to_comment_posed_user', 'add_admin_menu_email_to_comment_posed_user');
	add_submenu_page('email-newsletter', 'Send mail to subscribed user', 'Send mail to subscribed user', 'administrator', 'add_admin_menu_email_to_subscriber', 'add_admin_menu_email_to_subscriber');
	add_submenu_page('email-newsletter', 'Send mail to simple contact form emails', 'Send mail to simple contact form emails', 'administrator', 'add_admin_menu_email_to_simple_contact_form', 'add_admin_menu_email_to_simple_contact_form');
	add_submenu_page('email-newsletter', 'View subscribed user', 'View subscribed user', 'administrator', 'add_admin_menu_view_subscriber', 'add_admin_menu_view_subscriber');
	add_submenu_page('email-newsletter', 'Subscriber form setting', 'Subscriber form setting', 'administrator', 'add_admin_menu_widget_option', 'add_admin_menu_widget_option');
	// Start v 7.0
	//add_submenu_page('email-newsletter', 'Export CSV', 'Export CSV', 'administrator', 'add_admin_menu_export_csv', 'add_admin_menu_export_csv');
	// End v 7.0
	// Start v 8.0
	add_submenu_page('email-newsletter', 'Import emails', 'Import emails', 'administrator', 'add_admin_menu_import_emails', 'add_admin_menu_import_emails');
	// End v 8.0
}

add_action('admin_menu', 'add_admin_menu_option');
register_activation_hook(__FILE__, 'eemail_install');
register_deactivation_hook(__FILE__, 'eemail_deactivation');
add_action("plugins_loaded", "eemai_widget_init");
add_action('init', 'eemai_widget_init');
?>
