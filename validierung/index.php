<!DOCTYPE html>
<html lang="de-DE">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<title>Kirchenverzeichnis Web- und Social-Media-Auftritte - Fehleranalyse im Datenbestand</title>
	<meta name="description" content="Viele Kirchengemeinden nutzen Social-Media-Auftritte. Das Projekt kirchen-im-web.de macht diese sichtbar.">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../tabelle/theme.default.css">
</head>
<body id="validierung">
	<header>
		<h1>Offene Daten</h1>
		<nav>
			<ul>
				<li><a href="../">Das Projekt</a></li>
				<li><a href="../karte/">Karte</a></li>
				<li><a href="../tabelle/">Tabelle</a></li>
				<li><a href="../vergleich/">Vergleich</a></li>
				<li><a href="../validierung/"><strong>Offene Daten</strong></a></li>
				<li><a href="../entwicklung/">Entwicklung</a></li>
			</ul>
		</nav>
	</header>
	<main>
		<p>Für die Umsetzung unseres Projektes sind natürlich die Adressen sowie die URLs der Webauftritten und der Social-Media-Profile notwendig. 
			Da es sich hierbei um öffentlich verfügbare Informationen handelt, müssen diese „nur“ zusammengetragen und gepflegt werden. 
			Dabei kann jeder mithelfen, der ein wenig Zeit und Lust mitbringt:</p>
		<ul>
			<li>Sie möchten Ihre (oder auch eine andere) Gemeinde ergänzen? <a href="https://docs.google.com/forms/d/1364JigiaC71J4AZXM52jatkfwFEgryxBW7N6eBOnExM/viewform">Das geht über dieses Formular</a>. 
				In unserer <a href="../karte/">Karten- </a> oder <a href="../tabelle/">Tabellenansicht</a> können Sie vorher nachschauen, ob die Gemeinde bereits gelistet ist.</li>
			<li>Sie möchten bereits gelistete Daten korrigieren oder ergänzen? <a href="https://docs.google.com/spreadsheets/d/12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM/">Das geht in dieser Google Docs Tabelle</a>.</li>
			<li>Sie haben selber einen Datensatz und möchten uns diesen zur Verfügung stellen? Dann kontaktieren Sie bitte einen der <a href="../impressum.html">Entwickler</a>.</li>
		</ul>
		<section id="download">
			<h2>Download</h2>
			<p>Die Daten stehen in einem freien und offenen Format zur Verfügung, d. h. die Daten können auch für andere Projekte verwendet werden (z. B. eine App mit ähnlicher Funktion).</p>
			<p>Download als:</p>
			<ul>
				<li><a href="https://docs.google.com/spreadsheets/d/12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM/export?format=ods&amp;id=12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM">OpenDocument-Tabelle (ods)</a></li>
				<li><a href="https://docs.google.com/spreadsheets/d/12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM/export?format=csv&amp;id=12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM&amp;gid=683654467">csv</a></li>
				<li><a href="https://spreadsheets.google.com/feeds/list/12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM/obb1315/public/values?alt=json">JSON</a></li>
			</ul>
		</section>
		<section id="validierung">
			<h2>Validierung</h2>
			<p>Die Daten werden hier validiert, d. h. ein Programm überprüft, ob die Daten in der Tabelle unseren <a href="#anforderungen">Anforderungen</a> entsprechen.
				Ein grüner Hintergrund bedeutet, dass unser Programm die Daten für syntaktisch korrekt hält; rote, unterstrichene Daten entsprechen nicht den Anforderungen.</p>
			<table id="churchTable" class="tablesorter">
				<thead>
					<tr>
						<th>Name</th><th>Konfession</th><th>Landeskirche / Bistum </th><th>Straße und Hausnummer</th><th>PLZ</th><th>Ort</th><th>Land</th>
						<th>Webseite</th><th>Facebook</th><th>Google+</th><th>Twitter</th><th>YouTube</th>
					</tr>
				</thead>
				<tbody>
		<?php
			$spreadsheet_url = "https://docs.google.com/spreadsheets/d/12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM/export?format=csv&id=12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM&gid=683654467";
	
			if (! ini_set ( 'default_socket_timeout', 15 ))
				echo "<!-- unable to change socket timeout -->";
	
			if (($handle = fopen ( $spreadsheet_url, "r" )) !== FALSE) {
				fgetcsv ( $handle, 200, "," ); // skip line with the headers
				while ( ($data = fgetcsv ( $handle, 500, "," )) !== FALSE ) {
					if ($data[9] != '') {
						$web = '<td class="r' . isURL($data[9]) . '"><a href="' . $data[9] . '">Web</a></td>';
					} else {
						$web = '<td class="r"></td>';
					}
					if ($data[10] != '') {
						$facebook = '<td class="r' . isFacebookURL($data[10]) . '"><a href="' . $data[10] . '">Facebook</a></td>';
					} else {
						$facebook = '<td></td>';
					}
					if ($data[11] != '') {
						$google = '<td class="r' . isGooglePlusURL($data[11]) . '"><a href="' . $data[11] . '">Google+</a></td>';
					} else {
						$google = '<td></td>';
					}
					if ($data[12] != '') {
						$twitter = '<td class="r' . isTwitterURL($data[12]) . '"><a href="' . $data[12] . '">Twitter</a></td>';
					} else {
						$twitter = '<td></td>';
					}
					if ($data[13] != '') {
						$youtube = '<td class="r' . isYoutubeURL($data[13]) . '"><a href="' . $data[13] . '">YouTube</a></td>';
					} else {
						$youtube = '<td></td>';
					}
					echo '<tr>';
					echo '<td class="r' . isValidName($data[1]) . '">' . $data[1] . '</td>';
					echo '<td class="r' . isDenomination($data[2]) . '">' . $data[2] . '</td>';
					echo '<td class="r' . isLandeskircheBistum($data[2], $data[3], $data[4]) . '">' . $data[3] . $data[4] . '</td>';
					echo '<td class="r' . isStreet($data[5]) . '">' . $data[5] . '</td>';
					echo '<td class="r' . isPostalCode($data[6], $data[8]) . '">' . $data[6] . '</td>';
					echo '<td class="r' . isTown($data[7]) . '">' . $data[7] . '</td>';
					echo '<td class="r' . isCountry($data[8]) . '">' . $data[8] . '</td>';
					echo $web, $facebook, $google, $twitter, $youtube, '</tr>';
				}
				fclose ( $handle );
			} else {
				die("Problem reading csv");
			} ?>
				</tbody>
			</table>
		</section>	
		<section id="anforderungen">
			<h2>Was wird hier geprüft? - unsere Anforderungen</h2>
			<p>Bei der Validierung wird geprüft, ob</p>
			<ul>
				<li>der Name kein Komma enthält,</li>
				<li>die Konfession katholisch, evangelisch oder evangelisch-freikirchlich ist,</li>
				<li>bei evangelischen Kirchen eine Landeskirche bzw. bei katholischen Kirchen ein Bistum und sonst keins von beidem angegeben ist,</li>
				<li>eine Straße mit Hausnummer angeben ist,</li>
				<li>die Postleitzahl fünf- (Deutschland) bzw. vierstellig (sonst) ist,</li>
				<li>das Land Deutschland, Liechtenstein, Österreich oder die Schweiz ist,</li>
				<li>eine syntaktisch gültige Web-URL angegeben ist (nicht jedoch, ob unter dieser Inhalt bzw. der passende Inhalt erreichbar ist),</li>
				<li>und ob die URLs für Facebook-Seite, Google+, Twitter und Youtube syntaktisch gültige URLs für das jeweilige Netzwerk sind (nicht jedoch, ob unter der URL auch passender Inhalt erreichbar ist).</li>
			</ul>
		</section>
	</main>
	<footer>
		<p><a href="../impressum.html">Impressum</a></p>
	</footer>	
	<script src="http://code.jquery.com/jquery-2.1.4.js"></script>
	<script src="../tabelle/jquery.tablesorter.js"></script>
	<script>$("#churchTable").tablesorter();</script>
</body>
</html>
<?php
	function isValidName($name) {
		return $name && is_string($name) && $name != '' && (strpos($name, ',') === false);
	}
	
	function isDenomination($denomination) {
		return ($denomination == 'evangelisch' || $denomination == 'katholisch' || $denomination == 'evangelisch-freikirchlich');
	}
	
	function isLandeskircheBistum($denomination, $landeskirche, $diocese) {
		if ($denomination == 'evangelisch') {
			return ($landeskirche != '') && ($diocese == ''); //TODO list
		} else if ($denomination == 'katholisch') {
			return ($landeskirche == '') && ($diocese != ''); //TODO list
		} else {
			return ($landeskirche == '') && ($diocese == '');
		}
	}

	function isStreet($street) {
		return $street && is_string($street) && (strpos($street, '/') === false)
			&& preg_match('/([A-Z]|Ö|Ä|Ü)([A-Z]|[a-z]|ä|ü|ö||-|.| )+ [0-9]+[a-z]*(-[0-9]+)*/', $street);
	}
	
	function isPostalCode($postalCode, $country) {
		if ($country == 'Deutschland') {
			return $postalCode && is_string($postalCode)
				&& preg_match('/[0-9]{5}/', $postalCode);
		}
		if ($country == 'Österreich' || $country == 'Liechtenstein' || $country == 'Schweiz') {
			return $postalCode && is_string($postalCode)
				&& preg_match('/[0-9]{4}/', $postalCode);
		}
		return false;
	}
	
	function isTown($town) {
		return $town && is_string($town) 
			&& preg_match('/[A-Z]([a-z]|ü|ö|ä)+/', $town);
	}	

	function isCountry($country) {
		return ($country == 'Deutschland' || $country == 'Liechtenstein' || $country == 'Österreich' || $country == 'Schweiz');
	}	
	
	function isURL($url) {
		return ($url && is_string($url) && $url != ''
			&& preg_match('/^http(s)?:\/\/[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(\/.*)?$/i', $url));
	}
	
	function isFacebookURL($url) {
		return isURL($url) && startsWith($url, 'https://www.facebook.com/');
	}
	
	function isGooglePlusURL($url) {
		return isURL($url) && startsWith($url, 'https://plus.google.com/');
	}

	function isTwitterURL($url) {
		return isURL($url) && startsWith($url, 'https://twitter.com/');
	}
		
	function isYoutubeURL($url) {
		return isURL($url) && (startsWith($url, 'https://www.youtube.com/user/') || startsWith($url, 'https://www.youtube.com/channel/'));
	}
	
	function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
?>