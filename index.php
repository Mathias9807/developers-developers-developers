<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Duva test</title>
	<link href="stylesheets/screen.css" rel="stylesheet">
	<script src="script.js"></script>
</head>
<body onload="docLoaded()">
	<div id="errorbox"></div>

	<div class="flex-container">
		<div id="drag" class="box" draggable="true" ondragstart="dragStart(event)">
			Drag me
		</div>
		<div id="target" class="box" ondrop="drop(event)" ondragover="allowDrop(event)">
			In here
		</div>
	</div>

	<?php
		// Enable error checking
		error_reporting(E_ALL);
		ini_set('display_errors', 1);

		// True if the form has both username and password filled in
		$formFilled = array_key_exists('name', $_POST) 
				&& array_key_exists('pass', $_POST) 
				&& $_POST['name'] != ""
				&& $_POST['pass'] != "";

		$loggedIn = false;

		// Check if name field is empty on log in or registration
		if ((array_key_exists('register', $_GET) 
				|| array_key_exists('login', $_GET)) 
				&& !$formFilled) {
			echo '<div class="warning">Invalid username or password</div>';
		}

		if (array_key_exists('register', $_GET) && $formFilled) {

			// Get username and hashed password
			$name = $_POST['name'];
			$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

			$db = new SQLite3('database.sqlite', SQLITE3_OPEN_READWRITE);

			if ($db) {
				// Check if user exists
				$exists = $db->query(
					'SELECT * FROM USERS WHERE NAME = \'' .
					SQLite3::escapeString($name) .
					'\';'
				);

				if ($exists->fetchArray()) {
					echo "<div class=\"warning\">The name '" . 
						htmlspecialchars($name) . 
						"' is already taken!</div>";
				}else {
					// Else, add user to database
					$query = 'INSERT INTO USERS (NAME, PASS) VALUES (\'' . 
						SQLite3::escapeString($name) . '\', \'' . 
						SQLite3::escapeString($pass) . '\');';

					$db->query($query);

					$loggedIn = true;
				}
			}else {
				echo 'Connection to database failed!\n';
			}
		}else if (array_key_exists('login', $_GET) && $formFilled) {
			$name = $_POST['name'];
			$pass = $_POST['pass'];

			$db = new SQLite3('database.sqlite', SQLITE3_OPEN_READWRITE);

			if ($db) {
				$query = $db->query('SELECT * FROM USERS WHERE NAME = \'' .
					SQLite3::escapeString($name) . '\';');
	
				if ($user = $query->fetchArray()) {
					if (password_verify($pass, $user['PASS'])) {
						$loggedIn = true;
					}else {
						echo '<div class="warning">Invalid username or password</div>';
					}
				}else { // No user with that name exists
					echo '<div class="warning">Invalid username or password</div>';
				}
			}else {
				echo 'Connection to database failed!\n';
			}
		}

		if ($loggedIn) {
			echo '<div class="info">' .
				'<form action="index.php" method="get">' .
				'You are logged in as \'' . 
				htmlspecialchars($name) . '\' ' . 
				'<input type="submit" value="Log out" />' .
				'</form></div>';
		}else {
			echo file_get_contents('login.html');
		}
	?>

	<div id="list" class="flex-container">
		<table id="productTable">
			<tr>
				<th colspan="4" class="label">Products</th>
			</tr>
			<tr>
				<th>Name</th>
				<th>Description</th>
				<th>Maker</th>
				<th>Price</th>
			</tr>
		</table>
	</div>

	<div class="flex-container">
		<table class="panel">
			<tr colspan="5"><h3>Add a new product</h3></tr>
	
			<tr>
				<td><input type="text" placeholder="Name" required></td>
				<td><input type="text" placeholder="Description" required></td>
				<td><input type="text" placeholder="Maker" required></td>
				<td><input type="text" placeholder="Price" required></td>
				<td><input type="submit" value="Add"></td>
			</tr>
		</table>
	</div>

</body>
</html>
