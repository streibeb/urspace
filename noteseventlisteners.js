	//event listeners for notes.php
	var instructorSelection = document.getElementById("instructor");
	var courseNameSelection = document.getElementById("courseName");

	instructorSelection.addEventListener("change", update, false);
	courseNameSelection.addEventListener("change", update, false);
	
	//event listeners for createnotes.php
