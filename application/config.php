<?php

// Set the app paths
$home = "http://localhost/bedsect/";
$shinyDir = "http://localhost:3838/plots/";

//Change the default values
$servername = "localhost";
$username = "user";
$password = "password";
$dbname = "dbname";
$charset = 'utf8';
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Test connection
if ($conn->connect_error) {
    die("<b>Failed to connect with Mysql server.</b>");
};

//Cheange the default values
$options = array(
    'delete_type' => 'POST',
    'db_host' => 'localhost',
    'db_user' => 'user',
    'db_pass' => 'password',
    'db_name' => 'dbname',
    'db_table' => 'files'
);
