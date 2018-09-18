<?php
require '../vendor/autoload.php';
use Josantonius\Session\Session;
Session::init();
Session::destroy();
header("Location: ../index.php?logout=true");
?>