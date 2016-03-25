<?php
session_start();
include_once("config.php");

// Function declarations
function softDeletePost($db, $pid, $uid) {
  $sResp = array();

  $query = "UPDATE Posts SET isHidden = true WHERE postId = $pid AND creatorId = $uid";
  $result = mysqli_query($db, $query);
  if ($result) {
    $sRow["postId"] = $pid;
    $sRow["postHidden"] = true;
    $rResp[] = $sRow;
    echo json_encode($rResp);
  }
}

function hardDeletePost($db, $pid, $uid) {
  $sResp = array();

  // TODO: Need to delete file here

  $query = "DELETE FROM Posts WHERE postId = '$pid';";
  $result = mysqli_query($db, $query);
  if ($result) {
    $sRow["postId"] = $pid;
    $sRow["postDeleted"] = true;
    $rResp[] = $sRow;
    echo json_encode($rResp);
  }
}

function softDeleteNotes($db, $nid, $uid) {
  $sResp = array();

  $query = "UPDATE Notes SET isHidden = true WHERE notesId = $nid AND creatorId = $uid";
  $result = mysqli_query($db, $query);
  if ($result) {
    $sRow["notesId"] = $nid;
    $sRow["notesHidden"] = true;
    $rResp[] = $sRow;
    echo json_encode($rResp);
  }
}

function hardDeleteNotes($db, $nid, $uid) {
  $sResp = array();

  // TODO: Need to delete file here

  $query = "DELETE FROM Notes WHERE notesId = '$nid';";
  $result = mysqli_query($db, $query);
  if ($result) {
    $sRow["notesId"] = $nid;
    $sRow["notesDeleted"] = true;
    $rResp[] = $sRow;
    echo json_encode($rResp);
  }
}

// Business code
if (isset($_SESSION["login_user"])) {
    $uid = $_SESSION["login_user"];
    $pid = $_POST["pid"];
    $nid = $_POST["nid"];

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

    if (!$isAdmin) {
      if (!is_null($pid) && !is_null($nid)) break;
      else if (!is_null($pid)) softDeletePost($db, $pid, $uid);
      else if (!is_null($nid)) softDeleteNotes($db, $nid, $uid);
    } else {
      if (!is_null($pid) && !is_null($nid)) break;
      else if (!is_null($pid)) hardDeletePost($db, $pid, $uid);
      else if (!is_null($nid)) hardDeleteNotes($db, $nid, $uid);
    }
    mysqli_close($db);
}
