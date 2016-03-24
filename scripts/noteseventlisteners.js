	//event listeners for notes.php
	var instructorSelection = document.getElementById("instructor");
	var courseNameSelection = document.getElementById("courseName");

	instructorSelection.addEventListener("change", update, false);
	courseNameSelection.addEventListener("change", update, false);

	var buttons = document.getElementsByTagName("button");

	for(var i = 0; i < buttons.length; i++) {
			if (buttons[i].id.substr(0, 7) == "delete_") {
					buttons[i].addEventListener("mouseup", DeleteNote, false);
			}
	}
	//event listeners for createnotes.php
