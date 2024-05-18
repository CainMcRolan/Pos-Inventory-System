<?php
   require('../../helper/connect.php');
   session_start();
   
   if (!isset($_SESSION['id'])) {
      header('Location: ../../signin.php');
      exit();
   }

   $result = mysqli_query($connection, "SELECT * FROM user WHERE id = {$_SESSION['id']}");
   $loop = mysqli_fetch_assoc($result);

   echo 'Welcome Purchase Officer ' . $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Point of Sales</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js" defer></script>
</head>
<body>
   
</body>
</html>