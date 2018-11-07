<?php
session_start();
$email = $_SESSION['email'];
// Display the add question form to the user
echo "<!doctype html><html>";
echo "<head><meta charset='utf-8'><title>Add Question Form</title></head>";
echo "<body><form action='newQuestion.php' method='post'>";
echo "<h1>New Question:</h1><input type='text' name='questionName' placeholder='Question name'><br>";
echo "<textarea name='questionBody' placeholder='Question body'></textarea><br>";
echo "<textarea name='questionSkills' placeholder='Enter multiple skills separated by commas'></textarea><br>";
echo "<input type='submit' value='Add Question'><br>";
echo "</form></body></html>";