<link rel="stylesheet" type="text/css" href="<?php echo EMAIL_PLUGIN_URL; ?>/inc/admin-css.css" /><div class='wrap'><div class="wrap">
  <h2>Email Newsletter</h2>
  <h3>Import email address to subscription user list</h3>
  <div class="emailn-left">
    <div class="emailn-left-widgets">
	<?php
	$result = "";
	if(trim(@$_POST['importemails_submit']) == "importemails_submit" and trim(@$_POST['importemails']) <>"")
    {
		$emaillist = trim(@$_POST['importemails']);
		$arrayemail = explode(',', $emaillist);
		$inserted = 0;
		$duplicate = 0;
		for ($i = 0; $i < count($arrayemail); $i++)
		{
			$cSql = "select * from ".WP_eemail_TABLE_SUB." where eemail_email_sub='" . trim($arrayemail[$i]). "'";
			$data = $wpdb->get_results($cSql);
			if ( empty($data) ) 
			{
				$sql = "insert into ".WP_eemail_TABLE_SUB.""
					. " set `eemail_name_sub` = 'NONAME"
					. "', `eemail_email_sub` = '" . trim($arrayemail[$i])
					. "', `eemail_status_sub` = 'YES"
					. "', `eemail_date_sub` = CURDATE()";
				
				$wpdb->get_results($sql);
				$inserted = $inserted + 1;
			}
			else
			{
				$duplicate = $duplicate + 1;
			}
		}
		$result = $inserted . " Email address inserted successfully.<br />";
		$result = $result . " " . $duplicate . " Email address already exists.<br />";
		$result = $result . "Click <b>View subscribed user</b> menu to view the results.";
	}
	?>
	Enter the email address with comma separated (No comma at the end).<br />
	<script language="JavaScript" src="<?php echo emailnews_plugin_url('inc/setting.js'); ?>"></script>
	<form name="form_importemails" method="post" action="<?php echo get_option('siteurl')."/wp-admin/admin.php?page=add_admin_menu_import_emails"; ?>" onsubmit="return eemail_import()"  >
	<textarea name="importemails" cols="100" rows="10"></textarea><br /><br />
	<input name="publish" lang="publish" class="button-primary" value="submit" type="submit" />
	<input name="importemails_submit" id="importemails_submit" type="hidden" value="importemails_submit">&nbsp;&nbsp;&nbsp;
	<input name="Help" lang="publish" class="button-primary" onclick="window.open('http://www.gopiplus.com/work/2010/09/25/email-newsletter/');" value="Help" type="button" />
	</form><br />
	<p class="updated" style="color:#F00"><?php echo $result; ?></p>
	<b>Note:</b><br />
	Please enter your email address with comma separated.<br />
	Please enter maximum 25 email address at one time.<br />
	Comma not allowed at the end of the string.<br />
	<br />
	<b>Example</b><br />
	<b>Wrong format</b><br />
	Example 1 (Comma at the end of the string)<br />
	admin@admin.com,admin@admin1.com,<br />
	Example 2 (Two comma)<br />
	admin@admin.com,,admin@admin1.com<br />
	<br />
	<b>Correct format</b><br />
	admin@admin.com,admin@admin1.com<br />

    </div>
  </div>
  <div class="emailn-right">
    <div class="emailn-widgets">
      <h3>About</h3>
      <div class="emailn-widgets-desc"> 
	  Email newsletter plugin have option to send HTML Mails/Newsletters to users. <a href="http://www.gopiplus.com/work/2010/09/25/email-newsletter/" target="_blank">click here</a> to view more information.	  </div>
    </div>
	<div class="emailn-widgets">
      <h3>Options</h3>
	  <div class="emailn-widgets-desc">
	  <ul>
	  	<li>Compose email</li>
		<li>Send mail to registered user</li>
		<li>Send mail to comment posted user</li>
		<li>Send mail to subscribed user</li>
		<li>Send mail to simple contact form emails</li>
		<li>Export CSV file</li>
		<li>Import emails</li>
	  </ul>
	  </div>
    </div>
  </div>
</div>
