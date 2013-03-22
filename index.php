<?php


require 'config.php';


// Check you haven't just logged in
if ( $requested == $install_directory . $do_login ) { // Yes?
	$user = htmlentities($_POST['user'], ENT_QUOTES | ENT_HTML5, "UTF-8");
	if ( $user == $actual_user ) { // Okay, check the Username
		$pass = $_POST['pass'] . $actual_salt; // Append the salt to the post_pass
		$hash = hash('sha256', $pass); // Hash that
		if ( $hash == $actual_pass ) { // If it's right
			$expire=time()+60*60*24*28; // Set an expiry time
			setcookie("auth", $actual_ckie, $expire, $install_directory, $_SERVER['SERVER_NAME'], false, true); // Set the cookie
			$auth = 'true'; // Make sure you are
		}
	}
} elseif ( $requested == $install_directory . $do_logout ) { // Are you logging out?

	setcookie ("auth", "", 1); // Clear the Cookie
	setcookie ("auth", false);
	unset($_COOKIE['auth']);

	$auth = 'false';

} elseif ( $_COOKIE["auth"] == $actual_ckie ) { // Are you authenticated 
	$auth = 'true'; // Yes you are
} else { // No you're not
	$auth = 'false'; // Not at all
}



// Start the head element and add some meta elements for compatability
echo '<!DocType html><html><head><meta charset="utf-8"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><meta http-equiv="cleartype" content="on"><meta name="HandheldFriendly" content="True"><meta name="MobileOptimized" content="320"><meta name="viewport" content="width=device-width, target-densitydpi=160dpi, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0">';

// Add a description in there to, just for Google
echo '<meta name="description" content="' . $site_tagline . '">'; 

// We'll add some icons in there too, just to look nice
echo '<link rel="icon" href="http://' . $home . 'favicon.ico">';
echo '<link rel="shortcut icon" href="http://' . $home . 'favicon.ico">';

// You can totally uncomment the next line if you want to use html5reset.css
// echo '<link rel="stylesheet" href="http://' . $_SERVER['SERVER_ADDR'] . '/css/html5reset.css" media="all">

// Styling
echo '<link rel="stylesheet" href="http://' . $home . 'style.min.css">';

// You can include a more readable version of the stylesheet if you want to edit it
// echo '<link rel="stylesheet" href="http://' . $home . 'syle.css">';
// This big block of css the cool columns and responsive stuff. The bits where you can change colours come later.
// echo '<link rel="stylesheet" href="http://' . $home . 'col.min.css">';

// Now you can add your own stylesheet. If you want.
// The best way is to do it like i did above
// include 'themes/style.php';
// Or just some random code. Google Analytics, maybe?
// echo '<script>var _gaq=_gaq||[];_gaq.push(["_setAccount","UA-12345678-9"]);_gaq.push(["_trackPageview"]);(function(){var b=document.createElement("script");b.type="text/javascript";b.async=true;b.src=("https:"==document.location.protocol?"https://ssl":"http://www")+".google-analytics.com/ga.js";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(b,a)})();</script>';

// Perhaps load some javascript into the header?
// include 'plugins/head.php';



// Time to figure out what they're after
if ( $requested == $install_directory ) { // BEGIN THE HOMEPAGE, or, more accurately, the index

	// Echo the title, which also tells us where we are, or where this script thinks it is
	echo '<title>' . $site_title . ' &nbsp;&middot;&nbsp; ' . $site_tagline . '</title>';

	// Include the header, because that's an important thing
	include 'header.php'; // It also closes the head and opens the body

	// This is the serious stuff, the loop that shows posts on the hompage
	echo '<div class="section group">'; // Open a section and a group, all in one
	// Read up on Responsive Grid System if you don't get that
	// Run the MYSQLI Query
	$result = mysqli_query($connection, "SELECT * FROM $table_name WHERE page='0' ORDER BY timestamp DESC", MYSQLI_STORE_RESULT);

	if (!$result) { // If there's nothing
		echo 'Invalid query: ' . mysqli_error() . ''; // Tell us what's wrong
	} else { // But while there is

		while($row = mysqli_fetch_row($result)) { // And while there's a row

			$title = html_entity_decode($row[0], ENT_QUOTES | ENT_HTML5, "UTF-8"); // Decode the title
			$excerpt = html_entity_decode($row[2], ENT_QUOTES | ENT_HTML5, "UTF-8"); // Decode the excerpt

			echo '<div class="col span_1_of_3"><div class="padten">'; // Take up one-third, and leave some space

			echo '<h2><a href="http://' . $home . $row[1] . '">' . $title . '</a></h2>'; // Echo the title and link it to it's own special page

			echo $excerpt; // Echo it. Nothing special, no wrapping or padding, just a nice, clean, echo.
			echo '<div class="clear"></div></div></div>'; // Clear the div (you might have floated something, you) get rid of the space and get out of the third

			$posts_printed = $posts_printed+1; // Add one, becuase you printed another post.
			$post_priv = $posts_printed/$posts_divider; // See if this post is a multiple of three

			if (is_int($post_priv)) { // If it is
				echo '</div><div class="section group">'; // Start a new group
			} // All good groups come in threes

		} // And start again

		mysqli_free_result($result); // Get rid of the results
		echo '</div>'; // END THE HOMEPAGE
		// That's the end of the hompage, i hope you enjoyed the show
		// The footer will be requested, but not until after everything else, because, well, they all need it

	}



} elseif ( $requested == $install_directory . $page_login ) { // BEGIN THE LOGIN PAGE

	// Echo the title, which also tells us where we are, or where this script thinks it is
	echo '<title>Login &nbsp;&middot;&nbsp; ' . $site_title . '</title>';

	// Include the header, because that's an important thing
	include 'header.php'; // It also closes the head and opens the body

	if ( $auth == 'true' ) { // Are you logged in already?
		echo '<h2>You\'re already logged in</h2>'; // What are you doing here then?
		echo '<h3><a href="http://' . $home . $page_admin . '">Admin</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>'; // Seriously, go away.
	} else { // No?
		echo '<form class="span_1_of_1" action="' . $do_login . '" method="post"><h3>Username</h3><input type="text" name="user" /><h3>Password</h3><input type="password" name="pass" /><input type="submit" value="Login" /></form>'; // Okay, login then
	} // END THE LOGIN PAGE



} elseif ( $requested == $install_directory . $do_login ) { // BEGIN THE DOLOGIN PAGE

	// Echo the title, which also tells us where we are, or where this script thinks it is
	echo '<title>Logged In &nbsp;&middot;&nbsp; ' . $site_title . '</title>';

	// Include the header, because that's an important thing
	include 'header.php'; // It also closes the head and opens the body

	if ( $auth == 'true' ) {
		echo '<h2>Logged in</h2>'; // You logged in!
		echo '<h3><a href="http://' . $home . $page_admin . '">Admin</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>'; // What now?
	} else {
		$user = htmlentities($_POST['user'], ENT_QUOTES | ENT_HTML5, "UTF-8");
		if ( $user == $actual_user ) {
			$pass = $_POST['pass'] . $actual_salt; // Append the salt to the post_pass
			$hash = hash('sha256', $pass); // Hash the that
			if ( $hash == $actual_pass ) { // If it's right
				echo '<h2>Logged in</h2>'; // You logged in!
				echo '<h3><a href="http://' . $home . $page_admin . '">Admin</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>'; // What now?
			} else {
			echo '<h2>Incorrect Password</h2>';
			echo '<h3><a href="http://' . $home . $page_login . '">Try again</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>';
			}
		} else {
			echo '<h2>Incorrect Username</h2>';
			echo '<h3><a href="http://' . $home . $page_login . '">Try again</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>';
		}
	} // END THE DOLOGIN PAGE



} elseif ( $requested == $install_directory . $page_logout ) { // BEGIN THE LOGOUT PAGE

	// Echo the title, which also tells us where we are, or where this script thinks it is
	echo '<title>Logout &nbsp;&middot;&nbsp; ' . $site_title . '</title>';

	// Include the header, because that's an important thing
	include 'header.php'; // It also closes the head and opens the body

	if ( $auth == 'true' ) {
		echo '<h2>Log Out?</h2>';
		echo '<h3><a href="http://' . $home . $do_logout . '">Yes, log me out</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">No, Return home</a></h3>';
	} else {
		echo '<h2>You aren\'t logged in</h2>';
		echo '<h3><a href="http://' . $home . $homepage . '">Return home</a></h3>';
	} // END THE LOGOUT PAGE



} elseif ( $requested == $install_directory . $do_logout ) { // BEGIN THE DOLOGOUT PAGE

	// Echo the title, which also tells us where we are, or where this script thinks it is
	echo '<title>Logged Out &nbsp;&middot;&nbsp; ' . $site_title . '</title>';

	// Include the header, because that's an important thing
	include 'header.php'; // It also closes the head and opens the body

	if ( $auth == 'true' ) {
		echo '<h2>Not logged out</h2>';
		echo '<h3><a href="http://' . $home . $homepage . '">Return home</a></h3>';
	} else {
		echo '<h2>Logged Out</h2>';
		echo '<h3><a href="http://' . $home . $homepage . '">Return home</a></h3>';
	} // END THE DOLOGOUT PAGE



} elseif ( $requested == $install_directory . $page_admin ) { // BEGIN THE ADMIN PAGE

	// Echo the title, which also tells us where we are, or where this script thinks it is
	echo '<title>Administration &nbsp;&middot;&nbsp; ' . $site_title . '</title>';

	// Include the header, because that's an important thing
	include 'header.php'; // It also closes the head and opens the body

	if ( $auth == 'true' ) {
		echo '<h2>Administration</h2>';
		
		$result = mysqli_query($connection, "SELECT * FROM $table_name ORDER BY timestamp DESC", MYSQLI_STORE_RESULT);

		if (!$result) { // If there's nothing
			echo 'Invalid query: ' . mysqli_error() . ''; // Tell us what's wrong
		} else {

			$num_results = mysqli_num_rows($result);

			if ($num_results == 0){ // Check there a result

				// If not, show a "sorry" page
				echo '<h2>Sorry, no posts found.</h2>';
				// A search option would be useful
				// But we don't have one
				// Instead, show a link home
				echo '<h3><a href="http://' . $home . $homepage . '">Return home</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $page_create . '">Create a Post</a></h3>';

			} else { // If it was none of those, then it must be a post.

				echo '<h3 class="right"><a href="http://' . $home . $page_create . '">Create a Post</a></h3>';
				echo '<table><tr><th>Title</th><th>Page</th><th>Navigation</th><th>Timestamp</th></tr>';
				while($row = mysqli_fetch_row($result)) { // Fetch the result
					$title = html_entity_decode($row[0], ENT_QUOTES | ENT_HTML5, "UTF-8");
					echo '<tr><td>' . $title . '</td><td>';
					if ( $row[6] == "1" ) {
						echo "Yes";
					} else { 
						echo "No";
					}
					echo '</td><td>';
					if ( $row[7] == "1" ) {
						echo "Yes";
					} else { 
						echo "No";
					}
					echo '</td><td>' . $row[5] . '</td>';
					echo '<td><form action="' . $page_edit . '" method="post"><input type="hidden" name="url" value="' . $row[1] . '" /><input type="submit" class="no" value="Edit" /></form></td>';
					echo '<td><form action="' . $page_delete . '" method="post"><input type="hidden" name="url" value="' . $row[1] . '" /><input type="submit" class="no" value="Delete" /></form></td></tr>';
				}
				echo '</table>';
				echo '<h3 class="right"><a href="http://' . $home . $page_create . '">Create a Post</a></h3>';

				mysqli_free_result($result);

			}
		}
		
	} else {
		echo '<div class="section group"><div class="col span_1_of_1"><div class="padten"><h2>Access Denied</h2></div></div></div><div class="section group"><div class="col span_1_of_1"><div class="padten"></div><h3><a href="http://' . $home . $page_login . '">Login</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3></div></div>';
	} // END THE ADMIN PAGE


} elseif ( $requested == $install_directory . $page_create ) { // BEGIN THE CREATE PAGE

	// Echo the title, which also tells us where we are, or where this script thinks it is
	echo '<title>Create a new Post &nbsp;&middot;&nbsp; ' . $site_title . '</title>';

	// Include the header, because that's an important thing
	include 'header.php'; // It also closes the head and opens the body
	
	if ( $auth == 'true' ) {

		echo '<h2>Create a Post</h2>';
		echo '<form action="' . $do_create . '" class="span_1_of_1" method="post"><div class="section group"><div class="col span_1_of_3"><h3 class="no">Title</h3><p>The title of your page. You can use spans to add color or other styling, but make sure you close them, and don\'t use divs, it\'ll break out of the header they\'re printed into.</p></div><div class="col span_2_of_3"><h3 class="no"><input type="text" name="title" /></h3></div></div><div class="section group"><div class="col span_1_of_3"><h3 class="no">url</h3><p>The url of your page. You shouldn\'t put and special characters in here, as it might stop your visitors browsers loading the page. Stick to numbers, letter, underscrores and hyphens. Whether or not capitals matter depend on your server. Do not use: login, dologin, admin, logout, dologout, create, docreate, edit, doedit, delete, or dodelete.</p></div><div class="col span_2_of_3"><h3 class="no"><input type="text" name="url" /></h3></div></div><div class="section group"><div class="col span_1_of_3"><h3 class="no">Excerpt</h3><p>You can use HTML here, but be wary. This is the snippet of text displayed on the homepage. this isn\'t used for pages.</p></div><div class="col span_2_of_3"><textarea cols="40" rows="5" name="excerpt"></textarea></div></div><div class="section group"><div class="col span_1_of_3"><h3 class="no">Content</h3><p>Go to town, whatever you want. Here, your creativity is the only limitation. And server memory allocation.</p></div><div class="col span_2_of_3"><textarea cols="40" rows="20" name="content"></textarea></div></div><div class="section group"><div class="col span_1_of_3"><h4 class="no">Category</h4><p>If you want to use categoris (some of the code is there, but commented out) then this is important. If not, don\'t worry. Hell, leave it blank! Go wild!</p></div><div class="col span_2_of_3"><h4 class="no"><input type="text" name="category" /></h4></div></div><div class="section group"><div class="col span_1_of_3"><h4 class="no">Timestamp</h4><p>You can edit this to lie about when you posted or effect the order of the posts on the homepage, but it needs to be in the format 2008-07-19 15:24:36 or it will be ignored.</p></div><div class="col span_2_of_3"><h4 class="no"><input type="text" name="timestamp" /></h4></div></div><div class="section group"><div class="col span_1_of_3"><h4 class="no">Options</h4></div><div class="col span_1_of_3"><h4><input type="checkbox" name="page" value="1" /> Page (don\'t list on the homepage)</h4></div><div class="col span_1_of_3"><h4><input type="checkbox" name="nav" value="1"> Appear in Navigation</h4></div></div><h3><input type="submit" class="no" value="Create" /> &nbsp;&middot;&nbsp; <a href="http://' . $home . $page_admin . '">No, Return to Admin</a></h3></form>';

		
	} else {
		echo '<h2>You can\'t edit, you\'re not logged in</h2>';
		echo '<h3><a href="http://' . $home . $page_login . '">Login</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>';
	} // END THE CREATE PAGE

} elseif ( $requested == $install_directory . $do_create ) { // BEGIN THE DOCREATE PAGE

	// Echo the title, which also tells us where we are, or where this script thinks it is
	echo '<title>Post Created &nbsp;&middot;&nbsp; ' . $site_title . '</title>';

	// Include the header, because that's an important thing
	include 'header.php'; // It also closes the head and opens the body

	if ( $auth == 'true' ) { // If you're logged in 
		$title = htmlentities($_POST['title'], ENT_QUOTES | ENT_HTML5, "UTF-8"); // Encode the title
		$url = urlencode($_POST['url']); // Encode the URL
		$excerpt = htmlentities($_POST['excerpt'], ENT_QUOTES | ENT_HTML5, "UTF-8"); // Encode the excerpt
		$content = htmlentities($_POST['content'], ENT_QUOTES | ENT_HTML5, "UTF-8"); // Encode the content
		$category = htmlentities($_POST['category'], ENT_QUOTES | ENT_HTML5, "UTF-8"); // Encode the category
		if ( isset($_POST['timestamp']) && !empty($_POST['timestamp'])) { // If you sent a timestamp
			$timestamp = htmlentities($_POST['timestamp'], ENT_QUOTES | ENT_HTML5, "UTF-8"); // Encode it
		} else { // If not
			$timestamp = date("Y-m-d H:i:s"); // Don't, just use the date in the format 2013-02-18 15:24:44
		} // And then
		if ( isset( $_POST['page'] ) ) { // If you ticked the page box
			$page = '1'; // Set the page as one
		} else { // And if not
			$page = '0'; // Set it to zero
		} // And another one
		if ( isset( $_POST['nav'] ) ) { // Nav this time
			$nav = '1'; // Set to one
		} else { // Or
			$nav = '0'; // Set to zero
		} //  Your choice
		$result = mysqli_query($connection, "INSERT INTO $table_name (title, url, excerpt, content, category, timestamp, page, nav) VALUES ('$title', '$url', '$excerpt', '$content', '$category', '$timestamp', '$page', '$nav') ", MYSQLI_STORE_RESULT); // Insert those into the table
		if (!$result) { // If there's nothing
			echo 'Invalid query: ' . mysqli_error() . ''; // Tell us what's wrong
		} else { // But if it worked
			mysqli_free_result($result); // Get rid of the result
			echo '<h2>Post Created</h2>'; // Tell us it worked
			echo '<h3><a href="http://' . $home . $page_admin . '">Admin</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>';
		}
	} else { // If you're not allowed to be here
		echo '<h2>You can\'t create a post, you\'re not logged in</h2>';
		echo '<h3><a href="http://' . $home . $page_login . '">Login</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>';
	} // END THE DOCREATE PAGE



} elseif ( $requested == $install_directory . $page_edit ) { // BEGIN THE EDIT PAGE

	// Echo the title, which also tells us where we are, or where this script thinks it is
	echo '<title>Edit a Post &nbsp;&middot;&nbsp; ' . $site_title . '</title>';

	// Include the header, because that's an important thing
	include 'header.php'; // It also closes the head and opens the body

	if ( $auth == 'true' ) {
		$editurl = $_POST['url'];
		echo '<h2>Edit a Post</h2>';
		
		$result = mysqli_query($connection, "SELECT * FROM $table_name WHERE url='$editurl' ORDER BY timestamp DESC", MYSQLI_STORE_RESULT); // Insert those into the table

		if (!$result) { // If there's nothing

			echo 'Invalid query: ' . mysqli_error() . ''; // Tell us what's wrong

		} else { // But if it worked

			// Set a variable for then number of results,
			// so we can check there was one.
			$num_results = mysqli_num_rows($result); 

			if ($num_results == 0){ // Check there a result
				// If not, show a "sorry" page
				echo '<h2>Sorry, we can\'t find that post.</h2>';
				// A search option would be useful
				// But we don't have one
				// Instead, show a link home
				echo '<h3><a href="http://' . $home . $homepage . '">Return home</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $page_admin . '">Return to Admin</a></h3>';

			} else { // If it was none of those, then it must be a post.
				while($row = mysqli_fetch_row($result)) { // Fetch the result
					if ($row[6] == '1') {
						$page = 'checked';
					} else {
						$page = '';
					}
					if ($row[7] == '1') {
						$nav = 'checked';
					} else {
						$nav = '';
					}
					echo '<form action="' . $do_edit . '" class="span_1_of_1" method="post"><div class="section group"><div class="col span_1_of_3"><h3 class="no">Title</h3><p>The title of your page. You can use spans to add color or other styling, but make sure you close them, and don\'t use divs, it\'ll break out of the header they\'re printed into.</p></div><div class="col span_2_of_3"><h3 class="no"><textarea class="center" cols="40" rows="1" name="title">' . $row[0] . '</textarea></h3></div></div><div class="section group"><div class="col span_1_of_3"><h3 class="no">url</h3><p>The url of your page. You shouldn\'t put and special characters in here, as it might stop your visitors browsers loading the page. Stick to numbers, letter, underscrores and hyphens. Whether or not capitals matter depend on your server. Do not use: login, dologin, admin, logout, dologout, create, docreate, edit, doedit, delete, or dodelete.</p></div><div class="col span_2_of_3"><h3 class="no"><textarea class="center" cols="40" rows="1" name="url">' . $row[1] . '</textarea></h3></div></div><div class="section group"><div class="col span_1_of_3"><h3 class="no">Excerpt</h3><p>You can use HTML here, but be wary. This is the snippet of text displayed on the homepage. this isn\'t used for pages.</p></div><div class="col span_2_of_3"><textarea cols="40" rows="5" name="excerpt">' . $row[2] . '</textarea></div></div><div class="section group"><div class="col span_1_of_3"><h3 class="no">Content</h3><p>Go to town, whatever you want. Here, your creativity is the only limitation. And server memory allocation.</p></div><div class="col span_2_of_3"><textarea cols="40" rows="20" name="content">' . $row[3] . '</textarea></div></div><div class="section group"><div class="col span_1_of_3"><h4 class="no">Category</h4><p>If you want to use categoris (some of the code is there, but commented out) then this is important. If not, don\'t worry. Hell, leave it blank! Go wild!</p></div><div class="col span_2_of_3"><h4 class="no"><textarea class="center" cols="40" rows="1" name="category">' . $row[4] . '</textarea></h4></div></div><div class="section group"><div class="col span_1_of_3"><h4 class="no">Timestamp</h4><p>You can edit this to lie about when you posted or effect the order of the posts on the homepage, but it needs to be in the format 2008-07-19 15:24:36 or it will be ignored.</p></div><div class="col span_2_of_3"><h4 class="no"><input type="text" name="timestamp" value="' . $row[5] . '" /></h4></div></div><div class="section group"><div class="col span_1_of_3"><h4 class="no">Options</h4></div><div class="col span_1_of_3"><h4><input type="checkbox" name="page" value="1" ' . $page . ' /> Page (don\'t list on the homepage)</h4></div><div class="col span_1_of_3"><h4><input type="checkbox" name="nav" value="1" ' . $nav . '> Appear in Navigation</h4></div></div><input type="hidden" name="editurl" value="' . $editurl . '" /><h3><input type="submit" class="no" value="Edit" /> &nbsp;&middot;&nbsp; <a href="http://' . $home . $page_admin . '">No, Return to Admin</a></h3></form>';
				}
			}

			mysqli_free_result($result); // Get rid of the result

		}
	} else {
		echo '<h2>You can\'t edit, you\'re not logged in</h2>';
		echo '<h3><a href="http://' . $home . 'login">Login</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>';
	} // END THE EDIT PAGE



} elseif ( $requested == $install_directory . $do_edit ) { // BEGIN THE DOEDIT PAGE

	// Echo the title, which also tells us where we are, or where this script thinks it is
	echo '<title>Post Edited &nbsp;&middot;&nbsp; ' . $site_title . '</title>';

	// Include the header, because that's an important thing
	include 'header.php'; // It also closes the head and opens the body

	if ( $auth == 'true' ) {
		$editurl = $_POST['editurl'];
		$title = htmlentities($_POST['title'], ENT_QUOTES | ENT_HTML5, "UTF-8");
		$url = urlencode($_POST['url']);
		$excerpt = htmlentities($_POST['excerpt'], ENT_QUOTES | ENT_HTML5, "UTF-8");
		$content = htmlentities($_POST['content'], ENT_QUOTES | ENT_HTML5, "UTF-8");
		$category = htmlentities($_POST['category'], ENT_QUOTES | ENT_HTML5, "UTF-8");
		if ( isset($_POST['timestamp']) && !empty($_POST['timestamp'])) {
			$timestamp = htmlentities($_POST['timestamp'], ENT_QUOTES | ENT_HTML5, "UTF-8");
		} else {
			$timestamp = date("Y-m-d H:i:s"); // 2013-02-18 15:24:44
		}
		if ( isset( $_POST['page'] ) ) {
			$page = '1';
		} else {
			$page = '0';
		}
		if ( isset( $_POST['nav'] ) ) {
			$nav = '1';
		} else {
			$nav = '0';
		}

		$result = mysqli_query($connection, "UPDATE $table_name SET title='$title',url='$url',excerpt='$excerpt',content='$content',category='$category',timestamp='$timestamp',page='$page',nav='$nav' WHERE url='$editurl'", MYSQLI_STORE_RESULT);

		if (!$result) { // If there's nothing

			echo 'Invalid query: ' . mysqli_error() . ''; // Tell us what's wrong

		} else {

			mysqli_free_result($result);

			echo '<h2>Post Edited</h2>';
			echo '<h3><a href="http://' . $home . $page_admin . '">Admin</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>';

		}
	} else {
		echo '<h2>You can\'t edit a post, you\'re not logged in</h2>';
		echo '<h3><a href="http://' . $home . $page_login . '">Login</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>';
	} // END THE DOEDIT PAGE



} elseif ( $requested == $install_directory . $page_delete ) { // BEGIN THE DELETE PAGE

	// Echo the title, which also tells us where we are, or where this script thinks it is
	echo '<title>Delete Post &nbsp;&middot;&nbsp; ' . $site_title . '</title>';

	// Include the header, because that's an important thing
	include 'header.php'; // It also closes the head and opens the body

	if ( $auth == 'true' ) {
		echo '<h2>Delete a Post</h2><h3>Are you sure?</h3><h3>This can\'t be undone.</h3>';
		echo '<h3><form action="' . $do_delete . '" method="post"><input type="hidden" name="url" value="' . $_POST['url'] . '" /><input type="submit" class="no" value="Yes, Delete" /></form> &nbsp;&middot;&nbsp; <a href="http://' . $home . $page_admin . '">No, Return to Admin</a></h3>';
	} else {
		echo '<h2>You can\'t delete a post, you\'re not logged in</h2>';
		echo '<h3><a href="http://' . $home . $page_login . '">Login</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>';
	} // END THE DELETE PAGE



} elseif ( $requested == $install_directory . $do_delete ) { // BEGIN THE DODELETE PAGE

	// Echo the title, which also tells us where we are, or where this script thinks it is
	echo '<title>Post Deleted &nbsp;&middot;&nbsp; ' . $site_title . '</title>';

	// Include the header, because that's an important thing
	include 'header.php'; // It also closes the head and opens the body

	if ( $auth == 'true' ) {
		$deleteurl = $_POST['url'];
		$result = mysqli_query($connection, "DELETE FROM $table_name WHERE url='$deleteurl'", MYSQLI_STORE_RESULT);

		if (!$result) { // If there's nothing
			echo 'Invalid query: ' . mysqli_error() . ''; // Tell us what's wrong
		} else { // But while there is
			echo '<h2>Post Deleted</h2>';
			echo '<h3><a href="http://' . $home . $page_admin . '">Admin</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>';
		}
	} else {
		echo '<h2>You can\'t delete a post, you\'re not logged in</h2>';
		echo '<h3><a href="http://' . $home . $page_login . '">Login</a> &nbsp;&middot;&nbsp; <a href="http://' . $home . $homepage . '">Return home</a></h3>';
	} // END THE DODELETE PAGE



} else { // BEGIN THE POST PAGE
	$location = $requested;
	$posturl = str_ireplace($install_directory, "", $location);
	// Run the MYSQLI Query
	$result = mysqli_query($connection, "SELECT * FROM $table_name WHERE url='$posturl' ORDER BY timestamp DESC", MYSQLI_STORE_RESULT);

	if (!$result) { // If there's nothing
		echo 'Invalid query: ' . mysqli_error() . ''; // Tell us what's wrong
	} else { // But while there is

		$num_results = mysqli_num_rows($result); 

		if ($num_results == 0){ 

			echo '<title>Post not found &nbsp;&middot;&nbsp; ' . $site_title . '</title>';// Include the header, because that's an important thing

			include 'header.php'; // It also closes the head and opens the body

			// Open up a section, span the full thing, and add some padding
			echo '<div class="section group"><div class="col span_1_of_1"><div class="padten">';
		
			// If not, show a "sorry" page
			echo '<h2>Sorry, we can\'t find that post.</h2>';
			// A search option would be useful
			// But we don't have one
			// Instead, show a link home
			echo '<h3><a href="http://' . $home . $homepage . '">Return home</a></h3>';

			// Close that secion we opened, remember that?
			echo '<div class="clear"></div></div></div></div>';

		} else { // If it was none of those, then it must be a post.

			while($row = mysqli_fetch_row($result)) { // And while there's a row

				$title = html_entity_decode($row[0], ENT_QUOTES | ENT_HTML5, "UTF-8");
				$title_stripped = strip_tags ($title);

				$content = html_entity_decode($row[3], ENT_QUOTES | ENT_HTML5, "UTF-8");

				echo '<title>' . $title_stripped . ' &nbsp;&middot;&nbsp; ' . $site_title . '</title>';

				include 'header.php'; // It also closes the head and opens the body

				// Open up a section, span the full thing, and add some padding
				echo '<div class="section group"><div class="col span_1_of_1"><div class="padten">';

				echo '<h2>' . $title . '</h2>'; // Echo the title
				echo $content; // And the content

				// Plugins might want to add in scripts or boxes here
				// include 'plugins/post.php'; // For example, addthis.

				// Close that secion we opened, remember that?
				echo '<div class="clear"></div></div></div></div>';

			} // And start again

			mysqli_free_result($result); // Get rid of the results
		
		}
	} 
	
} // END THE POST PAGE



// Close those #maincontentcontainer and #maincontent, which we opened right near the beginning
echo '</div></div>';

include 'footer.min.php';
// There's a version you can edit a bit more too
// include 'footer.php';

echo '</div></body>'; // Close #wrapper and body

// include 'plugins/last.php'; // Include and scripts plugins want us to load at the end.

echo '</html>'; // Close 

// That's is, it's all over

?>
