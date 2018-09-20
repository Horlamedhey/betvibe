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
if ($_POST['action'] && $_POST['action'] === 'medium') {
  $update = DB::update('usersTable', array(
    'medium' => test_input($_POST['address'])
  ), 'email=%s', Session::get('email'));
  if ($update) {
    Session::set('medium', test_input($_POST['address']));
    echo('Updated Successfully');
  } else {
    echo('An error occured, please try again');
  }
} else if ($_POST['action'] && $_POST['action'] === 'telegram') {
  $update = DB::update('usersTable', array(
    'telegram' => test_input($_POST['address'])
  ), 'email=%s', Session::get('email'));
  if ($update) {
    Session::set('telegram', test_input($_POST['address']));
    echo('Updated Successfully');
  } else {
    echo('An error occured, please try again');
  }
} else if ($_POST['action'] && $_POST['action'] === 'twitter') {
  $update = DB::update('usersTable', array(
    'twitter' => test_input($_POST['address'])
  ), 'email=%s', Session::get('email'));
  if ($update) {
    Session::set('twitter', test_input($_POST['address']));
    echo('Updated Successfully');
  } else {
    echo('An error occured, please try again');
  }
} else if ($_POST['action'] && $_POST['action'] === 'ethereum') {
  if (
  !DB::queryFirstRow("SELECT ethereum FROM usersTable WHERE
  ethereum=%s", test_input($_POST['address']))){
    $update = DB::update('usersTable', array(
      'ethereum' => test_input($_POST['address'])
    ), 'email=%s', Session::get('email'));
    if ($update) {
      Session::set('ethereum', test_input($_POST['address']));
      echo('Updated Successfully');
    } else {
      echo('An error occured, please try again');
    }
  } else {
    echo('Wallet address already exists!');
  }
}
?>