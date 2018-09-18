<?php
require '../vendor/autoload.php';
use sergeytsalkov\meekrodb;
use Josantonius\Session\Session;
Session::init();

DB::$user = 'betvibe';
DB::$password = 'betvibe.co';
DB::$dbName = 'users';
if ($_POST['action'] && $_POST['action'] === 'medium') {
  $update = DB::update('usersTable', array(
    'medium' => $_POST['address']
  ), 'email=%s', Session::get('email'));
  if ($update) {
    Session::set('medium', $_POST['address']);
    echo('Updated Successfully');
  } else {
    echo('An error occured, please try again');
  }
} else if ($_POST['action'] && $_POST['action'] === 'telegram') {
  $update = DB::update('usersTable', array(
    'telegram' => $_POST['address']
  ), 'email=%s', Session::get('email'));
  if ($update) {
    Session::set('telegram', $_POST['address']);
    echo('Updated Successfully');
  } else {
    echo('An error occured, please try again');
  }
} else if ($_POST['action'] && $_POST['action'] === 'twitter') {
  $update = DB::update('usersTable', array(
    'twitter' => $_POST['address']
  ), 'email=%s', Session::get('email'));
  if ($update) {
    Session::set('twitter', $_POST['address']);
    echo('Updated Successfully');
  } else {
    echo('An error occured, please try again');
  }
} else if ($_POST['action'] && $_POST['action'] === 'ethereum') {
  if (
  !DB::queryFirstRow("SELECT ethereum FROM usersTable WHERE
  ethereum=%s", $_POST['address'])){
    $update = DB::update('usersTable', array(
      'ethereum' => $_POST['address']
    ), 'email=%s', Session::get('email'));
    if ($update) {
      Session::set('ethereum', $_POST['address']);
      echo('Updated Successfully');
    } else {
      echo('An error occured, please try again');
    }
  } else {
    echo('Wallet address already exists!');
  }
}
?>