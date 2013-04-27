<?php

// Add all the options.
include 'config.php';

if ( !isset ( // Check the config file loaded correctly. 
	$site_title,
	$site_tagline,
	$site_description,
	$install_directory,
	$database_host,
	$database_user,
	$database_pass,
	$database_name,
	$table_name,
	$actual_user, 
	$actual_salt, 
	$actual_pass, 
	$actual_ckie, 
	$page_login, 
	$page_logout, 
	$page_admin, 
	$page_create, 
	$page_edit, 
	$page_delete, 
	$do_login, 
	$do_logout, 
	$do_create, 
	$do_edit, 
	$do_delete, 
	$home, 
	$posts_printed, 
	$posts_divider, 
	$requested, 
	$location 
	) ) {
		echo '<!DocType html><html><head><meta charset="UTf-8"><title>Simplet Encountered a Fatal Error</title><style>body{width:70%;margin:2em auto}h1{font:normal 6em/1.7 LeagueGothicRegular,"lucida sans unicode","lucida grande","Trebuchet MS",verdana,arial,helvetica,helve,sans-serif;color:#111;margin:0;text-align:center}h2{font:normal 2em/2 LeagueGothicRegular,"lucida sans unicode","lucida grande","Trebuchet MS",verdana,arial,helvetica,helve,sans-serif;color:#222;margin:2em 0;text-align:center}p{font:normal 1em/1.7 LeagueGothicRegular,"lucida sans unicode","lucida grande","Trebuchet MS",verdana,arial,helvetica,helve,sans-serif;color:#333;margin:0;text-align:justify}h3{font: lighter 2em/10 LeagueGothicRegular,"lucida sans unicode","lucida grande","Trebuchet MS",verdana,arial,helvetica,helve,sans-serif;font-style:italic;color:#222;margin:0;text-align:right}a{color:#1777af;text-decoration:none}</style></head><body><h1>FATAL ERROR :</h1><h2>The config file loaded incorrectly, or required variables are not set.</h2><p>Simplet encountered a fatal error and has had to hault this application. There was a problem loading the config file and all the variables have not been set. Variables may be empty, but not removed. It is possible there are incorrect file permissions or faulty formatting. You may <a href="https://github.com/eustasy/simplet/issues">raise an issue on GitHub</a> or attempt to fix the problem yourself. It should not require anything more than a basic understanding of PHP or file permissions.</p><h3><a href="http://simplet.eustasy.org/">Simplet</a></h3></div></body></html>';
		exit;
} // Assume it did if all the variables are set

$timestamp = date("Y-m-d");

echo '<?xml version="1.0" encoding="UTF-8"?>'; // Totally an XML file, no PHP here.
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'; // An Sitemap to boot.
echo '	<url>';
echo '		<loc>http://' . $home . '</loc>';
echo '		<lastmod>' . $timestamp . '</lastmod>';
echo '		<priority>1</priority>';
echo '		<changefreq>daily</changefreq>';
echo '	</url>';

$map_results = mysqli_query($connection, "SELECT * FROM $table_name ORDER BY timestamp DESC", MYSQLI_STORE_RESULT);
if (!$map_results) { // If there's nothing
	echo 'Invalid query: ' . mysqli_error() . ''; // Tell us what's wrong
} else { // But while there is
	$map_num_results = mysqli_num_rows($map_results); 
	if ($map_num_results == 0){ 
		echo 'No Posts Found';
	} else { // If it was none of those, then it must be a post.
		while($row = mysqli_fetch_row($map_results)) { // And while there's a row
			$url = urlencode($row[1]);
			$timestamp = $rest = substr($row[5], 0, 10);;
            

echo '	<url>';
echo '		<loc>http://' . $home . $url . '</loc>';
echo '		<lastmod>' . $timestamp . '</lastmod>';
echo '		<priority>0.9</priority>';
echo '		<changefreq>weekly</changefreq>';
echo '	</url>';

		} // And start again
		mysqli_free_result($map_results); // Get rid of the results
		mysqli_close($connection); // And close the connection
	}
}

echo '</urlset>';
?>
