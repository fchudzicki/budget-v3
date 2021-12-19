<!--
/* 
 * Copyright Filip Chudzicki 2019
 */
-->
<?php
include 'header.php'; 
?>
<div class="container">
  
<?php



if (isset($_GET['newYear']))
{
    $rok = filter_var($_GET['newYear'], FILTER_SANITIZE_NUMBER_INT);
}
$miesiac = date('n');
$rok = date('Y');
$miesiac_pl = array(1 => 'styczeń', 'luty', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik', 'listopad', 'grudzień');

if (isset($_POST['month']))
{ 	$miesiac = $_POST['month'];}
if (isset($_POST['year']))
{ 	$rok = $_POST['year'];}


function month_sum($rok,$miesiac){
$mysqli = dbconnect();
$sql_mouth_sum = "SELECT SUM(expenses.expsum) FROM expenses WHERE YEAR(expenses.expdate)='$rok' AND  MONTH( expenses.expdate ) ='$miesiac'";
if($result = $mysqli->query($sql_mouth_sum)){
    if($result->num_rows > 0){
        while($row = $result->fetch_row()){
        $expsum = $row[0];
        return $expsum;
        }
$result->free();
        }
    }
}
function month_type_sum($rok,$miesiac,$type_id){
    $mysqli = dbconnect();
    $month_type_sum = " SELECT SUM(exptype.cost) FROM exptype, expenses 
    WHERE (exptype.expenseid)=(expenses.exp_id) AND typenameid = '$type_id' 
    AND YEAR(expenses.expdate)='$rok' AND  MONTH( expenses.expdate ) ='$miesiac'";
   
    if($result = $mysqli->query($month_type_sum)){
        if($result->num_rows > 0){
            while($row = $result->fetch_row()){
            $typesum = $row[0];
            return $typesum;
            }
    $result->free();
            }
        }
    }
    
function month_cat_sum($rok,$miesiac,$cat_id){
    $mysqli = dbconnect();

    $month_cat_sql = "SELECT SUM(exptype.cost) FROM exptype, expenses, exptypename 
    WHERE (exptype.expenseid)=(expenses.exp_id)
    AND exptypename.exptype_id=exptype.typenameid 
    AND exptypename.exp_cat_id=$cat_id
    AND YEAR(expenses.expdate)='$rok' AND  MONTH( expenses.expdate ) ='$miesiac'";
    
   
    if($result = $mysqli->query($month_cat_sql)){
        if($result->num_rows > 0){
            while($row = $result->fetch_row()){
            $catsum = $row[0];
            return $catsum;
            }
    $result->free();
            }
        }
    }


?>
<div class="stat_mies">



 
    <h3>Statystyki <?php echo $miesiac_pl[$miesiac]." ".$rok ; ?> </h3>
    	<div class="MYchoice">
        <form method="Post">
		<select id="select_month" name="month" onchange="this.form.submit()" >
<?php
		

for ($i = 1; $i <= 12; $i++)
{
 
    echo '<option value="'.$i.'"';
    if ($i == $miesiac) echo ' selected="selected"';
    echo '>'.$i."-".$miesiac_pl[$i].'</option>';
}
?>
		</select>

		<select id="select_year" name="year" onchange="this.form.submit()">		 
<?php for ($i = date('Y'); $i >= ($rok - 5); $i--)
{ 
    echo '<option value="'.$i.'"';
    if ($i == $rok) echo ' selected="selected"';
    echo '>'.$i.'</option>';
}
        ?>
        </select>
		</form>	  
        <h2 class="text-center">Suma wydatków <?php echo month_sum($rok,$miesiac);?> zł</h2>
        
        <div class="accordion" id="accordionExample">
                    <?php 
                    for ($i=1;($i<=expcatnumber());$i++){

                    

                        ?>
                        
                    <div class="card">
                        <div class="card-header" id="heading<?php echo $i;?>">
                            <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
                                <?php echo expcatename($i);?> <b><?php echo month_cat_sum($rok, $miesiac, $i);?> zł</b>
                                </button>
                            </h2>
                        </div>
                        <div id="collapse<?php echo $i;?>" class="collapse " aria-labelledby="heading<?php echo $i;?>" data-parent="#accordionExample">
                            <div class="card-body">
                                <?php for($j=1;$j<=number_type_in_cat($i);$j++){
?>
                            <p>
                            <?php echo exptypename_forcat($j,$i) ;?> <b><?php echo month_type_sum($rok, $miesiac, exptype_id_forcat($j,$i));?> zł</b>
                            </p>
                            

                                 <?php } ?>
                            </div>
                        </div>
                        </div>
                    <?php }      ?>
             

              


                </div>
        
 
        



      
        </div> 




<?php
        $mysqli->close();
?>


</body>
</html>
