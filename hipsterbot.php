<?php
/**
 * Generates some nonsense then tweets it.
 *
 * @author Paul Wright. pablo.wright@gmail.com 
 * @based on Daily Mail Headline Generator by
 * @author Damien Walsh <me@damow.net>
 * @version 1.0
 * @01/02/2015
 */
/**
 * Generate a some random gibberish and tweet it.
 * In addition to this file you need a json file. example json is included.
 * You also need the twitteroauth php library by Abraham Williams (https://github.com/abraham/twitteroauth)
 * If you are only using one Twitter account, you can put your credentials in this file.
 * else modify the 'user.php' include file with your twitter tokens.
 * 
 */

   // -----------------------------------------------------
 
function gen_nonsense()
{
  // ------------------------------------------------------
  // Load the "ennui" database...
  // ------------------------------------------------------
  $ennui = (array)json_decode(file_get_contents('hipster.json'));
  
  // Convert to array
  foreach($ennui as $key => & $value)
  {
    $ennui[$key] = (array)$value;
  }

  // ------------------------------------------------------
  // Randomise
  // ------------------------------------------------------
  shuffle($ennui['nouns']);
  shuffle($ennui['verbs']);
  shuffle($ennui['forms']);
  shuffle($ennui['places']);
  shuffle($ennui['hashtags']);
  shuffle($ennui['quantities']);
  shuffle($ennui['directions']);
  shuffle($ennui['thatiss']);
  shuffle($ennui['medias']);
  shuffle($ennui['adjectives']);
  shuffle($ennui['objects']);
  shuffle($ennui['peeps']);

  // ------------------------------------------------------
  // Perform replacements
  // ------------------------------------------------------
  $form = current($ennui['forms']);
  $form = str_replace('%noun', ucfirst(current($ennui['nouns'])), $form);
  $form = str_replace('%verb', current($ennui['verbs']), $form);
  $form = str_replace('%place', current($ennui['places']), $form);
  $form = str_replace('%hashtag', current($ennui['hashtags']), $form);
  $form = str_replace('%quantity', current($ennui['quantities']), $form);
  $form = str_replace('%direction', strtoupper(current($ennui['directions'])), $form);
  $form = str_replace('%media', ucfirst(current($ennui['medias'])), $form);
  $form = str_replace('%thatis', ucfirst(current($ennui['thatiss'])), $form);
  $form = str_replace('%lowrand', rand(2, 15), $form);
  $form = str_replace('%highrand', rand(70, 95), $form);
  $form = str_replace('%medrand', rand(50, 75), $form);
  $form = str_replace('%adjective', ucfirst(current($ennui['adjectives'])), $form);
  $form = str_replace('%peep', ucfirst(current($ennui['peeps'])), $form);
  $form = str_replace('%object', strtoupper(current($ennui['objects'])), $form);

  return $form;
 
}
  // ----------------------------------------------------------
  // Get your gibberish to Twitter
  // ----------------------------------------------------------

$tweetContent = gen_nonsense();
require_once('../twitteroauth/twitteroauth/twitteroauth.php');

	// ----------------------------------------------------------
	// get userID from script call
	// ----------------------------------------------------------
$userID = $_GET["UID"];

	// ---------------------------------------------------------------------------
	// determines which twitter account to tweet to: (or replace with your tokens)
	// ---------------------------------------------------------------------------
switch ($userID) {
    case "1":
        $file = "../path/to/user1php";
        break;
    case "2":
        $file = "../path/to/user2.php";
        break;
    case "4":
        $file = "../path/to/user3.php";
        break;
    case "4":
        $file = "../path/to/user4.php";
        break;
    default:
        exit("No user by that ID");
}

	// ----------------------------------------------------------
	// Include selected user's twitter app credentials from file:
	// ---------------------------------------------------------- 
include "$file" ; 

// Now tweet it:
$tweet = array('status' => $tweetContent);
$status = $tweetContent;

if ($status) {

	$connection = new TwitterOAuth ($consumer_key ,$consumer_secret , $access_key , $access_secret );
 
	$resultArray = $connection->post('statuses/update', $tweet);

}

	// ----------------------------------------------------------
	// Log script results:
	// ----------------------------------------------------------
date_default_timezone_set('America/New_York');
$tweetContentDate = date('m/d/Y h:i:s a', time());
$statusCode = $connection->http_code;
$statusSuccess = "{$tweetContentDate}  TwitterUser {$userID} tweeted {$tweetContent}";
$statusError = "{$statusCode}  {$tweetContentDate} TwitterUser {$userID}";
       
        if ($connection->http_code == 200) {
		error_log('Success ' .$statusSuccess."\n", 3, "tweeterErrors.log");
			} else {
		error_log('Error posting to twitter: '.$statusError."\n", 3, "tweeterErrors.log");
                        }
       echo "$tweetContent";

	// ----------------------------------------------------------
	// Clear data:
	// ----------------------------------------------------------
unset($ennui, $form);

?>