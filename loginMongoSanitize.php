<?php
#requiring mongo sanitize package
require_once 'c:\xampp\vendor\autoload.php';
session_start();

$errmsg = "";
if (isset($_POST["login"])){
    // Configuration
    $dbhost = "127.0.0.1";
    $dbname = "test";

    // Connect to the test database
    $manager = new MongoDB\Driver\Manager("mongodb://$dbhost");

    //set up the database with a new admin entry and password 1234
    $bulk = new MongoDB\Driver\BulkWrite; // Create a new BulkWrite object
    $bulk->insert(['username' => 'admin', 'password' => '1234']); // Insert a new user document
    $manager->executeBulkWrite("$dbname.users", $bulk); // Execute the bulk write operation

    // validate input
    $username = validateInput($_POST["username"]);
    $password = validateInput($_POST["password"]);

    // Create a new Query object
    $query = new MongoDB\Driver\Query([
        "username" => $username,
        "password" => $password
    ]);

    // Execute the query
    $result = $manager->executeQuery("$dbname.users", $query);

    // Check if a user was found
    if (count($result->toArray()) > 0) {
        $_SESSION["username"] = $_POST["username"];
        header("Location: logout.php");
        exit(0);
    } else {
        $errmsg = "login failed!";
    }
}
#sainitizing input with a package
function validateInput($input)
{
    //using mongo sanitize package 
    return mongo_sanitize($input);
}
?>

<html>
<head>
    <title></title>
</head>

<body style='text-align:center;font-family:Helvetica;'>
    <p>Sanitized Input Version via Mongo-sanitize package<p>
    <form method='POST' action='loginMongoSanitize.php'>
        <p /> Username <input type='TEXT' id='username' name='username' value='' />
        <p /> Password <input type='TEXT' id='password' name='password' value='' />
        <p /><input type='SUBMIT' id='login' name='login' value='LOGIN' />
        <p /><font color='#FF0000'><?=$errmsg;?></font>
    </form>
</body>
</html>