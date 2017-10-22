<?php 

/*DROPBOX LIBRARIES*/
require_once "dropbox-sdk/Dropbox/autoload.php";


$appInfo = Dropbox\AppInfo::loadFromJsonFile("dropbox-sdk/app-info.json");
$webAuth = new Dropbox\WebAuthNoRedirect($appInfo, "PHP-Example/1.0");

$authorizeUrl = $webAuth->start();

echo "1. Go to: " . $authorizeUrl . "\n";
echo "2. Click \"Allow\" (you might have to log in first).\n";
echo "3. Copy the authorization code.\n";


$authCode = \trim(\readline("Enter the authorization code here: "));

list($accessToken, $dropboxUserId) = $webAuth->finish($authCode);
echo "Access Token: " . $accessToken . "\n";

file_put_contents('dropbox-sdk/atoken.txt', $accessToken);

?>