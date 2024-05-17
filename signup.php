<?php
   $connection = mysqli_connect('localhost', 'root', '', 'mysystem');

   if (!$connection) {
      die('Connection Failed: ' . mysqli_connect_error());
   }
   if (isset($_POST['signup_submit'])) {
      $signup_username = mysqli_real_escape_string($connection, $_POST['signup_username']);
      $signup_password = mysqli_real_escape_string($connection, $_POST['signup_password']); 
      $signup_email = mysqli_real_escape_string($connection, $_POST['signup_email']);
      $signup_phone = mysqli_real_escape_string($connection, $_POST['signup_phone']);

      $query = "INSERT INTO user (username, password, email, phone_number) VALUES ('$signup_username', '$signup_password', '$signup_email', '$signup_phone')";
      $result = mysqli_query($connection, $query);

      if ($result) {
         echo "Sign up successful!";
         header('Location: home.php');
         exit();
      } else {
         echo "Error: " . mysqli_error($connection);
      }
   }
   mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sign Up</title>
   <link rel="stylesheet" href="./styles/style.css">
   <link rel="stylesheet" href="./styles/authentication.css">
</head>
<body>
   <h1>Sign Up</h1>
   <div class="container-2">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
         <input type="text" name="signup_username" placeholder="Username" class="inputs" minlength="5" required>
         <input type="password" name="signup_password" placeholder="Password" class="inputs" minlength="5" required>
         <input type="email" name="signup_email" placeholder="Email" class="inputs" required>
         <input type="number" name="signup_phone" placeholder="Phone Number" class="inputs" minlength="11" required>
         <input type="submit" name="signup_submit" value="Sign Up">
      </form>
      <a href="signin.php">Sign In</a>
   </div>
</body>
</html>