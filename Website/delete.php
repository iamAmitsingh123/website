<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("location: login.php");
  exit;
}

include 'partials/_dbconnect.php'; // Connect to your database

$currentUserRole = $_SESSION['usertype'];
$loggedInUsername = $_SESSION['username'];

if ($currentUserRole === 'Super Admin') {
  // 'Super Admin' can delete any user
  $usernameToDelete = $_GET['username'];

  // Check if the user to delete is not 'Super Admin' (for safety)
  if ($usernameToDelete !== 'Super Admin') {
    $sql = "DELETE FROM website WHERE username='$usernameToDelete'";
    if (mysqli_query($conn, $sql)) {
      // Deletion successful, redirect to user list
      header("location: welcome.php"); // You can create a welcome.php page for displaying the list of users
    } else {
      echo "Error deleting record: " . mysqli_error($conn);
    }
  } else {
    // Redirect or display an error message if trying to delete 'Super Admin'
    header("location: error.php"); // You can create an error.php page for displaying an error message
  }
} elseif ($currentUserRole === 'Admin') {
  // 'Admin' can't delete users, so redirect or display an error message
  header("location: error.php"); // You can create an error.php page for displaying an error message
} else {
  // 'Member' can't delete users, so redirect or display an error message
  header("location: error.php"); // You can create an error.php page for displaying an error message
}
?>
