<?php
session_start();
$email = $_SESSION['email'];
// Display the add question form to the user
echo "<!doctype html><html>";
echo "<head><meta charset='utf-8'><meta name='viewport' content='width=device-width, initial-scale=1'><title>Add Question</title>";
echo "<link href='main.css' type='text/css' rel='stylesheet'></head>";
echo "<body><div class='formContainer'><div class='formBox'><form action='newQuestion.php' method='post'>";
echo "<h1 class='formHeading'>Add a question!</h1><input type='text' name='questionName' placeholder='Question name'><br>";
echo "<textarea name='questionBody' placeholder='Question body'></textarea><br>";
echo "<textarea name='questionSkills' placeholder='Enter multiple skills separated by commas'></textarea><br>";
echo "<input type='submit' class='formButton' value='Add'><br>";
echo "</form></div></div></body></html>";