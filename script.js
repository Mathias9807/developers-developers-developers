function drop(event) {
	event.preventDefault();

	var dragObject = document.getElementById(event.dataTransfer.getData("text"));
	document.getElementById("target").innerHTML = dragObject.innerHTML;
	dragObject.innerHTML = "";
	
	// Switch opacity values
	dragObject.style.opacity = "0.5";
	document.getElementById("target").style.opacity = "1";
	dragObject.setAttribute("draggable", "false");
}

function allowDrop(event) {
	event.preventDefault();
}

function dragStart(event) {
	event.dataTransfer.setData("text", event.target.id);
}

