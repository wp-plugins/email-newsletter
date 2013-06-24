<link rel="stylesheet" type="text/css" href="<?php echo EMAIL_PLUGIN_URL; ?>/inc/admin-css.css" />
<div class='wrap'>
<h2>View Email Subscriber</h2>
<script language="JavaScript" src="<?php echo emailnews_plugin_url('inc/setting.js'); ?>"></script>
<?php
$DID=@$_GET["DID"];
$AC=@$_GET["AC"];

$chk_delete = @$_POST['chk_delete'];
if(!empty($chk_delete))
{
	$count = count($chk_delete);
	for($i=0; $i<$count; $i++)
	{
		$del_id = $chk_delete[$i];
		$sql = "delete FROM ".WP_eemail_TABLE_SUB." WHERE eemail_id_sub=".$del_id." Limit 1";
		$wpdb->get_results($sql);
	}
}
	
if($AC=="DEL" && $DID > 0)
{
	$wpdb->get_results("delete from ".WP_eemail_TABLE_SUB." where eemail_id_sub=".$DID);
}
@$search = @$_GET["Search"];
if(@$search <> "")
{
	$q = "&Search=".@$search;
}
else
{
	$q = "";
}
?>
<div class="emailn-left">
    <div class="emailn-left-widgets">
	<div style="padding-bottom:10px;padding-top:10px;">
		<a class="<?php if(@$search == "A,B,C") { echo "emailn-subcontent"; } else { echo "emailn-content"; } ?>" href="admin.php?page=add_admin_menu_view_subscriber&Search=A,B,C">A,B,C</a>&nbsp;&nbsp;
		<a class="<?php if(@$search == "D,E,F") { echo "emailn-subcontent"; } else { echo "emailn-content"; } ?>" href="admin.php?page=add_admin_menu_view_subscriber&Search=D,E,F">D,E,F</a>&nbsp;&nbsp;
		<a class="<?php if(@$search == "G,H,I") { echo "emailn-subcontent"; } else { echo "emailn-content"; } ?>" href="admin.php?page=add_admin_menu_view_subscriber&Search=G,H,I">G,H,I</a>&nbsp;&nbsp;
		<a class="<?php if(@$search == "J,K,L") { echo "emailn-subcontent"; } else { echo "emailn-content"; } ?>" href="admin.php?page=add_admin_menu_view_subscriber&Search=J,K,L">J,K,L</a>&nbsp;&nbsp;
		<a class="<?php if(@$search == "M,N,O") { echo "emailn-subcontent"; } else { echo "emailn-content"; } ?>" href="admin.php?page=add_admin_menu_view_subscriber&Search=M,N,O">M,N,O</a>&nbsp;&nbsp;
		<a class="<?php if(@$search == "P,Q,R") { echo "emailn-subcontent"; } else { echo "emailn-content"; } ?>" href="admin.php?page=add_admin_menu_view_subscriber&Search=P,Q,R">P,Q,R</a>&nbsp;&nbsp;
		<a class="<?php if(@$search == "S,T,U") { echo "emailn-subcontent"; } else { echo "emailn-content"; } ?>" href="admin.php?page=add_admin_menu_view_subscriber&Search=S,T,U">S,T,U</a>&nbsp;&nbsp;
		<a class="<?php if(@$search == "V,W,X,Y,Z") { echo "emailn-subcontent"; } else { echo "emailn-content"; } ?>" href="admin.php?page=add_admin_menu_view_subscriber&Search=V,W,X,Y,Z">V,W,X,Y,Z</a>
	</div>
<form name="form_subscriber" method="post" action="admin.php?page=add_admin_menu_view_subscriber<?php echo @$q; ?>" onsubmit="return _subscribermultipledelete()"  >
 <table width="100%" class="widefat" id="straymanage">
    <thead>
      <tr>
	  	<th width="2%" align="left"></th>
        <th width="8%" align="left">Sno</th>
        <th width="8%" align="left">Rand</th>
        <th align="left">Email</th>
        <th width="2%" align="left"></th>
      </tr>
    <thead>
    <tbody>
<?php
$sSql = "select * from ".WP_eemail_TABLE_SUB." where 1=1"; 
if(@$search <> "")
{
	$array = explode(',', @$search);
	$length = count($array);
	for ($i = 0; $i < $length; $i++) 
	{
		if(@$i == 0)
		{
			$sSql = $sSql . " and";
		}
		else
		{
			$sSql = $sSql . " or";
		}
		$sSql = $sSql . " eemail_email_sub LIKE '" . $array[$i]. "%'";
	}
}
$sSql = $sSql . " ORDER BY eemail_id_sub";
$data = $wpdb->get_results($sSql);
if ( ! empty($data) ) 
{
  ?>
   <?php 
	$i = 1;
	foreach ( $data as $data ) { 
	?>
	  <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
	  	<td align="left"><input name="chk_delete[]" id="chk_delete[]" type="checkbox" value="<?php echo(stripslashes($data->eemail_id_sub)); ?>" /></td>
		<td align="left"><?php echo $i; ?></td>
		<td align="left"><?php echo(stripslashes($data->eemail_id_sub)); ?></td>
		<td align="left"><?php echo(stripslashes($data->eemail_email_sub)); ?></td>
		<td align="left"><a title="Delete" onClick="javascript:_subscriberdealdelete('<?php echo($data->eemail_id_sub); ?>')" href="javascript:void(0);">X</a> </td>
	  </tr>
	  <?php $i = $i+1; } ?>
	</tbody>
  <?php
}
else
{
	?>
	 <tr><td colspan="5">No data found</td></tr>
	<?php
}
?>
</table>
<br />
<input name="searchquery" id="searchquery" type="hidden" value="<?php echo @$search; ?>" />
<input class="button-primary"  name="multidelete" type="submit" id="multidelete" value="Delete Multiple Records">
</form>
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