<?php
session_start();

$email = $_SESSION['email'];
$questionName = $_POST['questionName'];
$questionBody = $_POST['questionBody'];
$questionSkills = $_POST['questionSkills'];

echo "<!doctype html><html lang='en-US'>";
echo "<head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>Validation</title>";
echo "<link href='main.css' type='text/css' rel='stylesheet'></head>";
echo "<body><div class='formContainer'><div class='formBox'>";

$goodName = checkQuestionName($questionName);
$goodBody = checkQuestionBody($questionBody);
$goodSkills = checkQuestionSkills($questionSkills);

if ($goodName && $goodBody && $goodSkills) {
    $dsn = "mysql:host=sql1.njit.edu;dbname=rvz2";
    $user = "rvz2";
    $pass = "";

    try {
        $db = new PDO($dsn, $user, $pass);
    }catch(PDOException $e) {
        $error_message = $e->getMessage();
        echo "<p class='errorMessage'><span class='errorType'>Database error: </span><span class='errorDescription'>An error occurred while connecting to the database: $error_message</span></p>";
    }

    // Insert new question
    $query = "INSERT INTO questions (owneremail, title, body, skills) VALUES (:email, :questionName, :questionBody, :questionSkills)";
    $statement = $db->prepare($query);
    $statement->bindValue(":email", $email);
    $statement->bindValue(":questionName", $questionName);
    $statement->bindValue(":questionBody", $questionBody);
    $statement->bindValue(":questionSkills", $questionSkills);
    $statement->execute();
    $statement->closeCursor();

    header("Location: userPage.php");
}
else {
    echo "<p class='errorMessage'>Please go back and retry.</p>";
}
function checkQuestionName ($data) {
    $validQuestionName = TRUE;
    if (empty($data)) {
        print "<p class='errorMessage'><span class='errorType'>Question name error: </span><span class='errorDescription'>please enter a question name.</span></p><br>";
        $validQuestionName = FALSE;
    }
    if (strlen($data) < 3) {
        if (strlen($data) == 0) {
            print "<p class='errorMessage'><span class='errorType'>Question name error: </span><span class='errorDescription'>please enter a question name that is at least 3 characters long. Your entry is ".strlen($data)." characters long.</span></p><br>";
        }
        else {
            print "<p class='errorMessage'><span class='errorType'>Question name error: </span><span class='errorDescription'>please enter a question name that is at least 3 characters long. Your entry '".$data."' is ".strlen($data)." character(s) long.</span><br>";
        }
        $validQuestionName= FALSE;
    }
    return $validQuestionName;
}
function checkQuestionBody ($data) {
    $validQuestionBody = TRUE;
    if (empty($data)) {
        print "<p class='errorMessage'><span class='errorType'>Question body error: </span><span class='errorDescription'>please enter a question body.</span><br>";
        $validQuestionBody = FALSE;
    }
    if (strlen($data) >= 500) {
        print "<p class='errorMessage'><span class='errorType'>Question body error: </span><span class='errorDescription'>please enter a question body that is less than 500 characters long. Your entry is ".strlen($data)." characters long.</span></p><br>";
        $validQuestionBody = FALSE;
    }
    return $validQuestionBody;
}
function checkQuestionSkills($data) {
    $data = str_replace(' ', '', $data);
    $data = explode(',', $data);
    if(count($data) < 2) {
        print "<p class='errorMessage'><span class='errorType'>Question skills error: </span><span class='errorDescription'>please enter at least 2 question skills.</span></p><br>";
        return FALSE;
    }
    else {
        return TRUE;
    }
}

echo "</div></div></body></html>";