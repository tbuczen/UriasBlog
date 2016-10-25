<?php

require_once "config.php";

/**
 * Simple script to create user
 */
$opts  = array(
    "name:",  // Required value
    "pass:",  // Required value
    "nick:",  // Optional value
);
echo "Command for creating users, it takes 3 arguments:
--name - username [required]
--pass - password [required]
--nick - nickname [optional]\n";

$options = getopt(null,$opts);
if(empty($options["name"]) || empty($options["pass"])){
    echo "You have to provide both --name and --pass to create user.";
}else{
    $name = $options["name"];
    $pass = $options["pass"];
    $nick = (!empty($options["nick"]))? $options["nick"]:$name;

    $ac = new AdminController();
    $ac->createUser($name,$pass,$nick);
}
