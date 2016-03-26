function docLoaded() {
	getProductList();
}

function drop(event) {
	event.preventDefault();

	var draggedObject = document.getElementById(event.dataTransfer.getData("text"));
	document.getElementById("target").innerHTML = draggedObject.innerHTML;
	draggedObject.innerHTML = "";
	
	// Switch opacity values
	draggedObject.style.opacity = "0.5";
	document.getElementById("target").style.opacity = "1";
	draggedObject.setAttribute("draggable", "false");
}

function allowDrop(event) {
	event.preventDefault();
}

function dragStart(event) {
	event.dataTransfer.setData("text", event.target.id);
}

function getProductList() {
	var request = new XMLHttpRequest();

	request.onreadystatechange = function() {
		if (request.readyState == 4 && request.status == 200) {
			var listNode = document.getElementById("productTable");
			var reply = JSON.parse(request.responseText);

			for (i = 0; i < reply.length; i++) {
				let entry = document.createElement("tr");
				entry.className = "entry";
				listNode.appendChild(entry);

				let cells = [];
				for (j = 0; j < 4; j++) 
					cells.push(document.createElement("td"));

				cells[0].innerHTML = reply[i].PRODUCT.NAME;
				cells[1].innerHTML = reply[i].PRODUCT.TEXTVAL1;
				cells[2].innerHTML = reply[i].PRODUCT.TEXTVAL2;
				cells[3].innerHTML = reply[i].PRODUCT.PRICE.toFixed(2) + ":-";

				for (j = 0; j < 4; j++)
					entry.appendChild(cells[j]);
			}
		}
		document.getElementById("errorbox").innerHTML = request.responseText;
	};

	request.open("GET", "ajax.py?query=listProducts", true);
	request.send();
}

