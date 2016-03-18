///////////////////////////////////
///////event registration//////////
///////////////////////////////////



var commentForm = document.getElementById("commentForm");
var textComment = commentForm.comment1;

textComment.addEventListener("change", chkComment, false);
commentForm.addEventListener("submit", chkSubmit, false);

// For Reporting
var buttons = document.getElementsByTagName("button");

for(var i = 0; i < buttons.length; i++) {
    if (buttons[i].id.substr(0, 7) == "report_") {
        buttons[i].addEventListener("mouseup", ReportPost, false);
        console.log("Added Event Listener\n");
    }
}
