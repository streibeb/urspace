<?php
session_start();
include_once("config.php");

if (isset($_SESSION["login_user"])) {
    $uid = $_SESSION["login_user"];
    $pid = $_POST["pid"];

    $db = mysqli_connect(DB_HOST_NAME, DB_USER, DB_PASS, DB_NAME);
    if (!$db) {
        die ("Failed to connect to database: " . mysqli_connect_error());
    }

    // Check for Admin status
    $isAdmin = false;
    $query = "SELECT * FROM Administrators WHERE userId = $uid;";
    $result = mysqli_query($db, $query);
    if (mysqli_num_rows($result) > 0) {
      $isAdmin = true;
    }

    if (!$isAdmin) { //isset($_POST["soft"])) {
        $sResp = array();

        $query = "UPDATE Posts SET isHidden = true WHERE postId = $pid AND creatorId = $uid";
        $result = mysqli_query($db, $query);
        if ($result) {
          $sRow["postId"] = $pid;
          $sRow["postHidden"] = true;
          $rResp[] = $sRow;
          echo json_encode($rResp);
        }
        mysqli_close($db);
    } else { //if (isset($_POST["hard"])) {
        $sResp = array();

        $query = "DELETE FROM Posts WHERE postId = '$pid';";
        $result = mysqli_query($db, $query);
        if ($result) {
          $sRow["postId"] = $pid;
          $sRow["postDeleted"] = true;
          $rResp[] = $sRow;
          echo json_encode($rResp);
        }
        mysqli_close($db);
    }
}
