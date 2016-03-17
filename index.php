<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Duva test</title>
	<link href="stylesheets/screen.css" rel="stylesheet">
</head>
<body>
	<script src="script.js"></script>
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

		if (array_key_exists('register', $_GET)) {
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
					echo "<div style=\"color: #F00; text-align: center;\">The name '" . htmlspecialchars($name) . "' is already taken!</div>";
				}else {
					// Else, add user to database
					$query = 'INSERT INTO USERS (NAME, PASS) VALUES (\'' . 
						SQLite3::escapeString($name) . '\', \'' . 
						SQLite3::escapeString($pass) . '\');';

					// Print query first (to aid debugging)
					echo htmlspecialchars($query);
					$db->query($query);
				}
			}else {
				echo 'Connection to database failed!\n';
			}
		}
	?>

	<div class="flex-container">
		<div class="panel">
		<form method="post" action="index.php?login">
			<h3>Login</h3>
			<input name="name" type="text" placeholder="Username"></input>
			<br>
			<input name="pass" type="password" placeholder="Password"></input>
			<br>
			<input type="submit" value="Login"></input>
		</form>
		</div>

		<div class="panel">
		<form method="post" action="index.php?register">
			<h3>Register</h3>
			<input name="name" type="text" placeholder="Username"></input>
			<br>
			<input name="pass" type="password" placeholder="Password"></input>
			<br>
			<input type="submit" value="Register"></input>
		</form>
		</div>
	</div>
</body>
</html>
