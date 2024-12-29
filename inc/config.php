<?php

if(!isset($_SESSION))
{
    session_start();
}

$base_url = "http://localhost/sigjalan/";

$conn = mysqli_connect(
  'localhost',
  'root',
  '',
  'jalanrusak'
) or die(mysqli_error($mysqli));



?>