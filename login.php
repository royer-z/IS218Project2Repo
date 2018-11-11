<?php
session_start();
// Extract data from form
$email = $_POST["email"];
$password = $_POST["password"];

echo "<!doctype html><html lang='en-US'>";
echo "<head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>Validation</title>";
echo "<link href='main.css' type='text/css' rel='stylesheet'></head>";
echo "<body><div class='formContainer'><div class='formBox'>";

$goodEmail = checkEmail($email);
$goodPassword = checkPassword($password);

if ($goodEmail && $goodPassword) { // If Email and Password are VALID, check if user is in database
    $dsn = "mysql:host=sql1.njit.edu;dbname=rvz2";
    $user = "rvz2";
    $pass = "";

    try {
        $db = new PDO($dsn, $user, $pass);
    }catch(PDOException $e) {
        $error_message = $e->getMessage();
        echo "<p class='errorMessage'><span class='errorType'>Database error: </span><span class='errorDescription'>An error occurred while connecting to the database: $error_message</span></p>";
    }

    // Query database for existing user
    $query = 'SELECT email FROM accounts WHERE email = :email';
    $statement = $db->prepare($query);
    $statement->bindValue(':email', $email);
    $statement->execute();
    $account = $statement->fetch();
    $statement->closeCursor();

    if ($account['email'] === $email) {
        $query = 'SELECT email, password FROM accounts WHERE email = :email AND password = :password';
        $statement = $db->prepare($query);
        $statement->bindValue(':email', $email);
        $statement->bindValue(':password', $password);
        $statement->execute();
        $account = $statement->fetch();
        $statement->closeCursor();

        if ($account['password'] === $password) {
            $_SESSION['email'] = $email;
            header('Location: userPage.php');
        }
        else {
            echo "<p class='errorMessage'><span class='errorType'>Login error: </span><span class='errorDescription'>Incorrect email or password.</span></p>";
            echo "<p class='errorMessage'>Please go back and retry.</p>";
        }
    }
    else {
        // Redirect to registration page
        header('Location: registration.html');
    }
}
else { // If Email or Password is NOT VALID
    echo "<p class='errorMessage'>Please go back and retry.</p>";
}

function checkEmail($data) {
    $validEmail = TRUE;
    if (empty($data)) {
        echo "<p class='errorMessage'><span class='errorType'>Email error: </span><span class='errorDescription'>please enter an email address.</span></p><br>";
        $validEmail = FALSE;
    }
    if (strpos($data, '@') === FALSE) {
        if (strlen($data) == 0) {
            echo "<p class='errorMessage'><span class='errorType'>Email error: </span><span class='errorDescription'>please use '@' in your email address.</span></p><br>";
            $validEmail = FALSE;
        }
        else {
            echo "<p class='errorMessage'><span class='errorType'>Email error: </span><span class='errorDescription'>please use '@' in your email address. Your entry: $data</span></p><br>";
            $validEmail = FALSE;
        }
    }
    return $validEmail;
}
function checkPassword ($data) {
    $validPassword = TRUE;
    if (empty($data)) {
        echo "<p class='errorMessage'><span class='errorType'>Password error: </span><span class='errorDescription'>please enter a password.</span></p><br>";
        $validPassword = FALSE;
    }
    if (strlen($data) < 8) {
        if (strlen($data) == 0) {
            echo "<p class='errorMessage'><span class='errorType'>Password error: </span><span class='errorDescription'>please enter a password that is at least 8 characters long. Your entry is ".strlen($data)." characters long.</span></p><br>";
        }
        else {
            echo "<p class='errorMessage'><span class='errorType'>Password error: </span><span class='errorDescription'>please enter a password that is at least 8 characters long. Your entry '".$data."' is ".strlen($data)." character(s) long.</span></p><br>";
        }
        $validPassword = FALSE;
    }
    return $validPassword;
}

echo "</div></div></body></html>";