///////////////////////////////////
///////event registration//////////
///////////////////////////////////

var indexForm = document.getElementById("indexForm");
var nameNode = indexForm.uName;
var passNode = indexForm.pWord;
nameNode.addEventListener("change", chkName, false);
passNode.addEventListener("change", chkPass, false);
indexForm.addEventListener("submit", chkSubmit, false);







