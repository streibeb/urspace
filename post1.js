///////////////////////////////////
///////event registration//////////
///////////////////////////////////

var postForm = document.getElementById("postForm");
var urlNode = postForm.urlInput;
var commentsNode = postForm.comments;

urlNode.addEventListener("change", chkUrl, false);
commentsNode.addEventListener("change", chkComments, false);
postForm.addEventListener("submit", chkSubmit, false);








