<?PHP
// create custom plugin settings menu
add_action('admin_menu', 'wpsecureops_bruteforce_protect_create_menu');

function wpsecureops_bruteforce_protect_create_menu()
{

    //create new top-level menu
        add_submenu_page('options-general.php', 'WPSecureOps Brute Force Protect', 'WPSecureOps Brute Force Protect', 'administrator', __FILE__, 'wpsecureops_bruteforce_protect_settings_page');

    //call register settings function
    add_action('admin_init', 'wpsecureops_bruteforce_protect_register_settings');
    add_action('admin_init', 'wpsecureops_bruteforce_protect_is_save_triggered');
}

function wpsecureops_bruteforce_protect_register_settings()
{
    register_setting('wpsecureops-bruteforce-protect-settings-group', 'wpsecureops_bruteforce_protect_max_attempts');
    register_setting('wpsecureops-bruteforce-protect-settings-group', 'wpsecureops_bruteforce_protect_lock_duration');
    register_setting('wpsecureops-bruteforce-protect-settings-group', 'wpsecureops_bruteforce_protect_notify_email');
}

function wpsecureops_bruteforce_protect_is_save_triggered()
{
    if (isset($_GET['settings-updated']) && $_GET['settings-updated'] === 'true' && isset($_GET['page']) && $_GET['page'] === "wpsecureops-bruteforce-protect/" . basename(__FILE__)) {
        do_action("wpsecureops-bruteforce-protect/settings-updated");
    }
}
function wpsecureops_bruteforce_protect_get_max_attempts()
{
    $v = get_option('wpsecureops_bruteforce_protect_max_attempts');
    if (!$v) {
        return 5;
    } else {
        return $v;
    }
}
function wpsecureops_bruteforce_protect_get_lock_duration()
{
    $v = get_option('wpsecureops_bruteforce_protect_lock_duration');
    if (!$v) {
        return 3600;
    } else {
        return $v;
    }
}
function wpsecureops_bruteforce_protect_get_notify_email()
{
    $v = get_option('wpsecureops_bruteforce_protect_notify_email');
    if (!$v) {
        return get_option('admin_email');
    } else {
        return $v;
    }
}

function wpsecureops_bruteforce_protect_settings_page()
{
    ?>
	<div class="wrap">
		<h2>WPSecureOps Brute Force Protect Settings</h2>

		<form method="post" action="options.php">
			<?php settings_fields('wpsecureops-bruteforce-protect-settings-group');
    ?>
			<?php do_settings_sections('wpsecureops-bruteforce-protect-settings-group');
    ?>
			<table class="form-table">
								<tr valign="top">
					<th scope="row"><?php echo __('Max attempts', 'wpsecureops-bruteforce-protect') ?></th>
					<td><input type="text" name="wpsecureops_bruteforce_protect_max_attempts" value="<?php echo esc_attr(wpsecureops_bruteforce_protect_get_max_attempts());
    ?>" /></td>
				</tr>
								<tr valign="top">
					<th scope="row"><?php echo __('Lock duration (seconds)', 'wpsecureops-bruteforce-protect') ?></th>
					<td><input type="text" name="wpsecureops_bruteforce_protect_lock_duration" value="<?php echo esc_attr(wpsecureops_bruteforce_protect_get_lock_duration());
    ?>" /></td>
				</tr>
								<tr valign="top">
					<th scope="row"><?php echo __('Notification email (optional)', 'wpsecureops-bruteforce-protect') ?></th>
					<td><input type="text" name="wpsecureops_bruteforce_protect_notify_email" value="<?php echo esc_attr(wpsecureops_bruteforce_protect_get_notify_email());
    ?>" /></td>
				</tr>
							</table>

			<?php submit_button();
    ?>

		</form>
	</div>
<?php

}
