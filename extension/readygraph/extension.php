<?php
// ReadyGraph Extension
//
if(!function_exists('append_submenu_page')) {
function append_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function = '' ) {
	global $submenu;
	global $menu;
	global $_wp_real_parent_file;
	global $_wp_submenu_nopriv;
	global $_registered_pages;
	global $_parent_pages;

	$menu_slug = plugin_basename( $menu_slug );
	$parent_slug = plugin_basename( $parent_slug);

	if ( isset( $_wp_real_parent_file[$parent_slug] ) )
		$parent_slug = $_wp_real_parent_file[$parent_slug];

	if ( !current_user_can( $capability ) ) {
		$_wp_submenu_nopriv[$parent_slug][$menu_slug] = true;
		return false;
	}

	// If the parent doesn't already have a submenu, add a link to the parent
	// as the first item in the submenu. If the submenu file is the same as the
	// parent file someone is trying to link back to the parent manually. In
	// this case, don't automatically add a link back to avoid duplication.
	if (!isset( $submenu[$parent_slug] ) && $menu_slug != $parent_slug ) {
		foreach ( (array)$menu as $parent_menu ) {
			if ( $parent_menu[2] == $parent_slug && current_user_can( $parent_menu[1] ) )
				$submenu[$parent_slug][] = $parent_menu;
		}
	}

	$mymenu = array();
	$mymenu[] = array($menu_title, $capability, $menu_slug, $page_title);
	$submenu[$parent_slug] = array_merge($mymenu, $submenu[$parent_slug]);

	$hookname = get_plugin_page_hookname( $menu_slug, $parent_slug);
	if (!empty ( $function ) && !empty ( $hookname ))
		add_action( $hookname, $function );
    
	$_registered_pages[$hookname] = true;
	// backwards-compatibility for plugins using add_management page. See wp-admin/admin.php for redirect from edit.php to tools.php
	if ( 'tools.php' == $parent_slug )
		$_registered_pages[get_plugin_page_hookname( $menu_slug, 'edit.php')] = true;

	// No parent as top level
	$_parent_pages[$menu_slug] = $parent_slug;

	return $hookname;
}
}
function ee_readygraph_client_script_head() {
	if(function_exists('s2_readygraph_client_script_head'))  return;
	if(function_exists('gCF_readygraph_client_script_head'))  return;
	if(!get_option('readygraph_application_id') && strlen(get_option('readygraph_application_id')) < 0) return;
	if (get_option('readygraph_enable_branding', '') == 'false') {
		echo '<style>/* FOR INLINE WIDGET */.rgw-text {display: none !important;}</style>';
	}
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	?>	
<script type='text/javascript'>
var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
var d = top.document;
var h = d.getElementsByTagName('head')[0], script = d.createElement('script');
script.type = 'text/javascript';
script.src = '//cdn.readygraph.com/scripts/readygraph.js';
script.onload = function(e) {
  var settings = <?php echo str_replace("\\\"", "\"", get_option('readygraph_settings', '{}')) ?>;
  settings['applicationId'] = '<?php echo get_option('readygraph_application_id', '') ?>';
  settings['overrideFacebookSDK'] = true;
  settings['platform'] = 'others';
  settings['enableLoginWall'] = <?php echo get_option('readygraph_enable_popup', 'false') ?>;
  settings['enableSidebar'] = <?php echo get_option('readygraph_enable_sidebar', 'false') ?>;
	settings['inviteFlowDelay'] = <?php echo get_option('readygraph_delay', '5000') ?>;
	settings['enableNotification'] = <?php echo get_option('readygraph_enable_notification', 'false') ?>;
	settings['inviteAutoSelectAll'] = <?php echo get_option('readygraph_auto_select_all', 'true') ?>;
	top.readygraph.setup(settings);
	readygraph.ready(function() {
		readygraph.framework.require(['auth', 'invite', 'compact.sdk'], function() {
			function process(userInfo) {
				var rg_email = userInfo.get('email');
				var first_name = userInfo.get('first_name');
				var last_name = userInfo.get('last_name');
				<?php if ( is_plugin_active( 'subscribe2/subscribe2.php' ) ) { ?>
				jQuery.post(ajaxurl,
				{
					action : 's2-myajax-submit',
					email : rg_email
				},function(){});
				<?php } ?>
				<?php if ( is_plugin_active( 'email-newsletter/email-newsletter.php' ) ) { ?>
				jQuery.post(ajaxurl,
				{
					action : 'ee-myajax-submit',
					email : rg_email
				},function(){});
				<?php } ?>
				<?php if ( is_plugin_active( 'simple-subscribe/SimpleSubsribe.php' ) ) { ?>
				jQuery.post(ajaxurl,
				{
					action : 'ss-myajax-submit',
					email : rg_email,
					fname : first_name,
					lname : last_name
				},function(){});
				<?php } ?>
				<?php if ( is_plugin_active( 'simple-contact-form/simple-contact-form.php' ) ) { ?>
				jQuery.post(ajaxurl,
				{
					action : 'gCF-myajax-submit',
					email : rg_email,
					name : first_name
				},function(){});
				<?php } ?>
				}
			readygraph.framework.authentication.getUserInfo(function(userInfo) {
				if (userInfo.get('uid') != null) {
					process(userInfo);
				}
				else {
					userInfo.on('change:fb_access_token change:rg_access_token', function() {
						readygraph.framework.authentication.getUserInfo(function(userInfo) {
							process(userInfo);
						});
					});
				}
			}, true);
		});
	});
}
h.appendChild(script);
</script>
<?php } ?>