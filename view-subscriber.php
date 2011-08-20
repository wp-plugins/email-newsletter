<div class='wrap'>
<h2>View Email Subscriber</h2>
<script language="JavaScript" src="<?php echo emailnews_plugin_url('inc/setting.js'); ?>"></script>
<?php
$DID=@$_GET["DID"];
$AC=@$_GET["AC"];
if($AC=="DEL" && $DID > 0)
{
	$wpdb->get_results("delete from ".WP_eemail_TABLE_SUB." where eemail_id_sub=".$DID);
}
?>
<form name="form_subscriber" method="post" action="admin.php?page=add_admin_menu_view_subscriber" onsubmit="return view_subscriber()"  >
 <table width="100%" class="widefat" id="straymanage">
    <thead>
      <tr>
        <th width="8%" align="left">Sno</th>
        <!--<th width="10%" align="left">Name</th>-->
        <th align="left">Email</th>
        <th width="2%" align="left"></th>
      </tr>
    <thead>
    <tbody>
<?php
$data = $wpdb->get_results("select * from ".WP_eemail_TABLE_SUB." where 1=1");
if ( ! empty($data) ) 
{
  ?>
   <?php 
	$i = 0;
	foreach ( $data as $data ) { 
	?>
	  <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
		<td align="left"><?php echo $i; ?></td>
		<!--<td align="left"><?php //echo(stripslashes($data->eemail_name_sub)); ?></td>-->
		<td align="left"><?php echo(stripslashes($data->eemail_email_sub)); ?></td>
		<td align="left"><a title="Delete" onClick="javascript:_subscriberdealdelete('<?php echo($data->eemail_id_sub); ?>')" href="javascript:void(0);">X</a> </td>
	  </tr>
	  <?php $i = $i+1; } ?>
	</tbody>
  <?php
}
else
{
	
}
?>
</table>
</form>
</div>