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
               header('Location: ./build/admin/admin-request.php');
               exit();
            } else if ($row['is_admin'] == 'user') {
               header('Location: ./build/user/user-history.php');
               exit();
            } else if ($row['is_admin'] == 'purchase') {
               header('Location: ./build/purchase/purchase-inventory.php');
               exit();
            }
         } else {
            echo "Invalid username or password.";
         }
      } else {
         echo "Invalid username or password.";
      }
   }

   if(isset($_POST['admin_redirect'])) {
      $name = mysqli_real_escape_string($connection, $_POST['admin_name']);
      $password = $_POST['admin_password'];

      $query = "SELECT * FROM user WHERE username = '$name'";
      $result = mysqli_query($connection, $query);

      if (mysqli_num_rows($result) > 0) {
         $row = mysqli_fetch_assoc($result);

         if ($password == $row['password']) {
            echo "Sign in successful!";
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $name;

            if ($row['is_admin'] === 'admin') {
               $_SESSION['is_admin'] = $row['is_admin'];
               header('Location: ./build/admin/admin-request.php');
               exit();
         } else {
            echo "Invalid username or password.";
         }
      } else {
         echo "Invalid username or password.";
      }
    }
   }

   if(isset($_POST['cashier_redirect'])) {
      $name = mysqli_real_escape_string($connection, $_POST['cashier_name']);
      $password = $_POST['cashier_password'];

      $query = "SELECT * FROM user WHERE username = '$name'";
      $result = mysqli_query($connection, $query);

      if (mysqli_num_rows($result) > 0) {
         $row = mysqli_fetch_assoc($result);

         if ($password == $row['password']) {
            echo "Sign in successful!";
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $name;

            if ($row['is_admin'] == 'user') {
               header('Location: ./build/user/user-history.php');
               exit();
         } else {
            echo "Invalid username or password.";
         }
      } else {
         echo "Invalid username or password.";
      }
    }
   }

   
   if(isset($_POST['purchase_redirect'])) {
      $name = mysqli_real_escape_string($connection, $_POST['purchase_name']);
      $password = $_POST['purchase_password'];

      $query = "SELECT * FROM user WHERE username = '$name'";
      $result = mysqli_query($connection, $query);

      if (mysqli_num_rows($result) > 0) {
         $row = mysqli_fetch_assoc($result);

         if ($password == $row['password']) {
            echo "Sign in successful!";
            $_SESSION['id'] = $row['id'];
            $_SESSION['username'] = $name;

            if ($row['is_admin'] == 'purchase') {
               header('Location: ./build/purchase/purchase-request.php');
               exit();
         } else {
            echo "Invalid username or password.";
         }
      } else {
         echo "Invalid username or password.";
      }
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
   <style>
      .buttons {
         background-color: white;
         border-radius: 5px;
         height: 50px;
         cursor: pointer;
         color: black;
         margin-bottom: 10px;
         padding: 10px;
         width: 200px;
      }

      .buttons:hover {
         background-color: rgb(200, 200, 200);
      }
   </style>
</head>
<body>
   <h1>Sign In</h1>
   <div class="container">
      <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
         <input type="text" name="signin_username" placeholder="Username" class="inputs" required>
         <input type="password" name="signin_password" placeholder="Password" class="inputs" required>
         <input type="submit" name="signin_submit" value="Sign In">
      </form>
      <div>
         <h1>Premade Accounts</h1>
         <p>Each one has their own unique Role</p>
         <form action="index.php" method="POST">
            <input type="hidden" value="admin" name="admin_name">
            <input type="hidden" value="12345" name="admin_password">
            <button type="submit" class="buttons" name="admin_redirect">Open Admin</button>
         </form>
         <form action="index.php" method="POST">
            <input type="hidden" value="cashier" name="cashier_name">
            <input type="hidden" value="12345" name="cashier_password">
            <button type="submit" class="buttons" name="cashier_redirect">Open Cashier</button>
         </form>
         <form action="index.php" method="POST">
            <input type="hidden" value="purchase" name="purchase_name">
            <input type="hidden" value="12345" name="purchase_password">
            <button type="submit" class="buttons" name="purchase_redirect">Open Purchase Officer</button>
         </form>
      </div>
   </div>
</body>
</html>