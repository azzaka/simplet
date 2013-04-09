<?php

// Add all the options.
require 'config.php';

echo '<?xml version="1.0" encoding="utf-8"?>'; // Totally an XML file, no PHP here.
echo '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">'; // An RSS feed to boot.
echo '	<channel>'; // This is all one Channel, not a site, because this is totally TV.
echo '		<title>' . $site_title . '</title>';
echo '		<description>' . $site_tagline . '</description>';
echo '		<link>http://' . $home . '</link>';
echo '		<language>en</language>'; // We're going to presume you're english.
echo '		<generator>Simplet</generator>'; // Give us some credit.

$result = mysqli_query($connection, "SELECT * FROM $table_name ORDER BY timestamp DESC", MYSQLI_STORE_RESULT);
if (!$result) { // If there's nothing
	echo 'Invalid query: ' . mysqli_error() . ''; // Tell us what's wrong
} else { // But while there is
	$num_results = mysqli_num_rows($result); 
	if ($num_results == 0){ 
		echo 'No Posts Found';
	} else { // If it was none of those, then it must be a post.
		while($row = mysqli_fetch_row($result)) { // And while there's a row
			$title = html_entity_decode($row[0], ENT_QUOTES | ENT_HTML5, "UTF-8");
			$title_stripped = strip_tags ($title);
			$excerpt = html_entity_decode($row[2], ENT_QUOTES | ENT_HTML5, "UTF-8");
			$excerpt_stripped = strip_tags ($excerpt);
			$url = urlencode($row[1]);
			$timestamp = urlencode($row[5];

echo '	<item>';
echo '		<title>' . $title_stripped . '</title>';
echo '		<description>' . $excerpt_stripped . '</description>';
echo '		<link>http://' . $home . $url . '</link>';
echo '		<pubDate>' . $timestamp . '</pubDate>';
echo '	</item>';

		} // And start again
		mysqli_free_result($result); // Get rid of the results
	}
}

echo ' </channel>';
echo '</rss>';
?>
