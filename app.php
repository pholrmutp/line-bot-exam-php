<!DOCTYPE html>
<html lang="en">
<head>
<title>TEST</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>

body {
	background-color:#F0F8FF;
}
input[type=text]:focus {
    border: 3px solid #555;
}
input[type=password]:focus {
    border: 3px solid #555;
}
  body,td,th {
	font-family: Verdana, Geneva, sans-serif;
}
  </style>
</head>

<body>
<?php
if($_GET["b"]=="test"){ $txt = "Server to Host"; }else{ $txt = "Error";}
echo $txt;
?>
</body>
</html>
