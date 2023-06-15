<?php
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

    // Create a new Query object
    $query = new MongoDB\Driver\Query([
        "username" => $_POST["username"],
        "password" => $_POST["password"]
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
?>

<html>
<head>
    <title></title>
</head>

<body style='text-align:center;font-family:Helvetica;'>
    <form method='POST' action='login.php'>
        <p /> Username <input type='TEXT' id='username' name='username' value='' />
        <p /> Password <input type='TEXT' id='password' name='password' value='' />
        <p /><input type='SUBMIT' id='login' name='login' value='LOGIN' />
        <p /><font color='#FF0000'><?=$errmsg;?></font>
    </form>
</body>
</html>