<?php

// End the head, start the body, add a skiptomain, start the wrapper, start the header and it's container
echo '</head><body><div id="skiptomain"><a href="#maincontent">skip to main content</a></div><div id="wrapper"><div id="headcontainer"><header>';

if ( $auth == 'true' ) { // If logged in
	echo '<span class="floatright"><a href="http://' . $home . $page_admin . '">Admin</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $page_logout . '">Logout</a></span><div class="clear"></div>'; // Show admin and logout links
}// else { // You can uncomment these next three lines if you want a login link displays to un-authenticated users.
//	echo '<span class="floatright"><a href="http://' . $home . $page_login . '">Login</a></span><div class="clear"></div>';
// }

// Show the site title, and link it to the homepage
echo '<h1><a href="http://' . $home . '">' . $site_title . '</a></h1><h5>' . $site_tagline . '</h5>'; // And the tagline, don't forget that.

	$nav__result = mysqli_query($connection, "SELECT * FROM $table_name WHERE nav='1' ORDER BY title ASC", MYSQLI_STORE_RESULT);

	if (!$nav__result) { // If there's nothing
		echo 'Invalid query: ' . mysqli_error() . ''; // Tell us what's wrong
	} else {

		$nav_num_rows = mysqli_num_rows($nav__result);

		echo '<div class="section group nav">';

		while($row = mysqli_fetch_row($nav__result)) {

			$nav_title = html_entity_decode($row[0], ENT_QUOTES | ENT_HTML5, "UTF-8");

			echo '<div class="col span_1_of_' . $nav_num_rows . '">';
			echo '<a href="http://' . $home . $row[1] . '">' . $nav_title . '</a>';
			echo '<div class="clear"></div></div>';
		}

		echo '</div>';

		mysqli_free_result($nav__result);

	}


echo '</header></div><div id="maincontentcontainer"><div class="maincontent">';
?>
