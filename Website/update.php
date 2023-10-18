<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("location: login.php");
  exit;
}

include 'partials/_dbconnect.php'; // Connect to your database

$loggedInUsername = $_SESSION['username'];

// Get the username from the URL
$editUsername = $_GET['username'];
// echo $editUsername;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $newUsername = $editUsername; // Use the editUsername obtained from the URL
  $newEmail = $_POST['newEmail'];

  // Check if the current user is allowed to edit
  if ($_SESSION['usertype'] === 'Super Admin' || ($_SESSION['usertype'] === 'Admin' || $_SESSION['usertype']== 'Member')) {
    // Allow 'Super Admin' to edit all users and 'Admin' to edit other users (not themselves)
    $sql = "UPDATE website SET username='$newUsername', email='$newEmail' WHERE username='$newUsername'";

    if (mysqli_query($conn, $sql)) {
      // Update successful, redirect to user list or profile page
      header("location: welcome.php"); // You can create an update.php page for displaying the list of users
    } else {
      echo "Error updating record: " . mysqli_error($conn);
    }
  } else {
    // Redirect or display an error message for users who don't have permission
    header("location: error.php"); // You can create an error.php page for displaying an error message
    exit;
  }
}

// Retrieve the user's information to populate the form fields
$sql = "SELECT * FROM website WHERE username = '$editUsername'";
$result = mysqli_query($conn, $sql);

if ($result) {
  $row = mysqli_fetch_assoc($result);
  $editUsername = $row['username']; // Get the user's existing username
  $editEmail = $row['email']; // Get the user's existing email
} else {
  echo "Error: " . mysqli_error($conn);
  // Handle the case where the user's information cannot be retrieved
}

mysqli_close($conn); // Close the database connection
?>

<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
    integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="style.css">

  <title>Edit Profile - <?php echo $editUsername; ?></title>
</head>

<body>
  <?php require 'partials/_nav.php'; ?>

  <div class="container my-3">
    <h1 class="text-center">Edit Profile - <?php echo $editUsername; ?></h1>
    <form method="POST">
      <div class="form-group">
        <label for="newUsername">New Username</label>
        <input type="text" class="form-control" id="newUsername" name="newUsername" value="<?php echo $editUsername; ?>" readonly>
      </div>
      <div class="form-group">
        <label for="newEmail">New Email</label>
        <input type="email" class="form-control" id="newEmail" name="newEmail" value="<?php echo $editEmail; ?>">
      </div>
      <button type="submit" class="btn btn-primary">Update</button>
    </form>
  </div>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
    integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
    crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
    integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
    integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
    crossorigin="anonymous"></script>
</body>

</html>
