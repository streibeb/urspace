///////////////////////////////////
///////event registration//////////
///////////////////////////////////

var userSearchForm = document.getElementById("userSearchForm");
var textSearchForm = document.getElementById("textSearchForm");
var search1 = userSearchForm.search1;
var search2 = textSearchForm.search2;

search1.addEventListener("change", chkSearch, false);
search2.addEventListener("change", chkSearch, false);
userSearchForm.addEventListener("submit", chkSubmit, false);
textSearchForm.addEventListener("submit", chkSubmit, false);






