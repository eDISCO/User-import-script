#!/usr/bin/php
<?php
function validate_slice($slice,$conn){
	// validates if the data comes in format:
	// username, firstname, surname, email
	// first let's just test if we have correct slice:
	//var_dump($slice);
	//This works well.
	// validate the emails and remove any records with empty fields:
	$email_correct =filter_var($slice[3], FILTER_VALIDATE_EMAIL);
	if ($email_correct == false){
    } else {
        //insert user maybe?
        insert_user($slice,$conn);
    }

}
function init_db(){
    $server = 'localhost';
    $user = 'user';
    $pass = 'password';
    $database = 'equipment';
    // Create connection
    $conn = new mysqli($server, $user, $pass);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $query = "use $database";
    $conn->query($query);
    if ($conn->error===TRUE){
        echo $conn->error;
        }
    return $conn;
}
function insert_user($slice, $conn){
    $query = "INSERT INTO user (username,forename,surname,email) VALUES ('$slice[0]','$slice[1]','$slice[2]','$slice[3]')";
    //echo "\n" . $query . "\n";    
    $conn->query($query);
    if ($conn->error===TRUE){
        echo $conn->error;
        }
    return TRUE;

}

if (isset($argv[1])) {
$conn = init_db();    
$file = $argv[1];
echo "Opening file: " + $file + " to read line by line";
$handle = fopen($file, "r");
if ($handle) {
    $c = 0;
    while (($line = fgets($handle)) !== false) {
        // process the line read.
	$slice = explode(",",$line);
    $slice = array_map('trim',$slice);
	// no we have a slice of things
	// ignore first one? yes
	if ($c==0){
	//noop
	} else {
	//here's the import:
	validate_slice($slice,$conn);
	}
	$c++;
    }

    fclose($handle);
    $conn->close();
} else {
    // error opening the file.
} 

} else {
    echo "This script will import new users to Kit-Cat user database without creating a password.\n";
    echo "the input file is to be passed as command line argument: ./user_import.php users.csv\n";
    echo "The database connection details are defined in init_db() function,\n";
    echo "Expected csv format: Username,First Name,Surname, Email\n";    
}
?>
