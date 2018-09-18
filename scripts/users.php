<?php
require '../vendor/autoload.php';
use sergeytsalkov\meekrodb;
DB::$user = 'betvibe';
DB::$password = 'betvibe.co';
DB::$dbName = 'users';
if (isset($_GET['action']) && $_GET['action'] === 'admin') {
  $users = DB::query("SELECT email, ethereum, tokens, referrals FROM usersTable");
    echo(json_encode($users, JSON_OBJECT_AS_ARRAY));
}