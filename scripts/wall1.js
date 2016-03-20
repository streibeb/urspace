///////////////////////////////////
///////event registration//////////
///////////////////////////////////
var check = document.getElementById("dynamicUpdate");

//check.addEventListener('click', checkBox, false);
//window.addEventListener('load', theTimer , false);

var buttons = document.getElementsByTagName("button");
for(var i = 0; i < buttons.length; i++) {
    if (buttons[i].id.substr(0, 7) == "report_") {
        buttons[i].addEventListener("mouseup", ReportPost, false);
    } else if (buttons[i].id.substr(0, 7) == "delete_") {
        buttons[i].addEventListener("mouseup", DeletePost, false);
    }
}
