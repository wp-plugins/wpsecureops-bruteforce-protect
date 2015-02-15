<?PHP
global $WPSecureOps;
if (!isset($WPSecureOps)) {
    $WPSecureOps = [];
}
//$pluginId = str_replace('-', '_', basename(realpath(__DIR__)));

// must be hardcoded, just in case that when installing the plugin the plugin
// dir was named differently, e.g. containing a git/hg hash
$pluginId = "wpsecureops_bruteforce_protect";

$WPSecureOps[$pluginId] = [
    "title"       => "WPSecureOps Brute Force Protect",
    "id"          => $pluginId,
    "version"     => /* version **/ "1.2" /* end of version */,
    "plugin_url"  => /* plugin url **/ "http://wpsecureops.com/" /* end of plugin url */,
    "github_url"  => /* github url **/ "http://wpsecureops.com/" /* end of github url */,
    "fb_url"      => /* fb url **/ "http://wpsecureops.com/" /* end of fb url */,
    "twitter_url" => /* twitter url **/ "http://wpsecureops.com/" /* end of twitter url */,
];

return $pluginId;
