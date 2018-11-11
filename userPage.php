<?php
session_start();

$email = $_SESSION['email'];
$dsn = "mysql:host=sql1.njit.edu;dbname=rvz2";
$user = "rvz2";
$pass = "";

echo "<!doctype html><html lang='en-US'>";
echo "<head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>Your Page</title>";
echo "<link href='main.css' type='text/css' rel='stylesheet'></head>";
echo "<body><div class='formContainer'><div class='formBox'>";

try {
    $db = new PDO($dsn, $user, $pass);
}catch(PDOException $e) {
    $error_message = $e->getMessage();
    echo "<p>An error occurred while connecting to the database: $error_message</p>";
    echo "<p>Please go back and retry.</p>";
}

// Query database for current user information
$query = "SELECT fname, lname FROM accounts WHERE email = :email";
$statement = $db->prepare($query);
$statement->bindValue(':email', $email);
$statement->execute();
$account = $statement->fetch();
$statement->closeCursor();

// Display user's first and last name
echo "<h1 class='formHeading'>Welcome ".$account['fname']." ".$account['lname']."!</h1>";
echo "<h2 class='formHeading whiteUnderline' id='yourQuestions'>Your questions:</h2>";

// Display all the questions for the current user
$query = "SELECT title, body FROM questions WHERE owneremail = :email";
$statement = $db->prepare($query);
$statement->bindValue(':email', $email);
$statement->execute();
$questions = $statement->fetchAll();
$statement->closeCursor();

$questionCounter = 1;
foreach ($questions as $question) {
    echo "<h2 class='questionHeading whiteUnderline'>Question:</h2><p class='questionContent'>&nbsp;$questionCounter</p><br>";
    echo "<h2 class='questionHeading'>Title:&nbsp;</h2><p class='questionContent'>".$question['title']."</p><br>";
    echo "<h2 class='questionHeading'>Body:&nbsp;</h2><p class='questionContent'>".$question['body']."</p><br><br>";
    $questionCounter++;
}

// Takes user to add new question form
echo "<form action='questionForm.php' method='post'><input type='submit' class='formButton' value='New question'><br></form>";
echo "</div></div></body></html>";