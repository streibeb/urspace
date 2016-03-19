<?php
session_start();
include_once("config.php");

if (isset($_SESSION["login_user"])) {
    $uid = $_SESSION["login_user"];
    $pid = $_POST["pid"];

    if (isset($_POST["add"])) {
      // Think of this as
      // addReportToPost(int pid);
        $sResp = array();
        $db = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
        if (!$db) {
            die ("Failed to connect to database: " . mysqli_connect_error());
        }
        $timestamp = date("Y-m-d H:i:s");
        $query = "INSERT IGNORE INTO ReportedPosts (postID, userId, timestamp) VALUES ('$pid','$uid','$timestamp');";
        $result = mysqli_query($db, $query);
        if ($result) {
            $sRow["userReported"] = true;
            $rResp[] = $sRow;
            echo json_encode($rResp);
        }
        mysqli_close($db);
    } else if (isset($_POST["remove"])) {
      // Think of this as
      // removeReportFromPost(int pid);
        $sResp = array();
        $db = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
        if (!$db) {
            die ("Failed to connect to database: " . mysqli_connect_error());
        }
        $query = "DELETE FROM ReportedPosts WHERE postId = '$pid' AND userId = '$uid';";
        $result = mysqli_query($db, $query);
        if ($result) {
            $sRow["userReported"] = false;
            $rResp[] = $sRow;
            echo json_encode($rResp);
        }
        mysqli_close($db);
    }
}
