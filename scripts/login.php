<?php
require '../vendor/autoload.php';

use sergeytsalkov\meekrodb;
use Josantonius\Session\Session;

Session::init();
DB::$user = 'betvibe';
DB::$password = 'betvibe.co';
DB::$dbName = 'users';
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
if (isset($_POST['action']) && $_POST['action'] == 'login') {
  $email = test_input($_POST['email']);
  $password = md5(test_input($_POST['password']));
  $user = DB::queryFirstRow("SELECT * FROM usersTable WHERE email=%s AND password=%s", $email, $password);
  if ($user && count($user) > 0) {
    Session::set($user);
    echo('Login Successful');  
  } else {
    echo('Incorrect email or password');
  }
} else if (isset($_POST['action']) && $_POST['action'] === 'admin') {
  $email = $_POST['email'];
  $password = md5($_POST['password']);
  $user = DB::queryFirstRow("SELECT * FROM adminTable WHERE email=%s AND password=%s", $email, $password);
  if ($user && count($user) > 0) {
    Session::set($user);
    echo('Admin Login Successful');  
  } else {
    echo('Invalid Login');
  }
}