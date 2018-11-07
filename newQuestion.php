<?php
session_start();
$email = $_SESSION['email'];
$questionName = $_POST['questionName'];
$questionBody = $_POST['questionBody'];
$questionSkills = $_POST['questionSkills'];
if (checkQuestionName($questionName) && checkQuestionBody($questionBody) && checkQuestionSkills($questionSkills)) {
    $dsn = "mysql:host=sql1.njit.edu;dbname=rvz2";
    $user = "rvz2";
    $pass = "";
    try {
        $db = new PDO($dsn, $user, $pass);
    }catch(PDOException $e) {
        $error_message = $e->getMessage();
        echo "<p>An error occured while connecting to the database: $error_message </p>";
    }
    // Insert new question
    $query = "INSERT INTO questions (owneremail, title, body, skills) VALUES (:email, :questionName, :questionBody, :questionSkills)"; // Change to INSERT statement
    $statement = $db->prepare($query);
    $statement->bindValue(":email, $email");
    $statement->bindValue(":questionName", $questionName);
    $statement->bindValue(":questionBody", $questionBody);
    $statement->bindValue(":questionSkills", $questionSkills);
    $statement->execute();
    $statement->closeCursor();
    header("Location: userPage.php");
}
else {
    echo "Please go back and retry.";
}
function checkQuestionName ($data) {
    $validQuestionName = TRUE;
    if (empty($data)) {
        print "Question name error: please enter a question name.<br>";
        $validQuestionName = FALSE;
    }
    if (strlen($data) < 3) {
        if (strlen($data) == 0) {
            print "Question name error: please enter a question name that is at least 3 characters long. Your entry is ".strlen($data)." characters long.<br>";
        }
        else {
            print "Question name error: please enter a question name that is at least 3 characters long. Your entry '".$data."' is ".strlen($data)." characters long.<br>";
        }
        $validQuestionName= FALSE;
    }
    if ($validQuestionName == TRUE) {
        print "Valid question name: ".$data."<br>";
        return TRUE;
    }
    else {
        return FALSE;
    }
}
function checkQuestionBody ($data) {
    $validQuestionBody = TRUE;
    if (empty($data)) {
        print "Question body error: please enter a question body.<br>";
        $validQuestionBody = FALSE;
    }
    if (strlen($data) >= 500) {
        print "Question body error: please enter a question body that is less than 500 characters long. Your entry is ".strlen($data)." characters long.<br>";
        $validQuestionBody = FALSE;
    }
    if ($validQuestionBody == TRUE) {
        print "Valid question body: ".$data."<br>";
        return TRUE;
    }
    else {
        return FALSE;
    }
}
function checkQuestionSkills($data) {
    $data = str_replace(' ', '', $data);
    $data = explode(',', $data);
    if(count($data) < 2) {
        print "Question skills error: please enter at least 2 question skills.<br>";
        return FALSE;
    }
    else {
        print "Valid question skills.<br>";
        print_r($data);
        return TRUE;
    }
}