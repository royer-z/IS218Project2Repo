<?php
// Extract data from form
$email = $_POST["email"];
$password = $_POST["password"];

$validAccount = FALSE;

if (checkEmail($email) && checkPassword($password)) { // If Email and Password are VALID, check if user is in database
    $dsn = "mysql:host=sql1.njit.edu;dbname=rvz2";
    $user = "rvz2";
    $pass = "H4QkQ3x9";

    try {
        $db = new PDO($dsn, $user, $pass);
    }catch(PDOException $e) {
        $error_message = $e->getMessage();
        echo "<p>An error occured while connecting to the database: $error_message </p>";
    }

    $query = "SELECT email, password FROM accounts WHERE email = :email, password = :password";
    $statement = $db->prepare($query);
    $statement->bindValue(":email, $email");
    $statement->bindValue(":password, $password");
    $statement->execute();
    $account = $statement->fetchAll();
    $statement->closeCursor();

    // If in database, redirect to User page
    // If NOT in database, redirect to Registration page
    if (count($account) == 0) {
        $validAccount = FALSE;
        // Redirect to registration page
    }
    else {
        $validAccount = TRUE;
        // Redirect to user page
    }

    /*
    foreach ($account as $value) {
        echo $value;
    }
    */
}
else { // If Email or Password is NOT VALID
    print "<a href='index.html'>Retry</a>";
}

function checkEmail($data) {
    $validEmail = TRUE;
    if (empty($data)) {
       print "Email error: please enter an email address.<br>";
       $validEmail = FALSE;
    }
    if (strpos($data, '@') == FALSE) {
        if (strlen($data) == 0) {
            print "Email error: please use '@' in your email address.<br>";
            $validEmail = FALSE;
        }
        else {
            print "Email error: please use '@' in your email address. Your entry: ".$data."<br>";
            $validEmail = FALSE;
        }
    }
    if ($validEmail == TRUE) {
        print "Valid email.<br>";
    }
    return $validEmail;
}

function checkPassword ($data) {
    $validPassword = TRUE;
    if (empty($data)) {
        print "Password error: please enter a password.<br>";
        $validPassword = FALSE;
    }
    if (strlen($data) < 8) {
        if (strlen($data) == 0) {
            print "Password error: please enter a password that is at least 8 characters long. Your entry is ".strlen($data)." characters long.<br>";
        }
        else {
            print "Password error: please enter a password that is at least 8 characters long. Your entry '".$data."' is ".strlen($data)." characters long.<br>";
        }
        $validPassword = FALSE;
    }
    if ($validPassword == TRUE) {
        print "Valid password.<br>";
    }
    return $validPassword;
}
?>