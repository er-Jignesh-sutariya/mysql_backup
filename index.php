<?php
require_once "vendor/autoload.php";
use Ifsnop\Mysqldump as IMysqldump;
ini_set('max_execution_time','0');

$servername = "localhost";
$username = "root";
$password = "";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$sql = "SHOW DATABASES";
$dbs = $conn->query($sql);
if ($dbs->num_rows > 0)
    while($row = $dbs->fetch_assoc()){
        $ignore = ['information_schema', 'mysql', 'performance_schema', 'phpmyadmin', 'test'];
        if (! in_array($row['Database'], $ignore)) {
            $dump = new IMysqldump\Mysqldump("mysql:host=localhost;dbname=".$row['Database']."", "root", "");
            if (!file_exists('backup/'.date('d-m-Y')))
                mkdir('backup/'.date('d-m-Y'));
            $dump->start('backup/'.date('d-m-Y').'/'.$row['Database'].'.sql');
        }
    }
else
  echo "0 results";

$conn->close();