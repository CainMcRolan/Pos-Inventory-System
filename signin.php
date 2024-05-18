<?php
   require('./helper/connect.php');

   session_start();

   if (isset($_POST['signin_submit'])) {
      $signin_username = mysqli_real_escape_string($connection, $_POST['signin_username']);
      $signin_password = $_POST['signin_password'];

      $query = "SELECT * FROM user WHERE username = '$signin_username'";
      $result = mysqli_query($connection, $query);

      if (mysqli_num_rows($result) > 0) {
         $row = mysqli_fetch_assoc($result);

         if ($signin_password == $row['password']) {
            echo "Sign in successful!";
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $signin_username;

            if ($row['is_admin'] === 'admin') {
               $_SESSION['is_admin'] = $row['is_admin'];
               header('Location: ./build/admin/admin-purchase.php');
               exit();
            } else if ($row['is_admin'] == 'user') {
               header('Location: ./build/user/home-user.php');
               exit();
            } else if ($row['is_admin'] == 'purchase') {
               header('Location: ./build/purchase/home-purchase.php');
               exit();
            }
         } else {
            echo "Invalid username or password.";
         }
      } else {
         echo "Invalid username or password.";
      }
   }
   mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Sign In</title>
   <link rel="stylesheet" href="./styles/style.css">
   <link rel="stylesheet" href="./styles/authentication.css">
</head>
<body>
   <h1>Sign In</h1>
   <div class="container">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
         <input type="text" name="signin_username" placeholder="Username" class="inputs" required>
         <input type="password" name="signin_password" placeholder="Password" class="inputs" required>
         <input type="submit" name="signin_submit" value="Sign In">
      </form>
   </div>
</body>
</html>