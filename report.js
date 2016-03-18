//var buttons = document.getElementsByTagName("button");
//
//for(var i = 0; i < buttons.length; i++) {
//    if (buttons[i].id.substr(0, 7) == "report_") {
//        buttons[i].addEventListener("mouseup", ReportPost, false);
//    }
//}

function GetRequestObject() {
    var xhr = null;

    if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
    } else if (window.ActiveObject) {
        try {
            objRequest = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch(e) {
            try {
                objRequest = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch(e) {}
        }
    }
    return xhr;
}

function ReportPost(Event) {
    var element = Event.currentTarget;
    var pid = element.id.substr(7);

    var xhr = GetRequestObject();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var responseObj = JSON.parse(xhr.responseText);
            if (responseObj[0].userReported)
            {
                element.className = "reportButtonPressed";
                element.innerHTML = "Post Reported";
            } else {
                element.className = "reportButton";
                element.innerHTML = "Report Post";
            }
            //element.addEventListener("mouseup", ReportPost, true);
        }
    }
    xhr.open("POST","reports.php",true);
    xhr.setRequestHeader("Content-type","application/x-www-form-urlencoded");
    if (element.classList.contains('reportButton')) {
        xhr.send("add=1&pid=" + pid);
        return;
    } else {
        xhr.send("remove=1&pid=" + pid);
        return;
    }
}
