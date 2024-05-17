<?php
function titleCase($string) {
   // Convert the entire string to lowercase first
   $string = strtolower($string);
   // Capitalize the first letter of each word
   $string = ucwords($string);
   return $string;
}