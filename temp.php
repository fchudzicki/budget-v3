<?php
require_once 'include/connect.php';
$czas = date("Y-m-d H:i:s");
$temp = ($_GET['temp']);
$wilg = ($_GET['wilg']);


$sql = "INSERT INTO dom_dane (temperatura, wilgotnosc) VALUES ($temp, $wilg)";
if($wilg != 0){
$mysqli->query($sql);

// Close connection
$mysqli->close();
}
echo $temp.$wilg.$czas;

?>