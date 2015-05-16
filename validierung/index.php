<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<title>Kirchenverzeichnis Web- und Social-Media-Auftritte - Fehler</title>
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="../tabelle/theme.default.css">	
	<style>
		td{background:#515157 !important;}/*grey -> unknown*/
		.r{background:#FF0000 !important;text-decoration:underline;}/*red -> error */
		.r1{background:#88DC92 !important;}/*green -> all okay*/
	</style>
</head>
<body id="table">
	<header>
		<h1>Mögliche Fehler im Kirchenverzeichnis</h1>
		<nav>
			<ul>
				<li><a href="../">Das Projekt</a></li>
				<li><a href="../karte/">Kartenansicht</a></li>
				<li><a href="../tabelle/">Tabellenansicht</a></li>
				<li><strong>Datenvalidierung</strong></li>
				<li><a href="https://docs.google.com/forms/d/1364JigiaC71J4AZXM52jatkfwFEgryxBW7N6eBOnExM/viewform">Gemeinde ergänzen</a></li>
				<li><a href="https://docs.google.com/spreadsheets/d/12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM/">Daten bearbeiten</a></li>
			</ul>
		</nav>
	</header>
	<main>
		<p>Die Daten werden hier validiert. Ein grüner Hintergrund bedeutet, dass unser Programm die Daten für syntaktisch korrekt hält; eine rote, unterstrichene entsprechen nicht den Anforderungen.</p>
		<p>Fehler können in <a href="https://docs.google.com/spreadsheets/d/12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM/">dieser Google Docs Tabelle</a> behoben werden.</p>
		<table id="churchTable" class="tablesorter">
			<thead>
				<tr>
					<th>Name</th><th>Konfession</th><th>Landeskirche / Bistum </th><th>Straße und Hausnummer</th><th>PLZ</th><th>Ort</th><th>Land</th>
					<th>Webseite</th><th>Facebook</th><th>Google</th><th>Twitter</th><th>YouTube</th>
				</tr>
			</thead>
			<tbody>
	<?php
		$spreadsheet_url = "https://docs.google.com/spreadsheets/d/12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM/export?format=csv&id=12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM&gid=683654467";

		if (! ini_set ( 'default_socket_timeout', 15 ))
			echo "<!-- unable to change socket timeout -->";

		if (($handle = fopen ( $spreadsheet_url, "r" )) !== FALSE) {
			fgetcsv ( $handle, 1000, "," ); // skip line with the headers
			while ( ($data = fgetcsv ( $handle, 1000, "," )) !== FALSE ) {
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
				echo $web;
				echo $facebook;
				echo $google;
				echo $twitter;
				echo $youtube;
				echo '</tr>';
			}
			fclose ( $handle );
		} else {
			die("Problem reading csv");
		}
	?>
			</tbody>
		</table>
	</main>
	<script src="http://code.jquery.com/jquery-2.1.4.js"></script>
	<script src="../tabelle/jquery.tablesorter.js"></script>
	<script>
		$("#churchTable").tablesorter();
	</script>
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
		return ($country == 'Deutschland' || $country == 'Österreich' || $country == 'Schweiz');
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
		return isURL($url) && startsWith($url, 'https://www.youtube.com/');
	}
	
	function startsWith($haystack, $needle) {
		$length = strlen($needle);
		return (substr($haystack, 0, $length) === $needle);
	}
?>