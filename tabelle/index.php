<!DOCTYPE html>
<html lang="de-DE">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<title>Kirchenverzeichnis Web- und Social-Media-Auftritte - Tabelle</title>
	<meta name="description" content="Eine Tabelle mit den Webseiten und Social-Media-Auftritten von Kirchen im deutschsprachigen Raum.">
	<link rel="stylesheet" href="../css/style.css">
	<link rel="stylesheet" href="theme.default.css">
</head>
<body id="table">
	<header>
		<h1>Tabelle kirchlicher Web- und Social-Media-Auftritte</h1>
		<nav>
			<ul>
				<li><a href="../">Das Projekt</a></li>
				<li><a href="../karte/">Kartenansicht</a></li>
				<li><strong>Tabellenansicht</strong></li>
				<li><a href="../validierung/">Datenvalidierung</a></li>
				<li><a href="https://docs.google.com/forms/d/1364JigiaC71J4AZXM52jatkfwFEgryxBW7N6eBOnExM/viewform">Gemeinde ergänzen</a></li>
				<li><a href="https://docs.google.com/spreadsheets/d/12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM/">Daten bearbeiten</a></li>
				<li><a href="../entwicklung/">Entwicklung</a></li>				
			</ul>
		</nav>
	</header>
	<main>
		<p>Die Tabelle listet christliche Kirchen im deutschsprachigen Raum mit ihren Webauftritten und Social-Media-Profilen - geordnet nach PLZ - auf. Sie können die Daten durch einen Klick auf die Kopfzeile nach anderen Kriterien sortieren und/oder filtern</p>
		<ul>
			<li>nach Konfession: <a href="#alle">alle</a> | <a href="#evangelisch">evangelisch</a> | <a href="#katholisch">katholisch</a> | <a href="#andere">andere</a></li>
			<li>nach Netzwerk: <a href="#alle">alle</a> | <a href="#web">Webseite</a> | <a href="#facebook">Facebook</a> | <a href="#google">Google+</a> | <a href="#twitter">Twitter</a> | <a href="#youtube">YouTube</a></li>
		</ul>
		
		<h2 id="evangelisch">Evangelische Kirchen</h2>
		<h2 id="katholisch">Katholische Kirchen</h2>
		<h2 id="andere">Andere Kirchen</h2>
		
		<h2 id="web">Kirchen mit Webseite</h2>
		<h2 id="facebook">Kirchen mit Facebook-Seite</h2>
		<h2 id="google">Kirchen mit Google+-Profil</h2>
		<h2 id="twitter">Kirchen mit Twitter-Profil</h2>
		<h2 id="youtube">Kirchen mit Youtube-Profil</h2>
		
		<p>Wenn Ihre Gemeinde noch fehlt, können Sie diese über <a href="https://docs.google.com/forms/d/1364JigiaC71J4AZXM52jatkfwFEgryxBW7N6eBOnExM/viewform">dieses Formular</a> eintragen lassen. 
			Bereits eintragende Daten können in <a href="https://docs.google.com/spreadsheets/d/12d-puCj61KmcHssXTV7hRUXaZacoVP6EXupo07eHfoM/">dieser Google Docs Tabelle</a> geändert werden.</p>
			
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
			fgetcsv ( $handle, 200, "," ); // skip line with the headers
			while ( ($data = fgetcsv ( $handle, 500, "," )) !== FALSE ) {
				$class = $data[2];
				if ($class != 'katholisch' && $class != 'evangelisch') {
					$class = 'andere';
				}
				if ($data[9] != '') {
					$web = '<td><a href="' . $data[9] . '">Web</a></td>';
					$class .= ' web';
				} else {
					$web = '<td></td>';
				}
				if ($data[10] != '') {
					$facebook = '<td><a href="' . $data[10] . '">Facebook</a></td>';
					$class .= ' facebook';
				} else {
					$facebook = '<td></td>';
				}
				if ($data[11] != '') {
					$google = '<td><a href="' . $data[11] . '">Google+</a></td>';
					$class .= ' google';
				} else {
					$google = '<td></td>';
				}
				if ($data[12] != '') {
					$twitter = '<td><a href="' . $data[12] . '">Twitter</a></td>';
					$class .= ' twitter';
				} else {
					$twitter = '<td></td>';
				}
				if ($data[13] != '') {
					$youtube = '<td><a href="' . $data[13] . '">YouTube</a></td>';
					$class .= ' youtube';
				} else {
					$youtube = '<td></td>';
				}
				echo '<tr class="' . $class .'">';
				echo '<td>' . $data[1] . '</td>';
				echo '<td>' . $data[2] . '</td>';
				echo '<td>' . $data[3] . $data[4] . '</td>';
				echo '<td>' . $data[5] . '</td>';
				echo '<td>' . $data[6] . '</td>';
				echo '<td>' . $data[7] . '</td>';
				echo '<td>' . $data[8] . '</td>';
				echo $web, $facebook, $google, $twitter, $youtube, '</tr>';
			}
			fclose ( $handle );
		} else {
			die("Problem reading csv");
		} ?>
			</tbody>
		</table>	
	</main>
	<footer>
		<a href="../entwicklung/">Impressum</a>
	</footer>	
	<script src="http://code.jquery.com/jquery-2.1.4.js"></script>
	<script src="jquery.tablesorter.js"></script>
	<script>$("#churchTable").tablesorter({sortList: [ [4,0] ]});</script>
</body>
</html>