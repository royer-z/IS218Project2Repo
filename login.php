<?php
session_start();
// Extract data from form
$email = $_POST["email"];
$password = $_POST["password"];

$validAccount = FALSE;

if (checkEmail($email) && checkPassword($password)) { // If Email and Password are VALID, check if user is in database
    $dsn = "mysql:host=sql1.njit.edu;dbname=rvz2";
    $user = "rvz2";
    $pass = "OiyeITiq2";

    try {
        $db = new PDO($dsn, $user, $pass);
    }catch(PDOException $e) {
        $error_message = $e->getMessage();
        echo "<p>An error occurred while connecting to the database: $error_message </p>";
    }

    // Query database for existing user
    $query = "SELECT email, password FROM accounts WHERE email = :email, password = :password";
    $statement = $db->prepare($query);
    $statement->bindValue(":email", $email);
    $statement->bindValue(":password", $password);
    $statement->execute();
    $account = $statement->fetchAll();
    $statement->closeCursor();

    if (count($account) == 0) { // If NOT in database, redirect to Registration page
        $validAccount = FALSE;
        // Redirect to registration page
        header('Location: registration.html');
    }
    else { // If in database, redirect to user page
        $validAccount = TRUE;
        $_SESSION['email'] = $email;
        header('Location: userPage.php');
    }
}
else { // If Email or Password is NOT VALID
    echo "Please go back and retry.";
}

function checkEmail($data) {
    $validEmail = TRUE;
    if (empty($data)) {
       echo "Email error: please enter an email address.<br>";
       $validEmail = FALSE;
    }
    if (strpos($data, '@') == FALSE) {
        if (strlen($data) == 0) {
            echo "Email error: please use '@' in your email address.<br>";
            $validEmail = FALSE;
        }
        else {
            echo "Email error: please use '@' in your email address. Your entry: ".$data."<br>";
            $validEmail = FALSE;
        }
    }
    if ($validEmail == TRUE) {
        echo "Valid email.<br>";
    }
    return $validEmail;
}

function checkPassword ($data) {
    $validPassword = TRUE;
    if (empty($data)) {
        echo "Password error: please enter a password.<br>";
        $validPassword = FALSE;
    }
    if (strlen($data) < 8) {
        if (strlen($data) == 0) {
            echo "Password error: please enter a password that is at least 8 characters long. Your entry is ".strlen($data)." characters long.<br>";
        }
        else {
            echo "Password error: please enter a password that is at least 8 characters long. Your entry '".$data."' is ".strlen($data)." characters long.<br>";
        }
        $validPassword = FALSE;
    }
    if ($validPassword == TRUE) {
        echo "Valid password.<br>";
    }
    return $validPassword;
}