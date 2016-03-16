///////////////////////////////////
///////event registration//////////
///////////////////////////////////



var commentForm = document.getElementById("commentForm");
var textComment = commentForm.comment1;

textComment.addEventListener("change", chkComment, false);
commentForm.addEventListener("submit", chkSubmit, false);






