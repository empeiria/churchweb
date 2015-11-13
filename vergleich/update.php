<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width">
	<title>Kirchenverzeichnis Web- und Social-Media-Auftritte - Generator</title>
</head>
<body>
<?php
	$time_start = microtime(true);

	set_time_limit(0);
	$home_url = 'http://kirchen-im-web.de/vergleich/';
	
	if ( file_exists( 'index2.html' ) ) {
		echo '<p>Neugenerierung läuft schon.</p>';
	} else {
		echo '<p>Neugenerierung gestartet.</p>';

		// execute the script and save result in index2.html
		writeFile( 'index2.html', $home_url . 'social-media.php' );
		echo '<p>Neugenerierung erfolgreich.</p>';
		
		// overwrite index.html with index2.html
		if (rename( 'index2.html', 'index.html' )) {
			echo '<p><a href="./">Social-Media-Liste</a> aktualisiert.</p>';
		} else {
			echo '<p>Fehler beim Überschreiben.</p>';
		}
		if (@unlink('index2.html')) {
			echo '<p>Zwischenergebnis gelöscht.</p>';
		}
	}
?>	
	
	<p>Dauer: 
<?php
	$time_end = microtime(true);
	$execution_time = $time_end - $time_start;	
	echo $execution_time;
?>
	Millisekunden</p>
</body>
</html>
<?php
	function writeFile($target, $source) {
		$targetFile = fopen($target, 'w') or die("file could not be accessed/created");
		$sourceFile = file_get_contents($source);
		fwrite($targetFile, $sourceFile);
		fclose($targetFile);			
	}
?>