<?php
require_once 'header.php';

?>
<?php
$id = $_GET['id'];
$sql = "SELECT * FROM nazwa_oplaty WHERE firma_id= '$id' ";

if($result = $mysqli->query($sql)){
    if($result->num_rows > 0){
        while($row = $result->fetch_array()){
        
                $firmaID = $row['firma_id'];
                $nazwaFirmy = $row['firma'];
                $nrKonta = $row['nrkonta'];
                $view = $row['view'];

        }
        
         $result->free();
    } else{
        echo "No records matching your query were found.";
    }
} else{
    echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
}
 
// Close connection
$mysqli->close();
?>

<div class="container">
    <form method="post">
        <input name="nazwaFirmy"type="text" value="<?php echo $nazwaFirmy ?>"/>
        <input name="nrKonta"type="text" value="<?php echo $nrKonta ?>"/>
        <input name="view"type="checkbox" value="<?php echo $view ?>"/>
    </form>
</div>



