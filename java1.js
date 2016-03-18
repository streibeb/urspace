//access GET variables with javascript
//source: http://papermashup.com/read-url-get-variables-withjavascript/
function getUrlVars() {
	var vars = {};
	var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
		vars[key] = value;
	});
	return vars;
}





///////////////////////////////////////////////////////////////////////////////////////////////////////
//login page
///////////////////////////////////////////////////////////////////////////////////////////////////////





//chkName function event
//called on change event, updates html depending
// if user inputted valid email or not
function chkName(event){

	var errors = document.getElementById(event.currentTarget.id + "error");
	var str = event.currentTarget.value;

	if(!/\b[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z][a-zA-Z]?[a-zA-Z]?\b/.test(str) || /\s/.test(str))
	{

		errors.innerHTML = "Username incorrect. Please enter a valid email (name@domain.com).";
	}else{

		errors.innerHTML = "";
	}
}

//chkPass function event
//called on change event, updates html depending
// if user inputted valid password or not
function chkPass(event){
	var errors = document.getElementById(event.currentTarget.id + "error");
	var str = event.currentTarget.value;


	if(!/[^a-zA-Z]/.test(str) || str.length < 8 || /\s/.test(str))
	{

		errors.innerHTML = "Password incorrect. Must be at least 8 characters (no spaces) and at least 1 non-letter.";
	}else{

		errors.innerHTML = "";
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////////
//signup page
///////////////////////////////////////////////////////////////////////////////////////////////////////


//chkFname function event
//called on change event, updates html depending
// if user inputted valid first name or not
function chkFname(event){

	var errors = document.getElementById(event.currentTarget.id + "error");
	var str = event.currentTarget.value;

	if(!/^\S/.test(str) ||!/\S$/.test(str))
	{

		errors.innerHTML = "First name incorrect (no leading or trailing spaces).";
	}else{

		errors.innerHTML = "";
	}
}

//chkLname function event
//called on change event, updates html depending
// if user inputted valid last name or not
function chkLname(event){

	var errors = document.getElementById(event.currentTarget.id + "error");
	var str = event.currentTarget.value;

	if(!/^\S/.test(str) ||!/\S$/.test(str))
	{

		errors.innerHTML = "Last name incorrect (no leading or trailing spaces).";
	}else{

		errors.innerHTML = "";
	}
}

//chkDate function event
//called on change event, updates html depending
// if user inputted valid date or not
function chkDate(event){


	var errors = document.getElementById(event.currentTarget.id + "error");
	var str = event.currentTarget.value;

	if(!/\b[1-2][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]\b/.test(str))
	{
		errors.innerHTML = "Incorrect birthdate. Please use YYYY-MM-DD format.";
	}else{
		errors.innerHTML = "";
	}
}



//chkPass2 function event
//called on change event, updates html depending
// if user inputted valid password or not
// second password matches to first
function chkPass2(event){

	var errors = document.getElementById(event.currentTarget.id + "error");
	var pass1 = pass1Node.value;
	var pass2 = pass2Node.value;

	if(pass1 != pass2)
	{

		errors.innerHTML = "Passwords must match";
	}else{

		errors.innerHTML = "";
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////////
//comment page
///////////////////////////////////////////////////////////////////////////////////////////////////////

//chkComment function event
//called on change event, updates html depending
// if user inputted valid comment or not
function chkComment(event){


	var errors = document.getElementById(event.currentTarget.id + "error");
	var str = event.currentTarget.value;

	if(str.length > 500 || str == "")
	{

		comment1Error.innerHTML = "Invalid comment. Please input text (maximum 500 characters).";
	}else{

		comment1Error.innerHTML = "";
	}


}

///////////////////////////////////////////////////////////////////////////////////////////////////////
//search page
///////////////////////////////////////////////////////////////////////////////////////////////////////

//searchEventListeners fucntion
//function called to create event listeners
// for dynamically created content on search page
function searchEventListeners(){
			var rePosts = document.querySelectorAll('button.repostLink');
			for (var j = 0; j < rePosts.length; j++)
			rePosts[j].addEventListener('click', rePostSearch , false);

			var likeButtons = document.querySelectorAll('button.likeButton');
			for (var j = 0; j < likeButtons.length; j++) {
			likeButtons[j].addEventListener('click', searchlikeFunction , false);
			}
			var dislikeButtons = document.querySelectorAll('button.dislikeButton');
			for (var j = 0; j < dislikeButtons.length; j++) {
			dislikeButtons[j].addEventListener('click', searchdislikeFunction , false);
			}

	}



//chkSearch function event
//called on change event, updates html depending
// if user inputted valid search query or not
function chkSearch(event){

	var errors = document.getElementById(event.currentTarget.id + "error");
	var str = event.currentTarget.value;

	if(!/^\S/.test(str) ||!/\S$/.test(str))
	{

		errors.innerHTML = "Invalid search (no leading or trailing spaces allowed).";
	}else{

		errors.innerHTML = "";
	}
}


///////////////////////////////////////////////////////////////////////////////////////////////////////
//index page
///////////////////////////////////////////////////////////////////////////////////////////////////////

//chkComments function event
//called on change event, updates html depending
// if user inputted valid comments or not
function chkPost(event){

	var errorComments = document.getElementById("errorComments");
	var commentstr = commentsNode.value;

	if(commentstr.length > 500 || commentstr == "")
	{

		errorComments.innerHTML = "Invalid post. Please input text (maximum 500 characters).";
	}else{

		errorComments.innerHTML = "";
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////
//wall page
///////////////////////////////////////////////////////////////////////////////////////////////////////
//store wall posts in permanent global object
var globalArr = [];

//store latest post number in global variable
var latestPost = 0;

// store interval timer
var refreshIntervalId;




//commentFunction function event
function commentFunction(event){



	var postNum = event.currentTarget.value

		if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function(){
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200){

		}
	}
	xmlhttp.open("GET","comment.php?PostNum="+postNum,true);
	xmlhttp.send();

	//reset wall update values so new repost values can be updated even on autoupdate disable
	latestPost = 0;
	globalArr = [];
	loadWall();
}


//checkBox function event
//disables and enables the periodic refresh (theTimer function)
function checkBox(event){

	if(document.getElementById("dynamicUpdate").checked)  // if user selected disable, stop interval timer
	{
		clearInterval(refreshIntervalId);
	}else{       // if unchecked, reset timer

		theTimer();
	}
}


//theTimer function event
//periodically calls the loadWall Ajax function,
//called on page load
function theTimer(event){

	refreshIntervalId = setInterval(function(){ loadWall(); }, 2000);

}

//loadWall function event
//called by theTimer function, sends ajax request to
//wallupdate.php and prints out wall posts with JSON
function loadWall(){


	if (window.XMLHttpRequest) {
		// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	} else {
		// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {

		// combine new posts object with global posts object


			var myString = xmlhttp.responseText;
			var myArr = JSON.parse(myString);
			globalArr = myArr.concat(globalArr);



			var wallPosts = "";

			//set the latest post number variable
			latestPost = parseInt(globalArr[0]['postId']);

		// determine current page to print data
		if(getUrlVars()["page"] == undefined){
			var page = 1;
		}else{
			var page = getUrlVars()["page"];
		}

		//update total pages
		var total_pages = globalArr.length/10;
		total_pages = Math.ceil(total_pages);



			// print out JSON post objects if it needs to be updated
			if(myArr.length > 0)
			{
				if(page*10 < globalArr.length)
					var countTo = page*10;
				else
					var countTo = globalArr.length;


			for(var i = (page-1) * 10; i < countTo; i++)
			{
				wallPosts += '<div class="wallPost">';
				if(globalArr[i]['uploadedFile'] != null)
				wallPosts += '<div><img src="'+globalArr[i]['uploadedFile']+'" class="wallImg" alt="img"></img></div>';
				wallPosts += '<p class="wallText">'+globalArr[i]['text'] + '</p>';


				// slated to remove-----------change this into comments!?////////////////////////////////

				//if(globalArr[i]['Reposts'] == -1)
				//	wallPosts += '<p class="p3"> Reposted '+globalArr[i]['CurrTime']+' by '+globalArr[i]['Reposter'];
				//else{
				wallPosts += '<p class="p3"> Posted Anonymously at '+globalArr[i]['timestamp'];
				wallPosts += ' - <a href="comment.php?a='+globalArr[i]['postId']+'"><span id="commentCounter'+globalArr[i]['postId']+'">'+globalArr[i]['numOfComments']+'</span> Comment';
				if(globalArr[i]['numOfComments'] != 1)
					wallPosts += 's';
				wallPosts += '</a>';
				//wallPosts += ' <button type="button" class="likeButton" value = "'+globalArr[i]['PostNum']+'"><span id="like'+globalArr[i]['PostNum']+'">Like</span></button>';
				//wallPosts += '<button type="button" class="dislikeButton" value = "'+globalArr[i]['PostNum']+'"><span id="dislike'+globalArr[i]['PostNum']+'">Dislike</span></button>';
				//wallPosts += ' Total Votes: <span id="voteCounter'+globalArr[i]['PostNum']+'">';
				////////////////////////////////////////////////////////////////////////////////////////////


				wallPosts += '</p>';
				wallPosts += '</div>';
			}
			document.getElementById("wallArea").innerHTML = wallPosts;

			}

			//create page links
			var links = "";
			links += "<p> Jump to page: ";
			for (var i=1; i <= total_pages; i++) {
<<<<<<< HEAD
				links += "<a href='index.php?page="+i+"'>"+i+"</a> "; 
=======
				links += "<a href='wall.php?page="+i+"'>"+i+"</a> ";
>>>>>>> origin/master
			}
			links += "</p>";

			//print page links
			document.getElementById("linksArea").innerHTML = links;




		}
	}


	xmlhttp.open("GET","wallupdate.php?latestPost="+latestPost,true);
	xmlhttp.send();

}

/////////////////////////////////////////////////////////////////////////////////////////////////////////
// chkSubmit mega function
// called on submitting any form
// if user inputted bad data, then halt submit and display
// error messages
//////////////////////////////////////////////////////////////////////////////////////////////////////////
function chkSubmit(event){
	var errorStr = ""
	var errors = false;


	// index page submit actions
	if(event.currentTarget.id == "indexForm")
	{
		var namestr = nameNode.value;
		var passstr = passNode.value;

		if(!/\b[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z][a-zA-Z]?[a-zA-Z]?\b/.test(namestr) || /\s/.test(namestr))
		{
			errorStr += "Please enter a valid email \n"
			errors = true;
		}
		if(!/[^a-zA-Z]/.test(passstr) || passstr.length < 8 || /\s/.test(passstr))
		{
			errorStr += "Please enter a valid password \n"
			errors = true;
		}
	}

	// signup page submit actions
	if(event.currentTarget.id == "signupForm")
	{
		var fnamestr = fnameNode.value;
		var lnamestr = lnameNode.value;
		var bday = bdayNode.value;
		var emailstr = emailNode.value;
		var pass1 = pass1Node.value;
		var pass2 = pass2Node.value;

		if(!/^\S/.test(fnamestr) ||!/\S$/.test(fnamestr))
		{
			errorStr += "Please enter a valid first name \n"
			errors = true;
		}
		if(!/^\S/.test(lnamestr) ||!/\S$/.test(lnamestr))
		{
			errorStr += "Please enter a valid last name \n"
			errors = true;
		}
		if(!/\b[1-2][0-9][0-9][0-9]-[0-1][0-9]-[0-3][0-9]\b/.test(bday))
		{
			errorStr += "Please enter a valid birthday \n"
			errors = true;
		}
		if(!/\b[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z][a-zA-Z]?[a-zA-Z]?\b/.test(emailstr) || /\s/.test(emailstr))
		{
			errorStr += "Please enter a valid email \n"
			errors = true;
		}
		if(!/[^a-zA-Z]/.test(pass1) || pass1.length < 8 || /\s/.test(pass1))
		{
			errorStr += "Please enter a valid password \n"
			errors = true;
		}
		if(pass1 != pass2)
		{
			errorStr += "Please re-enter your password \n"
			errors = true;
		}
	}

	//post form submit actions
	if(event.currentTarget.id == "postForm")
	{

		var commentstr = commentsNode.value;


		if(commentstr.length > 500 || commentstr == "")
		{
			errorStr += "Please enter a valid post \n"
			errors = true;
		}
	}
		//comment form submit actions
	if(event.currentTarget.id == "commentForm")
	{
		var commentstr = textComment.value;

		if(commentstr.length > 500 || commentstr == "")
		{
			errorStr += "Please enter a valid comment \n"
			errors = true;
		}
	}

	//user search form submit actions
	if(event.currentTarget.id == "userSearchForm")
	{
		var userstr = search1.value;
		if(!/^\S/.test(userstr) ||!/\S$/.test(userstr))
		{
			errorStr += "Please enter a valid search \n"
			errors = true;
		}
	}

	//text search form submit actions
	if(event.currentTarget.id == "textSearchForm")
	var textstr = search2.value;
	if(!/^\S/.test(textstr) ||!/\S$/.test(textstr))
	{
		errorStr += "Please enter a valid search \n"
		errors = true;
	}




	if(errors)
	{
		alert(errorStr);
		event.preventDefault();
	}
}
