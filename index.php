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
		<div id="drag" class="panel tall" draggable="true" ondragstart="dragStart(event)">
			Drag me
		</div>
		<div id="target" class="panel tall" ondrop="drop(event)" ondragover="allowDrop(event)">
			In here
		</div>
	</div>

	<?php
		// Error checking
		error_reporting(0);
		ini_set('display_errors', 0);

		session_start();

		// True if the form has both username and password filled in
		$formFilled = array_key_exists('name', $_POST) 
				&& array_key_exists('pass', $_POST) 
				&& $_POST['name'] != ""
				&& $_POST['pass'] != "";

		// Check if name field is empty on log in or registration
		if ((array_key_exists('register', $_GET) || array_key_exists('login', $_GET)) 
				&& !$formFilled) {
			echo '<div class="warning">Invalid username or password</div>';
		}

		if (array_key_exists('register', $_GET) && $formFilled) {

			// Get username and hashed password
			$_SESSION['name'] = $_POST['name'];
			$pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);

			$db = new SQLite3('database.sqlite', SQLITE3_OPEN_READWRITE);

			if ($db) {
				// Check if user exists
				$exists = $db->query(
					'SELECT * FROM USERS WHERE NAME = \'' .
					SQLite3::escapeString($_SESSION['name']) .
					'\';'
				);

				if ($exists->fetchArray()) {
					echo "<div class=\"warning\">The name '" . 
						htmlspecialchars($_SESSION['name']) . 
						"' is already taken!</div>";
				}else {
					// Else, add user to database
					$query = 'INSERT INTO USERS (NAME, PASS) VALUES (\'' . 
						SQLite3::escapeString($_SESSION['name']) . '\', \'' . 
						SQLite3::escapeString($pass) . '\');';

					$db->query($query);

					$_SESSION['loggedIn'] = true;
				}
			}else {
				echo 'Connection to database failed!\n';
			}
		}else if (array_key_exists('login', $_GET) && $formFilled) {
			$_SESSION['name'] = $_POST['name'];
			$pass = $_POST['pass'];

			$db = new SQLite3('database.sqlite', SQLITE3_OPEN_READWRITE);

			if ($db) {
				// Find the user in the database
				$query = $db->query('SELECT * FROM USERS WHERE NAME = \'' .
					SQLite3::escapeString($_SESSION['name']) . '\';');
	
				if ($user = $query->fetchArray()) {
					if (password_verify($pass, $user['PASS'])) {
						$_SESSION['loggedIn'] = true;
					}else {
						echo '<div class="warning">Invalid username or password</div>';
					}
				}else { // No user with that name exists
					echo '<div class="warning">Invalid username or password</div>';
				}
			}else {
				echo 'Connection to database failed!\n';
			}
		}else if (array_key_exists('logout', $_GET)) {
			$_SESSION['loggedIn'] = false;
			$_SESSION['name'] = '';
		}

		if ($_SESSION['loggedIn']) {
			echo '<div class="info">' .
				'<form action="index.php" method="get">' .
				'You are logged in as \'' . 
				htmlspecialchars($_SESSION['name']) . '\' ' . 
				'<input type="submit" name="logout" value="Log out" />' .
				'</form></div>';
		}else {
			echo file_get_contents('login.html');
		}
	?>

	<div id="list" class="flex-container">
		<table class="panel">
			<tr>
				<th colspan="4" class="label">Products</th>
			</tr>
			<tr>
				<th>Name</th>
				<th>Description</th>
				<th>Maker</th>
				<th>Price</th>
			</tr>
			<tbody id="productTable">
			</tbody>
		</table>
	</div>

	<div class="flex-container">
		<table class="panel">
			<tr>
				<th colspan="5" class="label">Add a new product</th>
			</tr>
	
			<tr id="productForm">
				<td><input type="text" placeholder="Name" required></td>
				<td><input type="text" placeholder="Description" required></td>
				<td><input type="text" placeholder="Maker" required></td>
				<td><input type="text" placeholder="Price" required></td>
				<td><input type="submit" value="Add" onclick="addProduct()"></td>
			</tr>
		</table>
	</div>

</body>
</html>
