<div class="wrap">
  <?php
  	include_once("inc/button.php");
  	global $wpdb;
    $mainurl = get_option('siteurl')."/wp-admin/admin.php?page=add_admin_menu_email_compose";
    $DID=@$_GET["DID"];
    $AC=@$_GET["AC"];
    $submittext = "Insert Message";
	if($AC <> "DEL" and trim(@$_POST['eemail_subject']) <>"")
    {
			if($_POST['eemail_id'] == "" )
			{
					$sql = "insert into ".WP_eemail_TABLE.""
					. " set `eemail_subject` = '" . mysql_real_escape_string(trim($_POST['eemail_subject']))
					. "', `eemail_content` = '" . mysql_real_escape_string(trim($_POST['eemail_content']))
					. "', `eemail_status` = '" . mysql_real_escape_string(trim($_POST['eemail_status']))
					. "', `eemail_date` = CURDATE()";
			}
			else
			{
					$sql = "update ".WP_eemail_TABLE.""
					. " set `eemail_subject` = '" . mysql_real_escape_string(trim($_POST['eemail_subject']))
					. "', `eemail_content` = '" . mysql_real_escape_string(trim($_POST['eemail_content']))
					. "', `eemail_status` = '" . mysql_real_escape_string(trim($_POST['eemail_status']))
					. "' where `eemail_id` = '" . $_POST['eemail_id'] 
					. "'";	
			}

			$wpdb->get_results($sql);
    }
    
    if($AC=="DEL" && $DID > 0)
    {
        $wpdb->get_results("delete from ".WP_eemail_TABLE." where eemail_id=".$DID);
    }
    
    if($DID<>"" and $AC <> "DEL")
    {
        $data = $wpdb->get_results("select * from ".WP_eemail_TABLE." where eemail_id=$DID limit 1");
        if ( empty($data) ) 
        {
           echo "<div id='message' class='error'><p>No data available! use below form to create!</p></div>";
           return;
        }
        $data = $data[0];
        if ( !empty($data) ) $eemail_id_x = htmlspecialchars(stripslashes($data->eemail_id)); 
		if ( !empty($data) ) $eemail_subject_x = htmlspecialchars(stripslashes($data->eemail_subject)); 
        if ( !empty($data) ) $eemail_content_x = htmlspecialchars(stripslashes($data->eemail_content));
		if ( !empty($data) ) $eemail_status_x = htmlspecialchars(stripslashes($data->eemail_status));
        $submittext = "Update Message";
    }
    ?>
  <h2>Email newsletter (Compose email)</h2>
  <script language="JavaScript" src="<?php echo emailnews_plugin_url('inc/setting.js'); ?>"></script>
  <form name="form_eemail" method="post" action="<?php echo @$mainurl; ?>" onsubmit="return eemail_submit()"  >
    <table width="100%">
      <tr>
        <td colspan="2" align="left" valign="middle">Enter email subject:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="eemail_subject" type="text" id="eemail_subject" value="<?php echo @$eemail_subject_x; ?>" size="90" /></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Enter email body (You use HTML content)</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><textarea name="eemail_content" cols="140" rows="25" id="eemail_content"><?php echo @$eemail_content_x; ?></textarea></td>
      </tr>
      <tr>
        <td align="left" valign="middle">Display Status:</td>
        <td align="left" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td width="22%" align="left" valign="middle"><select name="eemail_status" id="eemail_status">
            <option value="">Select</option>
            <option value='YES' <?php if(@$eemail_status_x=='YES') { echo 'selected' ; } ?>>Yes</option>
            <option value='NO' <?php if(@$eemail_status_x=='NO') { echo 'selected' ; } ?>>No</option>
          </select>        </td>
        <td width="78%" align="left" valign="middle"></td>
      </tr>
      <tr>
        <td height="35" colspan="2" align="left" valign="bottom"><table width="100%">
            <tr>
              <td width="50%" align="left">
			  	<input name="publish" lang="publish" class="button-primary" value="<?php echo @$submittext?>" type="submit" />
                <input name="publish" lang="publish" class="button-primary" onclick="_eemail_redirect()" value="Cancel" type="button" />              
			  </td>
              <td width="50%" align="right">&nbsp;</td>
            </tr>
          </table></td>
      </tr>
      <input name="eemail_id" id="eemail_id" type="hidden" value="<?php echo @$eemail_id_x; ?>">
    </table>
  </form>
  <div class="tool-box">
    <?php
	$data = $wpdb->get_results("select * from ".WP_eemail_TABLE." order by eemail_id desc");
	if ( empty($data) ) 
	{ 
		echo "<div id='message' class='error'>No data available! use below form to create!</div>";
		return;
	}
	?>
    <form name="frm_eemail_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th width="77%" align="left" scope="col">Subject
              </td>
            <th width="9%" align="left" scope="col">Status
            <th width="14%" align="left" scope="col">Action
          </td>          </tr>
        </thead>
        <?php 
        $i = 0;
        foreach ( $data as $data ) { 
		if($data->eemail_status=='YES') { $displayisthere="True"; }
        ?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle"><?php echo(stripslashes($data->eemail_subject)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->eemail_status)); ?></td>
			<td align="left" valign="middle"><a href="admin.php?page=add_admin_menu_email_compose&DID=<?php echo($data->eemail_id); ?>">Edit</a> &nbsp; <a onClick="javascript:_eemail_delete('<?php echo($data->eemail_id); ?>')" href="javascript:void(0);">Delete</a> </td>
          </tr>
        </tbody>
        <?php $i = $i+1; } ?>
        <?php if($displayisthere<>"True") { ?>
        <tr>
          <td colspan="3" align="center" style="color:#FF0000" valign="middle">No message available with display status 'Yes'!' </td>
        </tr>
        <?php } ?>
      </table>
    </form>
  </div>
</div>
