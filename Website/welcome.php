<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("location: login.php");
  exit;
}

$canEdit = $canDelete = false;
$currentUserRole = $_SESSION['usertype'];
$loggedInUsername = $_SESSION['username']; // Get the username of the logged-in user

if ($currentUserRole === 'Super Admin') {
  $canEdit = $canDelete = true; // Super Admin can edit and delete all users
} elseif ($currentUserRole === 'Admin') {
  $canEdit = true; // Admin can edit 'Members' and themselves
}

include 'partials/_dbconnect.php'; // Connect to your database
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
   <link rel="stylesheet" href="css/jquery.dataTables.min.css">
 <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="js/jquery.dataTables.min.js"></script>
  <script>
$(document).ready(function () {
    $('#myTable').DataTable();
});
</script>  -->




  <title>Welcome -
    <?php echo $_SESSION['username']; ?>
  </title>
</head>

<body>
  <?php require 'partials/_nav.php'; ?>

  <div class="container my-3">
    <div class="alert alert-success" role="alert">
      <h4 class="alert-heading">Welcome -
        <?php echo $_SESSION['username']; ?>
      </h4>
      <p>Hey, how are you doing? Welcome to iSecure. You are logged in as
        <?php echo $_SESSION['username']; ?>. Aww yeah, you successfully read this important alert message. This example
        text is going to run a bit longer so that you can see how spacing within an alert works with this kind of
        content.
      </p>
      <hr>
      <p class="mb-0">Whenever you need to, be sure to logout <a href="/website/logout.php"> using this link.</a></p>
    </div>
  </div>
  <div class="container my-4">
    <h1 class="text-center">User List</h1>
    <table class="table table-striped" id="myTable">
      <thead>
        <tr>
          <th>Username</th>
          <th>Usertype</th>
          <th>Email</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
       $sql = "SELECT username, usertype, email FROM website ";

       $result = mysqli_query($conn, $sql);
       
       if ($result) {
         while ($row = mysqli_fetch_assoc($result)) {
           echo '<tr>';
           echo '<td>' . $row['username'] . '</td>';
           echo '<td>' . $row['usertype'] . '</td>';
           echo '<td>' . $row['email'] . '</td>';
           echo '<td>';
       
           // Check if the current user is a 'Super Admin'
           if ($currentUserRole === 'Super Admin') {
             // Super Admin can edit and delete all users
             echo '<button class="btn btn-primary"><a href="update.php?username=' . $row['username'] . '" class="text-light">Edit</a></button>';
             echo '<a href="delete.php?username=' . $row['username'] . '" class="btn btn-danger">Delete</a>';
           } elseif ($currentUserRole === 'Admin') {
             // Admin can edit 'Members' and themselves
             if ($row['usertype'] === 'Member' && $row['username'] !== $loggedInUsername) {
               // Allow Admin to edit 'Members' (except themselves)
               echo '<button class="btn btn-primary"><a href="update.php?username=' . $row['username'] . '" class="text-light">Edit</a></button>';
             } elseif ($row['username'] === $loggedInUsername) {
               // Allow Admin to edit themselves
               echo '<button class="btn btn-primary"><a href="update.php?username=' . $row['username'] . '" class="text-light">Edit</a></button>';
             }
           } elseif ($currentUserRole === 'Member' && $row['username'] === $loggedInUsername) {
             // Member can only edit themselves
             echo '<button class="btn btn-primary"><a href="update.php?username=' . $row['username'] . '" class="text-light">Edit</a></button>';
           }
       
           echo '</td>';
           echo '</tr>';
         }
       } else {
         echo "Error: " . mysqli_error($conn);
       }
       mysqli_close($conn); // Close the database connection
        ?>
        
      </tbody>
      <tfoot>
        <tr>
            <th>Username</th>
            <th>Usertype</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </tfoot>
    </table>
    <!-- Add pagination controls here if required -->
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function () {
        $('#myTable').DataTable();
    });
</script>
</body>

</html>