<?php

// Add all the options.
require 'config.php';

$timestamp = date("Y-m-d");

echo '<?xml version="1.0" encoding="UTF-8"?>'; // Totally an XML file, no PHP here.
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'; // An Sitemap to boot.
echo '	<url>';
echo '		<loc>http://' . $home . '</loc>';
echo '		<lastmod>' . $timestamp . '</lastmod>';
echo '		<priority>1</priority>';
echo '		<changefreq>daily</changefreq>';
echo '	</url>';

$result = mysqli_query($connection, "SELECT * FROM $table_name ORDER BY timestamp DESC", MYSQLI_STORE_RESULT);
if (!$result) { // If there's nothing
	echo 'Invalid query: ' . mysqli_error() . ''; // Tell us what's wrong
} else { // But while there is
	$num_results = mysqli_num_rows($result); 
	if ($num_results == 0){ 
		echo 'No Posts Found';
	} else { // If it was none of those, then it must be a post.
		while($row = mysqli_fetch_row($result)) { // And while there's a row
			$url = urlencode($row[1]);
			$timestamp = $rest = substr($row[5], 0, 10);;
            

echo '	<url>';
echo '		<loc>http://' . $home . $url . '</loc>';
echo '		<lastmod>' . $timestamp . '</lastmod>';
echo '		<priority>0.9</priority>';
echo '		<changefreq>weekly</changefreq>';
echo '	</url>';

		} // And start again
		mysqli_free_result($result); // Get rid of the results
	}
}

echo '</urlset>';
?>
