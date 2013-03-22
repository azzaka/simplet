<?php

require 'config.php';

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

			echo 'http://' . $home . $url . ' ';


		} // And start again

		mysqli_free_result($result); // Get rid of the results

	}
}

echo 'http://' . $home;

?>
