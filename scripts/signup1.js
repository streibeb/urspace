///////////////////////////////////
///////event registration//////////
///////////////////////////////////


var signupForm = document.getElementById("signupForm");
var fnameNode = signupForm.fName;
var lnameNode = signupForm.lName;
var bdayNode = signupForm.bDay;
var emailNode = signupForm.eMail;
var pass1Node = signupForm.pWord1;
var pass2Node = signupForm.pWord2;
fnameNode.addEventListener("change", chkFname, false);
lnameNode.addEventListener("change", chkLname, false);
bdayNode.addEventListener("change", chkDate, false);
emailNode.addEventListener("change", chkName, false);
pass1Node.addEventListener("change", chkPass, false);
pass2Node.addEventListener("change", chkPass2, false);
signupForm.addEventListener("submit", chkSubmit, false);

