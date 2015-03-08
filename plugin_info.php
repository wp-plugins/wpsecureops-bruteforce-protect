<?PHP
defined('ABSPATH') or exit;

global $WPSecureOps;
if (!isset($WPSecureOps)) {
    $WPSecureOps = array();
}

// must be hardcoded, just in case that when installing the plugin the plugin
// dir was named differently, e.g. containing a git/hg hash
$pluginId = "wpsecureops_bruteforce_protect";

$WPSecureOps[$pluginId] = array(
    "title"   => "WPSecureOps Brute Force Protect",
    "id"      => $pluginId,
    "version" => "1.4",
);

return $pluginId;
