<?php 
   require('../../helper/connect.php');

   //Handles Product Submission (Insert Query)
   if (isset($_POST['account_submit'])) {
      $account_id = mysqli_real_escape_string($connection, $_POST['account_id']);
      $account_username = mysqli_real_escape_string($connection, $_POST['account_username']);
      $account_password = mysqli_real_escape_string($connection, $_POST['account_password']);
      $account_email = mysqli_real_escape_string($connection, $_POST['account_email']);
      $account_phone = mysqli_real_escape_string($connection, $_POST['account_phone']);
      $account_isadmin = mysqli_real_escape_string($connection, $_POST['account_isadmin']);

      //Handes Update Query
      $updateFields = "
         username = '{$account_username}',
         password = '{$account_password}',
         email = '{$account_email}',
         phone_number = '{$account_phone}',   
         is_admin = '{$account_isadmin}'";

      $query = "UPDATE user SET $updateFields WHERE id = '{$account_id}'";

      $result = mysqli_query($connection, $query);

      if ($result) {  
         echo "<script>window.parent.location.reload();</script>";
         exit();
      } else {
         echo "Error: " . mysqli_error($connection);
      }
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Document</title>
   <link rel="stylesheet" href="../../styles/home-admin.css">
</head>
<body>
   <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="myForm new-product-form new-user-form edit-account-form" enctype="multipart/form-data">
      <input type="hidden" name="account_id" value="<?= $_GET['id'] ?>">
      <?php
         $account_id = mysqli_real_escape_string($connection, $_GET['id']);
         $query = "SELECT * FROM user WHERE id = '{$account_id}'";
         $result = mysqli_query($connection, $query);
         if ($result && mysqli_num_rows($result) > 0) {
            $account = mysqli_fetch_assoc($result);
         }
      ?>

      <label>Username:</label>
      <input type="text" name="account_username" placeholder="Username" class="inputs" minlength="5" required value="<?= isset($account['username']) ? htmlspecialchars($account['username']) : '' ?>">
      <label>Password:</label>
      <input type="password" name="account_password" placeholder="Password" class="inputs" minlength="5" required value="<?= isset($account['password']) ? htmlspecialchars($account['password']) : '' ?>">
      <label>Email:</label>
      <input type="email" name="account_email" placeholder="Email" class="inputs" required value="<?= isset($account['email']) ? htmlspecialchars($account['email']) : '' ?>">
      <label>Phone Number:</label>
      <input type="number" name="account_phone" placeholder="Phone Number" class="inputs" minlength="11" required value="<?= isset($account['phone_number']) ? htmlspecialchars($account['phone_number']) : '' ?>">
      <label>Role:</label>
      <select name="account_isadmin" value="<?= isset($account['is_admin']) ? htmlspecialchars($account['is_admin']) : '' ?>">
         <option value="admin" <?= isset($account['is_admin']) && $account['is_admin'] == 'admin' ? 'selected' : '' ?>>Admin</option>
         <option value="purchase" <?= isset($account['is_admin']) && $account['is_admin'] == 'purchase' ? 'selected' : '' ?>>Purchase Officer</option>
         <option value="user" <?= isset($account['is_admin']) && $account['is_admin'] == 'user' ? 'selected' : '' ?>>Cashier</option>
      </select>
      <div class="actions">
         <input type="button" value="Cancel" class="formButtons app-content-headerButton cancel">
         <input type="submit" value="Submit" name="account_submit" class="formButtons app-content-headerButton submit">
      </div>
   </form>
   <script>
      document.querySelector('.cancel').addEventListener('click', () => {
         window.parent.location.reload();
      })
   </script>
</body>
</html>