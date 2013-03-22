<?php

// Hello, welcome to Simplet, the teeny-tiny blog

// Copyright © 2013 eustasy
// Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
// The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.</p>THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.



// Site Information

$site_title = 'Simplet Blog Title'; // Your blog title (NO HTML), appears in the header
$site_tagline = 'The totally revolutionary open-source teeny-tiny blogging and content management system.'; // Your blog tagline (NO HTML), appears under the title
$site_description = '<p>This is the bit abut your blog. You should probably put something here.</p><p>Perhaps something about how awesome Simplet is?</p>'; // Your blog description, appears in the footer



// Installation Information

$install_directory = '/'; // This is your install directory. Use '/' for root, or '/blog/' for the directory blog

// Database Information
$database_host = 'localhost'; // Put the host of your satabase here
$database_user = 'simplet'; // The database user you want us to use. Try creating one with it's own database. SELECT, INSERT, UPDATE and DELETE Permissions Required
$database_pass = 'password'; // The password to the database for the user.
$database_name = 'simplet'; // The name of the database. You created a database, right?
$table_name = 'posts'; // The name of the table. You should import posts.sql BEFORE creating this, then rename it if you want.



// User Information

// Choose a username
// Put it here, in plain text
$actual_user = '';

// Generate a salt value
// You can add a salt to stop rainbow tables being used to crack the SHA256SUM below
$actual_salt = '';

// This is NOT your password.
// First, you need a random unique password.
// Then append the salt to the end of it and SHA256SUM the whole thing.
// That is what goes in here.
$actual_pass = '';

// This is your cookie value.
// If a user has an auth cookie with this value, then they are logged in.
// Change this value to a random string.
// It is effectively the key to your installation.
$actual_ckie = '';

// Generate SALT and Cookie values (and maybe a password too)
// https://secure.pctools.com/guides/password/

// Generate hashes
// http://www.fileformat.info/tool/hash.htm



// Locations

// These can be changed to make your installation more secure.
// It only works if you change all of them.
// They all link to the login page you see.
// Someone can't hack your login if they don't know where it is.
// NINJA STYLE!
$page_login = 'login';
$page_logout = 'logout';
$page_admin = 'admin';
$page_create = 'create';
$page_edit = 'edit';
$page_delete = 'delete';

// These are the action pages.
// You could change these too, so someone can't send their own (carefully crafted) POST variables to them
// ARTY CRAFTY.
$do_login = 'dologin';
$do_logout = 'dologout';
$do_create = 'docreate';
$do_edit = 'doedit';
$do_delete = 'dodelete';




// These don't need to be changed unless you edit other parts of the script
$home = $_SERVER['SERVER_NAME'] . $install_directory; // This figures out where we are, so it doesn't have to do it for each and every link
$posts_printed = 0; // Set the loops as being at the beginning
$posts_divider = 3; // Because it displays three wide on the home page, we need to insert some breakers every three posts to keep things neat
$requested = str_replace("'", "", $_SERVER['REQUEST_URI']);
$requested = str_replace('"', "", $requested);
// print $requested; // Check where we think we are, useful for debuggin post not found errors



// This checks to see if any pulgins want to load variables
// include 'plugins/variables.php'; // Well do they?



// Try to connect to the database immediately
$connection = mysqli_connect($database_username, $database_user, $database_pass, $database_name);
if (!$connection) {
	die('Connect Error: ' . mysqli_connect_error());
}

?>
