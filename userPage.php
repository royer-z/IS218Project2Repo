<?php
session_start();
$email = $_SESSION['email'];
$dsn = "mysql:host=sql1.njit.edu;dbname=rvz2";
$user = "rvz2";
$pass = "";
try {
    $db = new PDO($dsn, $user, $pass);
}catch(PDOException $e) {
    $error_message = $e->getMessage();
    echo "<p>An error occurred while connecting to the database: $error_message </p>";
}
// Query database for current user information
$query = "SELECT fname, lname FROM accounts WHERE email = :email";
$statement = $db->prepare($query);
$statement->bindValue(":email", $email);
$statement->execute();
$account = $statement->fetchAll();
$statement->closeCursor();
// Display user's first and last name
$firstN = $account['fname'];
$lastN = $aacount['lname'];
echo "<h1>Welcome $firstN $lastN!</h1><br><br>";
// Display all the questions for the current user
$query = "SELECT title, body, skills, score FROM questions WHERE email = :email";
$statement = $db->prepare($query);
$statement->bindValue(":email", $email);
$statement->execute();
$questions = $statement->fetchAll();
$statement->closeCursor();
$title = $questions['title'];
$body = $questions['body'];
$skills = $questions['skills'];
$score = $questions['score'];
foreach ($questions as $question) {
    echo "<h3>Title: </h3><br><p>$title</p><br>";
    echo "<h4>Body: </h4><br><p>$body</p><br>";
    echo "<h5>Skills: </h5><br><p>$skills</p><br>";
    echo "<h6>Score: </h6><br><p>$score</p><br><br>";
}
// Takes user to add new question form
echo "<form action='questionForm.php' method='post'><input type='submit' value='New question'><br></form>";