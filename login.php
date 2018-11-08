<?php
session_start();
// Extract data from form
$email = $_POST["email"];
$password = $_POST["password"];
$validAccount = FALSE;

echo "<!doctype html><html lang='en-US'>";
echo "<head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>Page</title>";
echo "<link href='main.css' type='text/css' rel='stylesheet'></head>";
echo "<body><div class='formContainer'><div class='formBox'>";

if (checkEmail($email) && checkPassword($password)) { // If Email and Password are VALID, check if user is in database
    $dsn = "mysql:host=sql1.njit.edu;dbname=rvz2";
    $user = "rvz2";
    $pass = "";

    try {
        $db = new PDO($dsn, $user, $pass);
    }catch(PDOException $e) {
        $error_message = $e->getMessage();
        echo "<p>An error occurred while connecting to the database: $error_message </p>";
    }

    // Query database for existing user
    $query = 'SELECT email, password FROM accounts WHERE email = :email AND password = :password';
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':password', $password);
    $statement->execute();
    $account = $statement->fetch();
    $statement->closeCursor();

    if ($account['email'] === $email) {
        $validAccount = TRUE;
        $_SESSION['email'] = $email;
        header('Location: userPage.php');
    }
    else {
        $validAccount = FALSE;
        // Redirect to registration page
        header('Location: registration.html');
    }
}
else { // If Email or Password is NOT VALID
    echo "Please go back and retry.";
}

function checkEmail($data) {
    $validEmail = TRUE;
    if (empty($data)) {
        echo "<p>Email error: please enter an email address.<p><br>";
        $validEmail = FALSE;
    }
    if (strpos($data, '@') == FALSE) {
        if (strlen($data) == 0) {
            echo "<p>Email error: please use '@' in your email address.<p><br>";
            $validEmail = FALSE;
        }
        else {
            echo "<p>Email error: please use '@' in your email address. Your entry: ".$data."<p><br>";
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
            echo "Password error: please enter a password that is at least 8 characters long. Your entry '".$data."' is ".strlen($data)." character(s) long.<br>";
        }
        $validPassword = FALSE;
    }
    if ($validPassword == TRUE) {
        echo "Valid password.<br>";
    }
    return $validPassword;
}

echo "</div></div></body></html>";