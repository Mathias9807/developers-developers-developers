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

	<div class="flex-container">
		<div class="panel">
		<form>
			<h3>Login</h3>
			<input type="text" placeholder="Username"></input>
			<br>
			<input type="text" placeholder="Password"></input>
			<br>
			<input type="submit" value="Login"></input>
		</form>
		</div>

		<div class="panel">
		<form>
			<h3>Register</h3>
			<input type="text" placeholder="Username"></input>
			<br>
			<input type="text" placeholder="Password"></input>
			<br>
			<input type="Submit" value="Register" action="test.php" method="post"></input>
		</form>
		</div>
	</div>
</body>
</html>
