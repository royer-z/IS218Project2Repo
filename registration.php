<?php
session_start();

$firstName = $_POST['firstname'];
$lastName = $_POST['lastname'];
$birthday = $_POST['birthday'];
$email = $_POST["email"];
$password = $_POST["password"];

if (checkFirstName($firstName) && checkLastName($lastName) && checkBirthday($birthday) && checkEmail($email) && checkPassword($password)) {
    $dsn = "mysql:host=sql1.njit.edu;dbname=rvz2";
    $user = "rvz2";
    $pass = "OiyeITiq2";

    try {
        $db = new PDO($dsn, $user, $pass);
    }catch(PDOException $e) {
        $error_message = $e->getMessage();
        echo "<p>An error occurred while connecting to the database: $error_message </p>";
    }

    // Register new user
    $query = "INSERT INTO accounts (email, fname, lname, birthday, password) VALUES (:email, :firstName, :lastName, :birthday, :password)"; // Change to INSERT statement
    $statement = $db->prepare($query);
    $statement->bindValue(":email", $email);
    $statement->bindValue(":firstName", $firstName);
    $statement->bindValue(":lastName", $lastName);
    $statement->bindValue(":birthday", $birthday);
    $statement->bindValue(":password", $password);
    $statement->execute();
    $statement->closeCursor();

    $_SESSION['email'] = $email;

    header('Location: userPage.php');
}
else {
    echo "Please go back and retry.";
}

function checkFirstName($data) {
    if (empty($data)) {
        print "First name error: please enter your first name.<br>";
        return FALSE;
    }
    else {
        print "Valid first name: ".$data."<br>";
        return TRUE;
    }
}

function checkLastName($data) {
    if (empty($data)) {
        print "Last name error: please enter your last name.<br>";
        return FALSE;
    }
    else {
        print "Valid last name: ".$data."<br>";
        return TRUE;
    }
}

function checkBirthday($data) {
    if (empty($data)) {
        print "Birthday error: please enter your birthday.<br>";
        return FALSE;
    }
    else {
        print "Valid birthday: ".$data."<br>";
        return TRUE;
    }
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
        print "Valid email: ".$data."<br>";
        return TRUE;
    }
    else {
        return FALSE;
    }
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
        print "Valid password: ".$data."<br>";
        return TRUE;
    }
    else {
        return FALSE;
    }
}