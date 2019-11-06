<?php
session_start();
include 'config.php';
define('HOST', $host);
define('USER', $username);
define('PASSWORD', $password);
define('DATABASE', $database);
require 'class/Database.php';
require 'class/Users.php';
require 'class/Time.php';
require 'class/Tickets.php';
require 'class/Subscriber.php';
require 'class/SubscriberManager.php';
$database = new Database;
$users = new Users;
$time = new Time;
$tickets = new Tickets;
$subscribers = new Subscriber;
$subManager = new SubscriberManager;
?>
