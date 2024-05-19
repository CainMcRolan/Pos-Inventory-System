<?php
   require('../../helper/connect.php');
   include('../../app/app.php');
   session_start();
   
   //Check if user is logged in
   if (!isset($_SESSION['id'])) {
      header('Location: ../../signin.php');
      exit();
   }

   //Hande Logout
   if (isset($_POST['logout_session'])) {
      session_destroy();
      header("Location: ../../signin.php");
      exit;
   }
   
   //Generate TitleCase Username for Display
   $username = titleCase($_SESSION['username']);

   $query = "SELECT cash FROM cash";
   $result = mysqli_query($connection, $query);

   $totalCash = 0;
   while ($row = mysqli_fetch_assoc($result)) {
      $totalCash += $row['cash'];
   }

   // Insert total cash amount into cash_pull_out
 
   if (isset($_POST['terminal-report-submit'])) {
      $negativeCash = -$totalCash;
      $queryInsertPullOut = "INSERT INTO cash (cash_pull_out, cash, name) VALUES ('$totalCash', '$negativeCash', '{$_SESSION['username']}')";
      $resultInsertPullOut = mysqli_query($connection, $queryInsertPullOut);
   }

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      try {
         $products = json_decode($_POST['products'], true, 512, JSON_THROW_ON_ERROR);
      } catch (JsonException $e) {
         header('Location: user-pos.php');
         exit(); // Exit if decoding fails
      }
      mysqli_begin_transaction($connection); 
  
      try {
          foreach ($products as $product) {
              $code = $product['code'];
              $image = $product['image'];
              $name = $product['name'];
              $category = $product['category'];
              $price = $product['price'];
              $quantity = $product['quantity'];
              $subtotal = $product['subtotal'];
              $cashier = $product['cashier'];
  
              // Get the current stock from the product table
              $stockQuery = "SELECT current_stock FROM product WHERE code = '$code'";
              $stockResult = mysqli_query($connection, $stockQuery);
  
              if ($stockResult && mysqli_num_rows($stockResult) > 0) {
                  $stockRow = mysqli_fetch_assoc($stockResult);
                  $currentStock = $stockRow['current_stock'];
                  $myVariance = (int) $stockRow['variance'] - $quantity;
                  if ($currentStock >= $quantity) {
                      // Update the stock in the product table
                      $newStock = $currentStock - $quantity;
                      $updateStockQuery = "UPDATE product SET current_stock = $newStock, pull_out = $quantity, variance = $myVariance WHERE code = '$code'";
                      mysqli_query($connection, $updateStockQuery);
  
                      // Insert the sale record into the sale table
                      $insertSaleQuery = "INSERT INTO sale (code, image, name, category, price, sold, cash_received, method, cashier_name) VALUES ('$code', '$image', '$name', '$category', $price, $quantity, $subtotal, 'cash', '$cashier')";
                      mysqli_query($connection, $insertSaleQuery);

                      $insertCashQuery = "INSERT INTO cash (cash) VALUES ($subtotal)";
                      mysqli_query($connection, $insertCashQuery);
                  } else {
                      throw new Exception("Insufficient stock for product code: $code");
                  }
              } else {
                  throw new Exception("Product not found for code: $code");
              }
          }
  
          mysqli_commit($connection);
          echo "Sale recorded successfully!";
      } catch (Exception $e) {
          mysqli_rollback($connection);
          echo "Error recording sale: " . $e->getMessage();
      }

     
  
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
   <div class="app-content">
         <div class="app-content-header">
         <h1>Point of Sales</h1>
          <button class="mode-switch" title="Switch Theme">
            <svg class="moon" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" width="24" height="24" viewBox="0 0 24 24">
              <defs></defs>
              <path d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"></path>
            </svg>
          </button>
          <button class="app-content-headerButton"><a href="user-history.php" class="history-button">History</a></button>
        </div>
        <div class="pos-container">
            <div class="pos-container-1">
               <div class="product-display">
                  <div class="table-div">
                     <div>Item Name</div>
                     <div>Stock</div>
                     <div>Quantity</div>
                     <div>Price</div>
                     <div>Subtotal</div>
                     <div>X</div>
                  </div>
               </div>
               <div class="functions-div">
                  <?php 
                     $result = mysqli_query($connection, 'SELECT cash FROM cash');
                     $displayCash = 0;
                     $display = mysqli_fetch_all($result, MYSQLI_ASSOC);
                     foreach ($display as $row) {
                        $displayCash += $row['cash'];
                     }
                     echo "<div style='font-weight:bold'>Cash: ₱  $displayCash</div>"
                  ?>
                  <div class="quantity-div">
                    <p class="quantity-number">Quantity: 0</p>
                  </div>
                  <div class="noofitems-div">
                     <p class="item-number">Total Items: 0</p>
                  </div>
                  <div class="grand-total-div ">
                     <p class="grand-total">Grand Total: ₱0</p>
                  </div>
                  <div class="check-report waveeffect">
                     <i class="ri-file-chart-line icon" style="color:white"></i>
                     <p style="color:white">Sales Report</p>
                  </div>
                  <div class="check-hourly-report waveeffect">
                     <i class="ri-time-line icon" style="color:white"></i>
                     <p style="color:white">Hourly Report</p>
                  </div>
                  <form action="user-pos.php" method="post">
                     <button class="check-terminal-report waveeffect" type="submit" name="terminal-report-submit">
                        <i class="ri-terminal-window-line icon" style="color:white; font-weight: bold; font-size: 1.1em"></i>
                        <p style="color:white; font-weight: bold; font-size:1.1em">Terminal Report</p>
                     </button>
                  </form>

              
                  <div class="pay-div waveeffect">
                     <i class="ri-cash-line icon" style="color:white"></i>
                     <p style="color:white">Pay All</p>
                  </div>
               </div>
            </div>
            <div class="pos-container-2">
                  <input type="text" class="hi search_item" placeholder="Search Item">
                  <?php
                     $query = 'SELECT * FROM product';

                     $result = mysqli_query($connection, $query);

                     if ($result) {
                        if (mysqli_num_rows($result) > 0) {
                           while ($row = mysqli_fetch_assoc($result)) {
                              echo "
                                 <div class='item-div waveeffect' data-code={$row['code']}>
                                    <img src='../../assets/products/{$row['image']}' alt='hi'>
                                    <h3>{$row['name']}</h3>
                                    <h5>₱{$row['price']}</h5>
                                    <h6>QTY: {$row['current_stock']}</h6>
                                    <p style='display:none'>{$row['category']}</p>
                                    <span style='display:none'>{$row['image']}</span>
                                    <i style='display:none'>$username</i>
                                 </div>
                              ";
                           }
                        }
                     }
                  ?>
            </div>
        </div>
   </div>
   <script src="../../app/toggle.js"></script>
  <script>
      const productArray = [];
      const tableDiv = document.querySelector('.table-div');
      const grandTotal =document.querySelector('.grand-total');
      const quantityNumber =document.querySelector('.quantity-number');
      const itemNumber =document.querySelector('.item-number');
      const searchItem = document.querySelector('.search_item');

      searchItem.addEventListener('input', () => {
         const searchValue = searchItem.value.toLowerCase();
         document.querySelectorAll('.item-div').forEach(item => {
            const itemName = item.querySelector('h3').textContent.toLowerCase();
            if (itemName.includes(searchItem.value)) {
               item.style.display = '';
            } else {
               item.style.display = 'none';
            }
         })
      });

      document.querySelectorAll('.item-div').forEach(item => {
         item.addEventListener('click', () => {
            const { code } = item.dataset;
            const itemName = item.querySelector('h3').textContent;
            const itemStock = parseInt(item.querySelector('h6').textContent.split(':')[1].trim());
            const itemPrice = parseFloat(item.querySelector('h5').textContent.replace('₱', ''));
            const itemQuantity = 1; // Set initial quantity to 1
            const itemSubtotal = itemPrice * itemQuantity;
            const itemCategory = item.querySelector('p').textContent;
            const imageSRC = item.querySelector('span').textContent;
            const currentUsername = item.querySelector('i').textContent;

            const existingProduct = productArray.find(product => product.code === code);

            if (existingProduct) {
               // If the product already exists in the array, increment the quantity
               existingProduct.quantity += itemQuantity;
               existingProduct.subtotal = existingProduct.price * existingProduct.quantity;  
            } else {
               // If the product doesn't exist in the array, create a new product object and push it
               const product = {
               code,
               category: itemCategory, 
               image: imageSRC,
               name: itemName,
               stock: itemStock,
               quantity: itemQuantity,
               price: itemPrice,
               subtotal: itemSubtotal,
               cashier:currentUsername,
               };
               productArray.push(product);
            }
            renderTable();
         });
      });

      function renderTable() {
         // Clear the table-div
         tableDiv.innerHTML = '';

         // Create header row
         const row1 = document.createElement('div');
         row1.textContent = 'Item Name';
         const row2 = document.createElement('div');
         row2.textContent = 'Stock';
         const row3 = document.createElement('div');
         row3.textContent = 'Quantity';
         const row4 = document.createElement('div');
         row4.textContent = 'Price';
         const row5 = document.createElement('div');
         row5.textContent = 'Subtotal';
         const row6 = document.createElement('div');
         row6.textContent = 'X';

         tableDiv.append(row1, row2, row3, row4, row5, row6);

         let total = 0;
         let quantity = 0;
         let items = 0;

         productArray.forEach(product => {
            // Create a div for each column
            const nameDiv = document.createElement('div');
            nameDiv.textContent = product.name;
            tableDiv.appendChild(nameDiv);

            const stockDiv = document.createElement('div');
            stockDiv.textContent = product.stock;
            tableDiv.appendChild(stockDiv);

            const quantityDiv = document.createElement('div');
            const quantitySelect = document.createElement('select');
            for (let i = 1; i <= 20; i++) {
               const option = document.createElement('option');
               option.value = i;
               option.textContent = i;
               quantitySelect.classList.add('quantity-select');
               if (i === product.quantity) {
               option.selected = true;
               }
               quantitySelect.appendChild(option);
            }
            quantitySelect.addEventListener('change', () => {
               const newQuantity = parseInt(quantitySelect.value);
               product.quantity = newQuantity;
               product.subtotal = product.price * newQuantity;
               renderTable();
            });
            quantityDiv.appendChild(quantitySelect);
            tableDiv.appendChild(quantityDiv);

            const priceDiv = document.createElement('div');
            priceDiv.textContent = `₱${product.price.toFixed(2)}`;
            tableDiv.appendChild(priceDiv);

            const subtotalDiv = document.createElement('div');
            subtotalDiv.textContent = `₱${product.subtotal.toFixed(2)}`;
            tableDiv.appendChild(subtotalDiv);

            const removeDiv = document.createElement('div');
            removeDiv.classList.add('remove-item');
            removeDiv.textContent = 'X';
            removeDiv.dataset.code = product.code;
            tableDiv.appendChild(removeDiv);

            total += product.subtotal;
            items++;
            quantity += product.quantity;
         });

         grandTotal.textContent = `Grand Total: ₱${total.toFixed(2)}`;
         quantityNumber.textContent = `Quantity: ${quantity}`;
         itemNumber.textContent = `Total Items: ${items}`;

         const removeButtons = document.querySelectorAll('.remove-item');
         removeButtons.forEach(button => {
            button.addEventListener('click', () => {
               const code = button.dataset.code;
               const index = productArray.findIndex(product => product.code === code);
               if (index !== -1) {
               productArray.splice(index, 1);
               renderTable();
               }
               console.log(productArray);
            });
         });
      }


      //Handle The Inserting
      const payAllDiv = document.querySelector('.pay-div');
      payAllDiv.addEventListener('click', () => {
         if (productArray.length > 0) {
            // Send the productArray data to the server
            sendProductsToServer(productArray);
            window.open('user-receipt.php', '_blank');
         } else {
            alert('No products to purchase!');
         }
         });

      function sendProductsToServer(products) {
         const xhr = new XMLHttpRequest();
         xhr.open('POST', 'user-pos.php', true);
         xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
         xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE && xhr.status === 200) {
               console.log(xhr.responseText);
               // Reset the productArray and update the table
               productArray.length = 0;
               renderTable();
            }
      };
         const data = 'products=' + JSON.stringify(products);
         xhr.send(data);
      }

      document.querySelector('.check-report').addEventListener('click', () => {
         window.open('user-sales-report.php', '_blank');
      });

      document.querySelector('.check-hourly-report').addEventListener('click', () => {
         window.open('user-hourly-report.php', '_blank');
      });

      document.querySelector('.check-terminal-report').addEventListener('click', () => {
         window.open('user-terminal-report.php', '_blank');
      });



   </script>
</body>
</html>