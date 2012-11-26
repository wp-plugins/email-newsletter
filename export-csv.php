<link rel="stylesheet" type="text/css" href="<?php echo EMAIL_PLUGIN_URL; ?>/inc/admin-css.css" />
<script language="JavaScript" src="<?php echo emailnews_plugin_url('inc/setting.js'); ?>"></script>
<div class='wrap'>
<?php
if (!session_id())
{
    session_start();
}
$_SESSION['exportcsv'] = "YES"; 
?>
<div class="wrap">
  <h2>Email Newsletter</h2>
  <h3>Export the email newsletter plugin data in CSV format</h3>
  <div class="emailn-left">
    <div class="emailn-left-widgets">
      <form name="frm_emailnewsletter" method="post">
        <table width="100%" class="widefat" id="straymanage">
          <thead>
            <tr>
              <th class="manage-column">No</th>
              <th class="manage-column">Export Option</th>
              <th class="manage-column">Action</th>
            </tr>
          </thead>
          <tr>
            <td>1</td>
            <td>Subscriber emails</td>
            <td><a onClick="javascript:exportcsv('<?php echo emailnews_plugin_url('csv/export.php'); ?>', 'view_subscriber')" href="javascript:void(0);">Export CSV</a> </td>
          </tr>
          <tr>
            <td>2</td>
            <td>Registered emails</td>
            <td><a onClick="javascript:exportcsv('<?php echo emailnews_plugin_url('csv/export.php'); ?>', 'registered_user')" href="javascript:void(0);">Export CSV</a> </td>
          </tr>
          <tr>
            <td>3</td>
            <td>Comment posed emails</td>
            <td><a onClick="javascript:exportcsv('<?php echo emailnews_plugin_url('csv/export.php'); ?>', 'commentposed_user')" href="javascript:void(0);">Export CSV</a> </td>
          </tr>
          <tr>
            <td>4</td>
            <td>Simple contact form email</td>
            <td><a onClick="javascript:exportcsv('<?php echo emailnews_plugin_url('csv/export.php'); ?>', 'contact_user')" href="javascript:void(0);">Export CSV</a> </td>
          </tr>
        </table>
      </form>
    </div>
  </div>
  <div class="emailn-right">
    <div class="emailn-widgets">
      <h3>About</h3>
      <div class="emailn-widgets-desc"> Email newsletter plugin have option to send HTML Mails/Newsletters to users. <a href="http://www.gopipulse.com/work/2010/09/25/email-newsletter/" target="_blank">click here</a> to view more information. </div>
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
