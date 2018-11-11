<?php
session_start();

echo "<!doctype html><html lang='en-US'>";
echo "<head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>Validation</title>";
echo "<link href='main.css' type='text/css' rel='stylesheet'></head>";
echo "<body><div class='formContainer'><div class='formBox'>";

$firstName = $_POST['firstname'];
$lastName = $_POST['lastname'];
$birthday = $_POST['birthday'];
$email = $_POST["email"];
$password = $_POST["password"];

$goodFirst = checkFirstName($firstName);
$goodLast = checkLastName($lastName);
$goodBirthday = checkBirthday($birthday);
$goodEmail = checkEmail($email);
$goodPassword = checkPassword($password);

if ($goodFirst && $goodLast && $goodBirthday && $goodEmail && $goodPassword) {
    $dsn = "mysql:host=sql1.njit.edu;dbname=rvz2";
    $user = "rvz2";
    $pass = "";

    try {
        $db = new PDO($dsn, $user, $pass);
    }catch(PDOException $e) {
        $error_message = $e->getMessage();
        echo "<p class='errorMessage'><span class='errorType'>Database error: </span><span class='errorDescription'>An error occurred while connecting to the database: $error_message</span></p>";
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
    echo "<p class='errorMessage'>Please go back and retry.</p>";
}

function checkFirstName($data) {
    if (empty($data)) {
        print "<p class='errorMessage'><span class='errorType'>First name error: </span><span class='errorDescription'>please enter your first name.</span></p><br>";
        return FALSE;
    }
    else {
        return TRUE;
    }
}

function checkLastName($data) {
    if (empty($data)) {
        print "<p class='errorMessage'><span class='errorType'>Last name error: </span><span class='errorDescription'>please enter your last name.</span></p><br>";
        return FALSE;
    }
    else {
        return TRUE;
    }
}

function checkBirthday($data) {
    if (empty($data)) {
        print "<p class='errorMessage'><span class='errorType'>Birthday error: </span><span class='errorDescription'>please enter your birthday.</span></p><br>";
        return FALSE;
    }
    else {
        return TRUE;
    }
}

function checkEmail($data) {
    $validEmail = TRUE;
    if (empty($data)) {
        print "<p  class='errorMessage'><span class='errorType'>Email error: </span><span class='errorDescription'>please enter an email address.</span></p><br>";
        $validEmail = FALSE;
    }
    if (strpos($data, '@') == FALSE) {
        if (strlen($data) == 0) {
            print "<p class='errorMessage'><span class='errorType'>Email error: </span><span class='errorDescription'>please use '@' in your email address.</span></p><br>";
            $validEmail = FALSE;
        }
        else {
            print "<p class='errorMessage'><span class='errorType'>Email error: </span><span>please use '@' in your email address. Your entry: ".$data."</span></p><br>";
            $validEmail = FALSE;
        }
    }
    return $validEmail;
}

function checkPassword ($data) {
    $validPassword = TRUE;
    if (empty($data)) {
        print "<p class='errorMessage'><span class='errorType'>Password error: </span><span class='errorDescription'>please enter a password.</span></p><br>";
        $validPassword = FALSE;
    }
    if (strlen($data) < 8) {
        if (strlen($data) == 0) {
            print "<p class='errorMessage'><span class='errorType'>Password error: </span><span class='errorDescription'>please enter a password that is at least 8 characters long. Your entry is ".strlen($data)." characters long.</span></p><br>";
        }
        else {
            print "<p class='errorMessage'><span class='errorType'>Password error: </span><span class='errorDescription'>please enter a password that is at least 8 characters long. Your entry '".$data."' is ".strlen($data)." character(s) long.</span></p><br>";
        }
        $validPassword = FALSE;
    }
    return $validPassword;
}

echo "</div></div></body></html>";