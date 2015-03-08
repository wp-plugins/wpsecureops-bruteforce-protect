<?php

/*
Plugin Name: WPSecureOps BruteForce Protect
Plugin URI: http://wpsecureops.com/
Description: The simplest yet free Brute Force plugin that will protect your WordPress site from any brute force password attacks.
Version: 1.4
Author: WPSecureOps
Author URI: http://wpsecureops.com/
License: GPLv2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
*/

require_once "plugin_info.php";
require_once "utils.php";

require_once "settings.php";

global $wpsecureops_bruteforce_protect_error;
$wpsecureops_bruteforce_protect_error = false;

add_filter('authenticate', 'wpsecureops_bruteforce_protect_auth_login', 99999);
function wpsecureops_bruteforce_protect_auth_login($user)
{
    $timeout = intval(wpsecureops_bruteforce_protect_get_lock_duration());
    $timeout = $timeout  ? $timeout : 60 * 60 * 1 /* 1h */;

    $max_attempts = intval(wpsecureops_bruteforce_protect_get_max_attempts());
    $max_attempts = $max_attempts ? $max_attempts : 5;

    $cnt = get_transient($k = "wpso_bfp_" . wpsecureops_bruteforce_protect_get_ip_address());
    if ($cnt === false) {
        $cnt = 0;
    } else {
        $cnt = intval($cnt);
    }

    $cnt++;

    set_transient($k, "" . $cnt . "", $timeout);

    if ($cnt >= $max_attempts) {
        global $wpsecureops_bruteforce_protect_error;
        $wpsecureops_bruteforce_protect_error = true;
        // send notification
        $email = wpsecureops_bruteforce_protect_get_notify_email();
        if ($cnt === $max_attempts && !empty($email)) {
            $r = wp_mail(
                $wpsecureops_bruteforce_protect_error['notify_email'],
                __('WPSecureOps Notification: Brute Force attack', 'wpsecureops_bruteforce_protect'),
                __('WPSecureOps BruteForce Protect has detected a new login failed attack from: ' . wpsecureops_bruteforce_protect_get_ip_address() . '

However, you can relax, because we\'d blocked the user for a while, so everything is just fine :)

Cheers,
WPSecureOps Team', 'wpsecureops_bruteforce_protect')
            );
        }

        $error = new WP_Error();

        $error->add('banned_bruteforce',
            __("You have been banned because of multiple login failed attempts.", 'wpsecureops_bruteforce_protect')
        );

        return $error;
    }

    return $user;
}

add_action('admin_init', 'wpsecureops_bruteforce_protect_restrict_admin', 1);
function wpsecureops_bruteforce_protect_restrict_admin()
{
    $max_attempts = intval(wpsecureops_bruteforce_protect_get_max_attempts());
    $max_attempts = $max_attempts ? $max_attempts : 5;

    $cnt = get_transient($k = "wpso_bfp_" . wpsecureops_bruteforce_protect_get_ip_address());
    if ($cnt === false) {
        return;
    } else {
        $cnt = intval($cnt);
    }

    if ($cnt >= $max_attempts) {
        global $wpsecureops_bruteforce_protect_error;
        $wpsecureops_bruteforce_protect_error = true;
        wp_logout();
        wp_die(__("You have been banned because of multiple login failed attempts.", 'wpsecureops_bruteforce_protect'));
    }
}
