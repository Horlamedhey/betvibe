<?php
require '../vendor/autoload.php';
use sergeytsalkov\meekrodb;
use Josantonius\Session\Session;

Session::init();
DB::$user = 'betvibe';
DB::$password = 'betvibe.co';
DB::$dbName = 'users';
function generateRandomString($length) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
if ($_POST['email'] && $_POST['password']) {
  $email = test_input($_POST['email']);
  $userPassword = md5(test_input($_POST['password']));
  $referrerCode = test_input($_POST['referrerCode']);
  DB::query("SELECT * FROM usersTable");
  $entries = DB::count();
    if ($entries < 10000) {
      $checkEmail = DB::query("SELECT email FROM usersTable WHERE email=%s", $email);
    if (!$checkEmail) {
      if ($referrerCode !== '') {
        if (
          DB::queryFirstRow("SELECT ownReferralCode FROM usersTable WHERE
          ownReferralCode=%s", $referrerCode)
          )
          {
            if (
              DB::insert('usersTable', array(
                'email' => $email,
                'password' => $userPassword,
                'referrerCode' => $referrerCode,
                'ownReferralCode' => generateRandomString(8),
                'tokens' => 25000
              ))
            ) {
                echo('Registered Successfully.');
                $user = DB::queryFirstRow("SELECT * FROM usersTable WHERE email=%s AND password=%s", $email, $userPassword);
                Session::set($user);
              } else {
                echo('Unable to register new user.');
              }
            if (
              $ref = DB::queryFirstRow("SELECT referrals FROM usersTable WHERE ownReferralCode=%s", $referrerCode)
            ) {
              DB::update('usersTable', array(
                'referrals' => $ref['referrals'] + 1
              ), 'ownReferralCode=%s', $referrerCode);
            }
          } else {
            echo('Invalid referrer code!');
          }
      } else {
        if (
          DB::insert('usersTable', array(
            'email' => $email,
            'password' => $userPassword,
            'referrerCode' => $referrerCode,
            'ownReferralCode' => generateRandomString(8),
            'tokens' => 25000
          ))
        ) {
            echo('Registered Successfully.');
            $user = DB::queryFirstRow("SELECT * FROM usersTable WHERE email=%s AND password=%s", $email, $userPassword);
            Session::set($user);
          } else {
            echo('Unable to register new user.');
          }
      }
    } else if ($checkEmail){
      echo('Email already taken');
    }
  } else if ($entries >= 10000) {
    echo('Sorry! Users Registration Limit Reached!');
  }
}