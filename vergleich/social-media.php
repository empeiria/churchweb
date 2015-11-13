<?php 
	require_once 'TwitterAPIExchange.php';
	require_once 'apikey.php';	
?>
<!DOCTYPE html>
<html lang="de-DE">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<title>Kirchliche Social-Media-Auftritte im Vergleich</title>
	<meta name="description" content="Viele Kirchengemeinden nutzen Social-Media-Auftritte. Diese werden hier anhand ihrer Like-/Follower-/Abonnenten-Zahlen verglichen.">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../tabelle/theme.default.css">
</head>
<body>
	<header>
		<h1>Kirchliche Social-Media-Auftritte im Vergleich (Beta)</h1>
		<nav>
			<ul>
				<li><a href="../">Das Projekt</a></li>
				<li><a href="../karte/">Karte</a></li>
				<li><a href="../tabelle/">Tabelle</a></li>
				<li><a href="../vergleich/"><strong>Vergleich</strong></a></li>
				<li><a href="../validierung/">Offene Daten</a></li>
				<li><a href="../entwicklung/">Entwicklung</a></li>
			</ul>
		</nav>
	</header>
	<main>
		<p>Hier gibt's einen Vergleich der Like-Zahlen für Facebook-Seiten, der Follower bei Google+ und Twitter sowie der Abonnenten der YouTube-Kanäle. Mit einem Klick auf die jeweilige Spalte kann man nach dieser sortieren.</p>
		<p>Kann für einen Social-Media-Auftritt kein Ergebnis ermittelt werden (z. B. weil die Facebook-Seite nicht öffentlich zugänglich ist), wird der Wert 0 angegeben.</p>
		<p>Zuletzt aktualisiert am <?php echo date("d.m.Y"); ?> um <?php echo date("H:i"); ?> Uhr</p>
		<table id="socialMediaTable" class="tablesorter">
			<thead>
				<tr>
					<th>Name</th><th>Facebook-Likes</th><th>Google+-Follower</th><th>Twitter-Follower</th><th>YouTube-Abonnenten</th>
				</tr>
			</thead>
			<tbody>
	<?php
		set_time_limit(0);
	
		$spreadsheet_url = "https://docs.google.com/spreadsheets/d/12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM/export?format=csv&id=12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM&gid=683654467";

		if (! ini_set ( 'default_socket_timeout', 15 ))
			echo "<!-- unable to change socket timeout -->";
		
		if (($handle = fopen ( $spreadsheet_url, "r" )) !== FALSE) {
			fgetcsv ( $handle, 200, "," ); // skip line with the headers
			while ( ($data = fgetcsv ( $handle, 500, "," )) !== FALSE ) {
				createRow($data);
			}
			fclose ( $handle );
		} else {
			die("Problem reading csv");
		}
	?>
			</tbody>
		</table>
	</main>
	<footer>
		<p><a href="../impressum.html">Impressum</a></p>
	</footer>	
	<script src="http://code.jquery.com/jquery-2.1.4.js"></script>
	<script src="../tabelle/jquery.tablesorter.js"></script>
	<script>$("#socialMediaTable").tablesorter({sortList: [ [1,1] ]});</script>
</body>
</html>
<?php
	function createRow($data) {
		$name = $data[1];
		$facebook = $data[10];
		$google = $data[11];
		$twitter = $data[12];
		$youtube = $data[13];
		
 		if ( $facebook != '' || $google != '' || $twitter != '' || $youtube != '') {		
			if ($facebook != '') {
				$likes = getFacebookLikes($facebook);
				$facebook = '<a href="' . $facebook . '">' . $likes . '</a>';
			}

			if ($google != '') {
				$ones = getGooglePlusOnes($google);
				$google = '<a href="' . $google . '">' . $ones . '</a>';
			}			
			
			if ($twitter != '') {
				$follower = getTwitterFollower($twitter);
				$twitter = '<a href="' . $twitter . '">' . $follower . '</a>';
			}

			if ($youtube != '') {
				$likes = getYoutubeSubscribers($youtube);
				$youtube = '<a href="' . $youtube . '">' . $likes . '</a>';
			}
			
			echo '<tr><td>', $name, '</td><td>', $facebook, '</td><td>', $google, '</td><td>', $twitter, '</td><td>', $youtube, '</td></tr>';
 		}
	}
	
	function getFacebookLikes($url) {
 		$name = substr($url, 25); // 25 = strlen('https://www.facebook.com/')
 		if ( startsWith($name, 'groups/') ) {
 			return 0;
 		}
 		if ( startsWith($name, 'pages/') ) {
 			$name = end(explode('/', $name));
 		}
		$url = 'https://graph.facebook.com/' . $name .'?fields=likes'; // url for the graph api, only read the number of likes
		$file = @file_get_contents($url); // suppress any warning
		if ($file === FALSE) { // check for errors
			return 0;
		}
		$graph = json_decode($file);
		return $graph->likes;		
	}
	
	function getGooglePlusOnes($url) {
		$id = substr($url, 24);
		$sep = explode('/', $id);
		$id = $sep[0];
		$file = @file_get_contents('https://www.googleapis.com/plus/v1/people/' . $id . '?key=AIzaSyACuiK-orfn_oPNsBJe4BBu_Bu1B8bO0QM');
		if ($file === FALSE) {
			return 0;
		}
		$data = json_decode($file, true);
		return $data['circledByCount'];
	}
	
	function getTwitterFollower($url) {
		$name = substr($url, 20); // 20 = strlen('https://twitter.com/')
// 		return $name; // hack for localhost (Twitter API does not work here, just display name instead)
		$settings = array( // constants defined in apikey.php
				'oauth_access_token' => TWITTER_API_TOKEN,
				'oauth_access_token_secret' => TWITTER_API_TOKEN_SECRET,
				'consumer_key' => TWITTER_API_CONSUMER_KEY,
				'consumer_secret' => TWITTER_API_CONSUMER_SECRET
		);
		$twitterAPI = new TwitterAPIExchange($settings);		
		$follow_count = $twitterAPI->setGetfield('?screen_name=' . $name)
		->buildOauth('https://api.twitter.com/1.1/users/show.json', 'GET')
		->performRequest();
		$data = json_decode($follow_count, true);
		return $data['followers_count'];
	}
	
	function getYoutubeSubscribers($url) {
		if (startsWith($url, 'https://www.youtube.com/user/')) {
			$user = substr($url, 29);
			$file = @file_get_contents( 'http://gdata.youtube.com/feeds/api/users/' . $user . '?v=2&alt=json');
			if ($file == FALSE) {
				return 0;
			}
			$data = json_decode($file, true);
			return $data['entry']['yt$statistics']['subscriberCount'];
		} else {
			if (startsWith($url, 'https://www.youtube.com/channel/')) {
				$user = substr($url, 32);
				$file = @file_get_contents( 'http://gdata.youtube.com/feeds/api/channels/' . $user . '?v=2&alt=json');
				if ($file == FALSE) {
					return 0;
				}
				$data = json_decode($file, true);
				return $data['entry']['yt$channelStatistics']['subscriberCount'];
			}
			// no user and no channel
			return 0;
		}
	}

	function isURL($url) {
		return ($url && is_string($url) && $url != ''
				&& preg_match('/^http(s)?:\/\/[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url));
	}
	
	function isFacebookURL($url) {
		return isURL($url) && startsWith($url, 'https://www.facebook.com/');
	}	
	
	function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
?>