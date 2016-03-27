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
			var children = listNode.getElementsByClassName("entry");

			// Remove previous entries
			while (children[0])
				listNode.removeChild(children[0]);

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
				cells[3].innerHTML = Number(reply[i].PRODUCT.PRICE).toFixed(2) + ":-";

				for (j = 0; j < 4; j++)
					entry.appendChild(cells[j]);
			}
		}
		// For debugging
		// document.getElementById("errorbox").innerHTML = request.responseText;
	};

	request.open("GET", "ajax.py?query=listProducts", true);
	request.send();
}

function addProduct() {
	var values = document.getElementById("productForm").getElementsByTagName("input");

	var product = {
		PRODUCT: {
			NAME: values[0].value,
			TEXTVAL1: values[1].value,
			TEXTVAL2: values[2].value, 
			PRICE: values[3].value
		}
	};

	var request = new XMLHttpRequest();

	request.onreadystatechange = function() {
		if (request.readyState == 4) {
			getProductList();
		}
	};

	request.open("POST", "ajax.py", true);
	request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	request.send("query=addProduct&p=" + JSON.stringify(product));
}

