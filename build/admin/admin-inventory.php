<?php
   require('../../helper/connect.php');
   include('../../app/app.php');
   session_start();
   
   if (!isset($_SESSION['id'])) {
      header('Location: ../../signin.php');
      exit();
   }

   function generateSecureUniqueCode($length = 6) {
      return bin2hex(random_bytes($length));
   }

   //Delete Function 
   if (isset($_POST['product_delete'])) {
      $delete_code = mysqli_real_escape_string($connection, $_POST['delete_code']);
      $query = "DELETE FROM product WHERE code = '{$delete_code}'";
      $result = mysqli_query($connection, $query);
      if ($result) {
          header("Location: {$_SERVER['PHP_SELF']}");
          exit();
      } else {
          echo "Error: " . mysqli_error($connection);
      }
  }


   $username = titleCase($_SESSION['username']);

   if (isset($_POST['product_submit'])) {
      $code = generateSecureUniqueCode($length = 6);
      $product_name = mysqli_real_escape_string($connection, $_POST['product_name']);
      $product_price = mysqli_real_escape_string($connection, $_POST['product_price']);
      $product_category = mysqli_real_escape_string($connection, $_POST['product_category']);
      $product_description = mysqli_real_escape_string($connection, $_POST['product_description']);
      $product_stock = mysqli_real_escape_string($connection, $_POST['product_stock']);
      $product_count = mysqli_real_escape_string($connection, $_POST['product_count']);
      $product_delivery = mysqli_real_escape_string($connection, $_POST['product_delivery']);
      $product_transfer = mysqli_real_escape_string($connection, $_POST['product_transfer']);
      $product_wasteges = mysqli_real_escape_string($connection, $_POST['product_wasteges']);
      $product_pullout = mysqli_real_escape_string($connection, $_POST['product_pullout']);
      $product_return = mysqli_real_escape_string($connection, $_POST['product_return']);
      $variance = (int) $product_stock - (int) $product_count;

      $query = "INSERT INTO product (code, name, category, price, current_stock, physical_count, delivery, transfer, wasteges, pull_out, returns, variance, description) VALUES ('{$code}', '{$product_name}', '{$product_category}', '{$product_price}', '{$product_stock}', '{$product_count}', '{$product_delivery}', '{$product_transfer}', '{$product_wasteges}', '{$product_pullout}', '{$product_return}', '{$variance}', '{$product_description}')";

      $result = mysqli_query($connection, $query);

      if ($result) {
         echo "Sign up successful!";
         header('Location: admin-inventory.php');
         exit();
      } else {
         echo "Error: " . mysqli_error($connection);
      }
   }

   $result = mysqli_query($connection, 'select * from product');
   $totalItems = 0;
   $totalStocks = 0;
   $totalCategories = 0;
   $totalItemPrice = 0;
   $categories = [];

   if ($result) {
      $categoryArray = mysqli_fetch_all($result, MYSQLI_ASSOC);
      foreach($categoryArray as $items) {
         $totalItems++;
         $totalStocks += (int) $items['current_stock'];
         $totalItemPrice += (int) $items['price'] * (int) $items['current_stock'];
         $categories[] = $items['category'];
      }

      $uniqueCategories = array_unique($categories);
      $totalCategories = count($uniqueCategories);
   }
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>DashBoard</title>
   <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>
   <link rel="stylesheet" href="../../styles/home-admin.css">
</head>
<body>
<div class="app-container">
   <div class="sidebar">
      <div class="sidebar-header">
         <div class="app-icon">
         <svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M507.606 371.054a187.217 187.217 0 00-23.051-19.606c-17.316 19.999-37.648 36.808-60.572 50.041-35.508 20.505-75.893 31.452-116.875 31.711 21.762 8.776 45.224 13.38 69.396 13.38 49.524 0 96.084-19.286 131.103-54.305a15 15 0 004.394-10.606 15.028 15.028 0 00-4.395-10.615zM27.445 351.448a187.392 187.392 0 00-23.051 19.606C1.581 373.868 0 377.691 0 381.669s1.581 7.793 4.394 10.606c35.019 35.019 81.579 54.305 131.103 54.305 24.172 0 47.634-4.604 69.396-13.38-40.985-.259-81.367-11.206-116.879-31.713-22.922-13.231-43.254-30.04-60.569-50.039zM103.015 375.508c24.937 14.4 53.928 24.056 84.837 26.854-53.409-29.561-82.274-70.602-95.861-94.135-14.942-25.878-25.041-53.917-30.063-83.421-14.921.64-29.775 2.868-44.227 6.709-6.6 1.576-11.507 7.517-11.507 14.599 0 1.312.172 2.618.512 3.885 15.32 57.142 52.726 100.35 96.309 125.509zM324.148 402.362c30.908-2.799 59.9-12.454 84.837-26.854 43.583-25.159 80.989-68.367 96.31-125.508.34-1.267.512-2.573.512-3.885 0-7.082-4.907-13.023-11.507-14.599-14.452-3.841-29.306-6.07-44.227-6.709-5.022 29.504-15.121 57.543-30.063 83.421-13.588 23.533-42.419 64.554-95.862 94.134zM187.301 366.948c-15.157-24.483-38.696-71.48-38.696-135.903 0-32.646 6.043-64.401 17.945-94.529-16.394-9.351-33.972-16.623-52.273-21.525-8.004-2.142-16.225 2.604-18.37 10.605-16.372 61.078-4.825 121.063 22.064 167.631 16.325 28.275 39.769 54.111 69.33 73.721zM324.684 366.957c29.568-19.611 53.017-45.451 69.344-73.73 26.889-46.569 38.436-106.553 22.064-167.631-2.145-8.001-10.366-12.748-18.37-10.605-18.304 4.902-35.883 12.176-52.279 21.529 11.9 30.126 17.943 61.88 17.943 94.525.001 64.478-23.58 111.488-38.702 135.912zM266.606 69.813c-2.813-2.813-6.637-4.394-10.615-4.394a15 15 0 00-10.606 4.394c-39.289 39.289-66.78 96.005-66.78 161.231 0 65.256 27.522 121.974 66.78 161.231 2.813 2.813 6.637 4.394 10.615 4.394s7.793-1.581 10.606-4.394c39.248-39.247 66.78-95.96 66.78-161.231.001-65.256-27.511-121.964-66.78-161.231z"/></svg>
         </div>
         <p class="logo"><?= 'Welcome ' . $username ?></p>
      </div>
      <ul class="sidebar-list">
         <li class="sidebar-list-item ">
         <a href="admin-home.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            <span>Home</span>
         </a>
         </li>
         <li class="sidebar-list-item">
         <a href="admin-purchase.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            <span>Purchasing</span>
         </a>
         </li>
         <li class="sidebar-list-item">
         <a href="admin-request.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-pie-chart"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"/><path d="M22 12A10 10 0 0 0 12 2v10z"/></svg>
            <span>Request</span>
         </a>
         </li>
         <li class="sidebar-list-item active">
         <a href="admin-inventory.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-inbox"><polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
            <span>Inventory</span>
         </a>
         </li>
         <li class="sidebar-list-item">
         <a href="admin-account.php">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bell"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <span>User Accounts</span>
         </a>
         </li>
      </ul>
      <div class="account-info">
         <div class="account-info-picture">
         <img src="https://images.unsplash.com/photo-1527736947477-2790e28f3443?ixid=MnwxMjA3fDB8MHxzZWFyY2h8MTE2fHx3b21hbnxlbnwwfHwwfHw%3D&ixlib=rb-1.2.1&auto=format&fit=crop&w=900&q=60" alt="Account">
         </div>
         <div class="account-info-name"><?= $username ?></div>
         <button class="account-info-more">
         <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-more-horizontal"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
         </button>
      </div>
   </div>
   <div class="app-content">
         <div class="app-content-header">
          <h1 class="app-content-headerText">Inventory</h1>
          <button class="mode-switch" title="Switch Theme">
            <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
              <defs></defs>
              <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
            </svg>
          </button>
          <button class="app-content-headerButton">Add Product</button>
        </div>
        <div class="inventory-container">
            <div class="requests waveeffect">
               <h1><?= $totalItems; ?></h1>
               <p class="">Total Items</p>
            </div>
            <div class="total-amount waveeffect">
               <h1><?= $totalStocks ?></h1>
               <p class="">Total Stocks</p>
            </div>
            <div class="total-paid-amount waveeffect">
               <h1><?= $totalCategories ?></h1>
               <p class="">Categories</p>
            </div>
            <div class="total-purchase-due waveeffect">
               <h1><?= '₱' . $totalItemPrice ?></h1>
               <p class="">All Items Total Price</p>
            </div>
            <div class="inventory-list">
               <h1>Items List</h1>
               <button class="app-content-headerButton">Print Record</button>
               <button class="app-content-headerButton new-item">New Item</button>
               <div class="inventory-table">
                  <div>Code</div>
                  <div>Image</div>
                  <div>Item Name</div>
                  <div>Category</div>
                  <div>Price</div>
                  <div>Current Stock</div>
                  <div>Physical Count</div>
                  <div>Delivery</div>
                  <div>Transfer</div>
                  <div>Wasteges</div>
                  <div>Pull Out</div>
                  <div>Returns</div>
                  <div>Variance</div>  
                  <div>Edit</div>
                  <div>Delete</div>
                  <?php 
                     $query = 'SELECT * FROM product';

                     $result = mysqli_query($connection, $query);

                     if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                              echo "<div>" . $row['code'] . "</div>";
                              echo "<div>" . $row['image'] . "</div>";
                              echo "<div>" . $row['name'] . "</div>";
                              echo "<div>" . $row['category'] . "</div>";
                              echo "<div>" . '₱' . $row['price'] . "</div>";
                              echo "<div>" . $row['current_stock'] . "</div>";
                              echo "<div>" . $row['physical_count'] . "</div>";
                              echo "<div>" . $row['delivery'] . "</div>";
                              echo "<div>" . $row['transfer'] . "</div>";
                              echo "<div>" . $row['wasteges'] . "</div>";
                              echo "<div>" . $row['pull_out'] . "</div>";
                              echo "<div>" . $row['returns'] . "</div>";
                              echo "<div>" . $row['variance'] . "</div>";
                              echo "<div><button class='app-content-headerButton-green edit-button'' data-code='{$row['code']}'>Edit</button> </div>";
                              echo "<div>
                                       <form action='admin-inventory.php' method='POST'>
                                          <input type='hidden' name='delete_code' value='{$row['code']}'>
                                          <button type='submit' class='app-content-headerButton-red' name='product_delete'>Delete</button>
                                       </form>
                                    </div>
                                    ";
                            }
                        } 
                    } else {
                        echo "Error: " . mysqli_error($connection);
                    }
                  ?>
               </div>
            </div>
        </div>
        <dialog class="dialog dialog2">
            <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" class="myForm new-product-form">
               <label>Product Name:</label>
               <input type="text" name="product_name">
               <label>Price:</label>
               <input type="text" name="product_price">
               <label>Category:</label>
               <input type="text" name="product_category">
               <label>Description:</label>
               <input type="text" name="product_description">
               <label>Current Stock:</label>
               <input type="number" name="product_stock"> 
               <label>Physical Count:</label>
               <input type="number" name="product_count"> 
               <label>Delivery:</label>
               <input type="number" name="product_delivery"> 
               <label>Transfer:</label>
               <input type="number" name="product_transfer"> 
               <label>Wasteges:</label>
               <input type="number" name="product_wasteges"> 
               <label>Pull Out:</label>
               <input type="number" name="product_pullout" > 
               <label>Returns:</label>
               <input type="number" name="product_return">
               <label>Select Image:</label>
               <input type="file" accept="image/png, image/jpeg">
               <div class="actions">
                  <input type="button" value="Cancel" class="formButtons app-content-headerButton cancel">
                  <input type="submit" value="Submit" name="product_submit" class="formButtons app-content-headerButton submit">
               </div>
            </form>
         </dialog>
         <dialog class="edit-dialog dialog2">
               <iframe src="../../views/edit/admin-inventory-edit.php" frameborder="0" class="edit-iFrame"></iframe>
         </div>
         </dialog>
   </div>
   </div>
   <script>
      let modeSwitch = document.querySelector('.mode-switch');
      const toggleButton = document.querySelector('.new-item');
      const dialog = document.querySelector('.dialog');
      const editDialog = document.querySelector('.edit-dialog');
      const editButton = document.querySelectorAll('.edit-button');
      const iFrame = document.querySelector('.edit-iFrame');
      
      modeSwitch.addEventListener('click', function () {   
         document.documentElement.classList.toggle('light');
         modeSwitch.classList.toggle('active');
      });

      toggleButton.addEventListener('click', () => {
         dialog.showModal();
      })

      document.querySelector('.cancel').onclick = () => {
         dialog.close();
      };

      document.querySelector('.submit').addEventListener('click', () => {
         addEditListener();
      })

      function addEditDialog() {
        editButton.forEach(button => {
            button.addEventListener('click', () => {
                const {code} = button.dataset;
                iFrame.src = `../../views/edit/admin-inventory-edit.php?code=${code}`;
                editDialog.showModal();
            })
        })
      }

      addEditDialog();
   </script>
</body>
</html>