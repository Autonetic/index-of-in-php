<?php

$page_name = substr($_SERVER['PHP_SELF'], 0, -9); //remove index.php from end of string

function listFiles($directory) {
  $index_files = array(); // Initialize an array
  if ($handle = opendir($directory)) {  // Open directory
    while (false !== ($entry = readdir($handle))) {
      if($directory != '.') {         // -|
        $prepend = $directory.'/';    //  | Fixes links if folder is subdirectory
      } else {                        //  |
        $prepend = "";                // -|
      }      
      if ($entry != "." && $entry != ".." && $entry != "index.php") { //Avoid the folder . and .. builtins as well as this page, index.php
        $size = formatBytes(filesize($entry)); // Formats filesize to a more readable value
        if (is_file($entry)) { // If file, collect and push into list
          array_push($index_files, "<a href=\"$prepend"."$entry\" class=\"list-group-item list-group-item-action file\">$entry - $size</a>");
        } else {  // If folder, shift to top of list
          array_unshift($index_files, "<a href=\"$prepend"."$entry\" class=\"list-group-item list-group-item-action folder\">$entry - $size</a>");
        }
      }
    }  
    closedir($handle); //Close directory
  }
  return $index_files; //returns the array
}
function renderData($directory) { //actually make the call to which directory you would like
  foreach(listFiles($directory) as $files) {
    echo $files;
  }
}
function formatBytes($bytes, $precision = 2) {  //Function used in above listFiles function to make bytes more readable
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
   
    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 
   
    $bytes /= pow(1024, $pow); 
   
    return round($bytes, $precision) . ' ' . $units[$pow]; 
}
?>
<!DOCTYPE html>
<html data-bs-theme="dark">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="author" content="Autonetix.co">
  <title>Index of <?=$page_name?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://kit.fontawesome.com/YOURCUSTOM.js" crossorigin="anonymous"></script>
  <style>
  body {
    background-color: #010409;
    color: white;
  }
  .folder{
    font-weight:bold;
    border: 1px solid #48fb47 !important;
  }
  .folder::before{
    font-family: "Font Awesome 5 Free";
    content: "\f07b";
    padding-right: 5px;
  }
  .file::before{
    font-family: "Font Awesome 5 Free";
    content: "\f15c";
    padding-right: 5px;
  }
  .list-group-item {
    background-color: #0d1117 !important;
    border-color: #3564a9;
  }
  </style>
</head>
<body>
<div class="container mt-3">
  <p><span style="font-weight:bold;color:#48fb47;">Index of <?=$page_name?></p></span>
  <div class="list-group">
    <?php  
    echo renderData('.'); //Using the call
    ?>  
  </div>
</div>

<div class="container mt-3">
  <div class="list-group">
    <?php  
    echo renderData('subDirectory'); //Using the call on sub directory
    ?>  
  </div>
</div>

<div class="container mt-3">
  <hr>
  <?php 
  echo $_SERVER['SERVER_NAME'];
  echo "<br>";
  echo $_SERVER['HTTP_REFERER'];
  echo "<br>";
  echo $_SERVER['HTTP_USER_AGENT'];
  ?>
</div>
</body>
</html>
