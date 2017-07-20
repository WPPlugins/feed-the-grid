<?PHP
require_once 'twitteroauth.php';

define("CONSUMER_KEY", "****consumer key*******");
define("CONSUMER_SECRET", "*******Consumer secret********");
define("OAUTH_TOKEN", "**********Oauth token*******");
define("OAUTH_SECRET", "*****Oauth Secret********");


$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN, OAUTH_SECRET);
$content = $connection->get('account/verify_credentials');

$connection->post('statuses/update', array('status' => 'This tweet is from PHP Oauth API'));


?>