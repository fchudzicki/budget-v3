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
$number_of_exptype = 6;
$rok = date("Y");
$teraz = date("Ymd");
$terazstr = strtotime( $teraz );
if (isset($_GET['newYear']))
{
    $rok = filter_var($_GET['newYear'], FILTER_SANITIZE_NUMBER_INT);
}

//Edycja rekordu z modal window
// if (isset($_POST['edytujSubmit']))
// {
//     $id = filter_var($_POST['Id'], FILTER_SANITIZE_NUMBER_INT);
//     $data = filter_var($_POST['dataUpdate'], FILTER_SANITIZE_STRING);
//     $kwota = filter_var($_POST['kwotaUpdate'], FILTER_SANITIZE_STRING);
//     $kwota = str_replace(",",".",$kwota);
//     $typ_id = $_POST['typ_id'];
//     $szczegoly = filter_var($_POST['szczegUpdate'], FILTER_SANITIZE_STRING);
//     $sposob_id = $_POST['sposob_id'];

    
    
    
//    $query = "UPDATE wydatki SET typ_id=?, szczegoly=?, data=?, kwota=?, sposob_id=? WHERE wyd_id=?";
//    $statement = $mysqli->prepare($query);
//    $statement->bind_param('isssii', $typ_id, $szczegoly, $data, $kwota, $sposob_id, $id);
//     if($statement->execute()){
//         print '<div class = "text-success"> Zmieniono rekord o id : ' .$id.' data wydatku '.$data.' kwota '.$kwota.' zł</div>'; 
//    }else{
//        die('Error : ('. $mysqli->errno .') '. $mysqli->error);
//    }
//    $statement->close();
// }

 

//            Dodawanie rekordu do tabeli

    if(isset($_POST['dodaj_wydatek'])){
    
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $sum = filter_var($_POST['expsum'], FILTER_SANITIZE_STRING);
    $sum = str_replace(",",".",$sum);
    $expensedate = filter_var($_POST['expensedate'], FILTER_SANITIZE_STRING);
    $userid=$_SESSION['id'];
 
    
    $expenseid = insExpense($userid,$expensedate,$description,$sum);

 
    for ($n=1;$n<=$number_of_exptype;$n++){

    $value = $_POST['value_'.$n];

    if (($value==null)||($value==0)){continue;}

    insExpenseType($value,$n,$expenseid);
       }

}
                
?>

        <!-----------------------Dodawanie wydatku FORM------------------------------------>
        
        <div class="row">
        <div class="container bg-light col-sm-6" id="wyd-add">
            <h5>Dodaj wydatek</h5>
                <form method="post">
                            
                
                <div class="form-group row ">
                    <label for="ins_description" class="col-sm-4 col-form-label">Szczegóły</label>
                    <div class="col-sm-6">
                        <input type="text" name="description" class="form-control" id="ins_description" >
                    </div>
                </div>
                <div class="form-group row">
                    <label for="dod_wyd_data" class="col-sm-4 col-form-label">Data</label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control" id="dod_wyd_data" name="expensedate" required="true">
                    </div>
                </div>
                

                <?php 
                for ($i=1;$i<=$number_of_exptype;$i++) {

                    switch ($i) {
                        case 1:
                            $exptypelabel = "Zakupy spożywcze";
                            break;
                        case 2:
                           $exptypelabel = "Posiłki na mieście / w pracy";
                            break;
                        case 3:
                             $exptypelabel = "Jedzenie w podróży służbowej";
                            break;
                        case 4:
                             $exptypelabel = "Wydatki na lekarzy";
                            break;
                        case 5:
                             $exptypelabel = "Wydatki na leki ";
                            break;
                        case 6:
                            $exptypelabel = "Ubrania Monika";
                            break;    
                    }
?>
                <div class="form-group row ">
                    <label for="ins_value<?php echo $i;?>" class="col-sm-4 col-form-label"><?php echo $exptypelabel;?></label>
                    <div class="col-sm-6">
                        <input type="number" step=0.01 name="value_<?php echo $i;?>" class="single_expense form-control" id="ins_value<?php echo $i;?>">
                    </div>
                </div>
<?php
                }

?>
               
                
                <div class="form-group row ">
                    <label for="ins_sum" class="col-sm-4 col-form-label">Suma</label>
                    <div class="col-sm-6">
                        <input type="number" step=0.01 name="expsum" class="form-control" id="ins_sum" >
                    </div>
                </div>
                        <div class="form-group row">
                        <div class="col-sm">
                            <input type="submit" class="form-control btn btn-success" name="dodaj_wydatek" value="Dodaj">
                        </div>
                        </div>
                    </form>
                </div>
         
            </div>
        <!--Koniec dodawanie wydatkow-->
        
<!--        Tabela z opłatami-->
<div class="row">
    <nav class="col-9" aria-label=...>
      <ul class="pagination">
          <li class="page-item" id="previous-page"><a class="page-link" href="javascript:void(0)" aria-label=Previous><span aria-hidden=true>&laquo;</span></a></li>
      </ul>
    </nav>
    
    <form class="col-3"  id="formYear" method="get" >
        <div class="form-group row">
       <label for="choseYear" class="col-form-label col">Rok</label>
        <select class="form-control col " name="newYear" id="choseYear" onchange="this.form.submit()">
            <option value=""></option>
            <?php 
           for(($i = date("Y")); $i >= 2016; $i-- ){
               echo '<option value='.$i.'>'.$i.'</option>';
           }  ?>
        </select>
        </div>
    </form>

</div>
    <?php
/******************************************Tabel query************************************ */
    $sql = "SELECT * FROM expenses, uzytkownicy 
         WHERE expenses.userid = uzytkownicy.user_id
         AND YEAR( expenses.expdate ) ='$rok' 
         ORDER BY expenses.expdate  DESC";

    if($result = $mysqli->query($sql)){
        if($result->num_rows > 0){
            ?>
            <table class='table table-hover' id='tabelaOplat' >
                <thead>
                    <tr>
                        <th scope='col'>Data</th>
                        <th scope='col'>Opis</th>
                       <th scope='col'>Suma</th>
                      <th scope='col'>Kto zapisał</th>
                       <th scope='col'></th>
                   </tr>
              </thead>
             <tbody id='daneTabeli'>
            <?php
            while($row = $result->fetch_array()){
                $bgkolor = "";
                $expdescription = $row["expdescription"];
                $data = $row["expdate"];
                $expsum = $row["expsum"];
                $expsum = str_replace(".",",",$expsum);
                $username= $row["user"];                
                $id =  $row["exp_id"];
               
                
                ?>
              
             
 

                    <tr>
                    <td><?php echo $data;?></td>
                    <td><?php echo $expdescription;?></td>
                    <th scope='row'> <span><?php echo $expsum;?> zł</span></td>
                    <td><?php echo $username;?></td>
                       
                                             
                        <td><button type='button' class='btn btn-danger' data-toggle='modal' data-target='#wydModal<?php echo $id;?>'>Edytuj</button>

                    <?php    
                    /************************   Modal Window start   */
                   
                    
                    ?>
                        <div class='modal fade' id='wydModal<?php echo $id;?>' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                            <div class='modal-dialog' role='document'>
                                <form method='post'>    
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='exampleModalLongTitl'>Edytuj Wydatek</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                              <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                        <input name='Id' value = "<?php echo $id;?>" hidden>   
                                    
                                        <div class='form-group row'>
                                            <label for='kwota<?php echo $id;?>' class='col col-form-label'>Kwota</label>
                                            <input id='kwota<?php echo $id;?>'class = 'col-6 form-control inputNazwa' name='kwotaUpdate' type='' value='<?php echo $expsum; ?>'>
                                        </div>
                                        <div class='form-group row'>
                                            <label for='data<?php echo $id;?>' class='col col-form-label'>Termin płatności</label>                                        
                                            <input id='data<?php echo $id;?>'class = 'col-6 form-control inputKonto' name='dataUpdate' type='date' value='<?php echo $data; ?>'>
                                        </div>
                                        <label for='szczeg<?php echo $id;?>' class='col-sm-5 col-form-label'>Szczegóły</label>                                        
                                        <input id='szczeg<?php echo $id;?>'class = 'form-control inputSzczeg' name='szczegUpdate' type='text' value='<?php echo $expdescription; ?>'>
<?php

                                    $sqlexptype = "SELECT * FROM expenses, exptype, exptypename 
                                    WHERE expenses.exp_id = '$id'
                                    AND exptype.expenseid = '$id'
                                    AND exptype.typenameid = exptypename.exptype_id
                                    ORDER BY exptype.typenameid  ASC";

                                    if($result1 = $mysqli->query($sqlexptype)){
                                        if($result1->num_rows > 0){
                                   while($row1 = $result1->fetch_array()){
                                       
                                    $expid = $row1["expenseid"];
                                   
                                    ?>

                                    <div class='form-group row'>
                                    <label for='expensetype_<?php echo $expid;?>' class='col col-form-label'><?php echo $row1["name"];?></label>
                                    <input id='expensetype_<?php echo $expid;?>'class = 'col-6 form-control inputNazwa' name='expensetype_<?php echo $expid;?>update' type='text' value='<?php echo $row1["cost"]; ?>'>
                                </div>
                                         <?php
                                        }
                                       $result1->free();

                                   } else{
                                       echo "No records matching your query were found.";
                                       }
                                   } else{ 
                                       echo "ERROR: Could not able to execute $sqlexptype. " . $mysqli->error;
                                   }

                                    
                                echo
                                        "<div class='modal-footer'>
                                          <button type='button' class='btn btn-secondary' data-dismiss='modal'>Zamknij</button>
                                          <button type='submit' name ='edytujSubmit' class='btn btn-primary'>Zapisz zmiany</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                          </div>";
                        echo"</td>";
                    echo "</tr>";
                     }
                echo "</tbody>";
            echo "</table>";
            $result->free();
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
    }
//Koniec tabeli z opłatami
    
    
    
    // Close connection
    $mysqli->close();
    ?>


                        
<script src="js\expense_form.js" type="text/javascript"></script>


</div> 
</body>
</html>