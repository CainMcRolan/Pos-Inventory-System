<?php
   declare(strict_types=1);
   $connection = mysqli_connect('localhost', 'root', '', 'mysystem');
   if (!$connection) {
      die('Connection Failed: ' . mysqli_connect_error());
   }
?>