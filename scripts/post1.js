///////////////////////////////////
///////event registration//////////
///////////////////////////////////


var postForm = document.getElementById("postForm");

var commentsNode = postForm.post1;


commentsNode.addEventListener("change", chkPost, false);
postForm.addEventListener("submit", chkSubmit, false);








