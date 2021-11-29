<!--
/* 
 * Copyright Filip Chudzicki 2019
 */
-->
<?php
include 'header.php'; ?>
<div class="container">
  
<?php
$teraz = date("Ymd");
$terazstr = strtotime( $teraz );



//Edycja rekordu z modal window
if (isset($_POST['edytujSubmit']))
{
    $id = filter_var($_POST['Id'], FILTER_SANITIZE_NUMBER_INT);
    $deadline = filter_var($_POST['dataUpdate'], FILTER_SANITIZE_STRING);
    $kwota = filter_var($_POST['kwotaUpdate'], FILTER_SANITIZE_STRING);
    $kwota = str_replace(",",".",$kwota);
    $firmaID = $_POST['firma_id'];

    if(isset($_POST['statusUpdate'])){
        $newstatus = filter_var($_POST['statusUpdate'], FILTER_SANITIZE_NUMBER_INT);
       }
       else {$newstatus = 0;}
   $query = "UPDATE platnosc SET kwota=?, data=?, firma_id=?, status=? WHERE id=?";
   $statement = $mysqli->prepare($query);
   $statement->bind_param('ssiii', $kwota, $deadline, $firmaID, $newstatus, $id);
    if($statement->execute()){

   }else{
       die('Error : ('. $mysqli->errno .') '. $mysqli->error);
   }
}
//Zmiana statusu opłaty
if(isset($_POST['zmianaStatusu'])){
         $id = filter_var($_POST['zmianaStatusu'], FILTER_SANITIZE_NUMBER_INT);
                             
                if(isset($_POST['status'])){
                 $newstatus = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
                }
                else {$newstatus = 0;}

                $query = "UPDATE platnosc SET status=? WHERE id=?";
                $statement = $mysqli->prepare($query);
                $statement->bind_param('ii', $newstatus, $id);

                if($statement->execute()){
                    
                }else{
                    die('Error : ('. $mysqli->errno .') '. $mysqli->error);
                }
            }
//Usuwanie rekordu/////////////////////////////////////
if (isset($_POST['delete']))
{
    $id = filter_var($_POST['Id'], FILTER_SANITIZE_NUMBER_INT);
    $data = filter_var($_POST['dataUpdate'], FILTER_SANITIZE_STRING);
    $kwota = filter_var($_POST['kwotaUpdate'], FILTER_SANITIZE_STRING);
 
   $query = "DELETE FROM `platnosc` WHERE id=?";
   $statement = $mysqli->prepare($query);
   $statement->bind_param('i', $id);
    if($statement->execute()){
        print '<div class = "text-danger"> Usunięto rekord o id : ' .$id.' termin płatności '.$data.' kwota '.$kwota.'</div>'; 
   }else{
       die('Error : ('. $mysqli->errno .') '. $mysqli->error);
   }
}

//            Dodawanie rekordu do tabeli
    if(isset($_POST['termin'])){

    $kwota = filter_var($_POST['kwota'], FILTER_SANITIZE_STRING);
    $kwota = str_replace(",",".",$kwota);
    $termin = filter_var($_POST['termin'], FILTER_SANITIZE_STRING);
    $firmaid = $_POST['firma_id'];

    $query = "INSERT INTO platnosc (kwota, data,firma_id,data_wpisu) VALUES(?,?,?,?)";
    $statement = $mysqli->prepare($query);
    $statement->bind_param('ssis',$kwota, $termin,$firmaid,date("YmdHis"));
    if($statement->execute()){
        print '<div class = "text-success"> Dodano rekord o id : ' .$statement->insert_id .' termin płatności '.$termin.' </div>'; 
    }else{
        die('Error : ('. $mysqli->errno .') '. $mysqli->error);
    }
    $statement->close();

}
                
?>



        
        <!--Dodawanie opłat-->
        <div class="row">
        <div class="container bg-light col-sm-6 opl-add">
            <h5>Dodaj opłatę</h5>
                <form method="post">
                <div class="form-group row">
                    <label for="dodaj_oplate_nazwa" class="col-sm-3 col-form-label">Nazwa</label>
                    <div class="col-sm-8">
                        <?php
                       
                           $sql1 = "SELECT * FROM nazwa_oplaty WHERE view = 1 ORDER BY firma ASC"; 
                            if($result = $mysqli->query($sql1)){
                                if($result->num_rows > 0){
                                    echo '<select class="custom-select" name="firma_id">';
                                        while($row = $result->fetch_array()){                                        
                                  echo '<option value="'.$row['firma_id'].'">'.$row['firma'].'</option>';          
                                }
                $result->free();
             
                } else{
                    echo "No records matching your query were found.";
                }
            } else{
                echo "ERROR: Could not able to execute $sql1. " . $mysqli->error;
            }
            ?>
         
                    
                    </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="dodaj_oplate_data" class="col-sm-3 col-form-label">Data</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="dodaj_oplate_data" name="termin" required="true">
                    </div>
                </div>
                <div class="form-group row ">
                    <label for="dodaj_oplate_kwota" class="col-sm-3 col-form-label">Kwota</label>
                    <div class="col-sm-8">
                        <input type="text" name="kwota" class="form-control" id="dodaj_oplate_kwota" required="true">
                    </div>
                </div>
                        <div class="form-group row">
                        <div class="col-sm">
                            <input type="submit" class="form-control btn btn-success" name="wyslij" value="Dodaj">
                        </div>
                        </div>
                    </form>
                </div>
         
            </div>
        <!--Koniec dodawanie opłat-->
        
<!--        Tabela z opłatami-->
<nav aria-label=...>
      <ul class="pagination">
          <li class="page-item" id="previous-page"><a class="page-link" href="javascript:void(0)" aria-label=Previous><span aria-hidden=true>&laquo;</span></a></li>
      </ul>
    </nav>
<select id="numberPerPage">
    <option value="10">10</option>
    <option value="25" selected>25</option>
    <option value="50">50</option>
    <option value="100">100</option>
    <option value="500">500</option>
</select>
    <?php
    $sql = "SELECT platnosc.id, platnosc.kwota, platnosc.data, nazwa_oplaty.firma, nazwa_oplaty.firma_id, nazwa_oplaty.nrkonta, platnosc.status "
            . "FROM platnosc, nazwa_oplaty WHERE nazwa_oplaty.firma_id = platnosc.firma_id "
            . "ORDER BY platnosc.data DESC"; 
    if($result = $mysqli->query($sql)){
        if($result->num_rows > 0){
            echo "<table class='table table-hover' id='tabelaOplat' >";
                echo "<thead>";
                    echo "<tr>";
                        echo "<th scope='col'>kwota</th>";
                        echo "<th scope='col'>termin</th>";
                        echo "<th scope='col'>firma</th>";
                        echo "<th scope='col'>numer konta</th>";
                        echo "<th scope='col'>status</th>";
                        echo "<th scope='col'></th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody id='daneTabeli'>";
            while($row = $result->fetch_array()){

                $bgkolor = "";
                $id =  $row["id"];
                $koszt = $row["kwota"];
                $koszt = str_replace(".",",",$koszt);
                $deadline = $row["data"];
                $deadlinestr = strtotime( $deadline );
                $firma = $row["firma"];
                $firma_id = $row["firma_id"];
                $status = $row["status"];
                $nr_konta = $row["nrkonta"];
             
                $pozostalo = round(($deadlinestr - $terazstr )/86400);

                    echo "<tr class='"; 
                    if (($status == 0) && ($pozostalo <=4))
                         {echo "table-danger";}
                    if ($status == 1)
                                {echo "table-success";}
                                echo"'>";
                        echo "<th scope='row' class='koszt'> <span>". $koszt . " zł</span></td>";
                        echo "<td>" . $deadline;
                           if ($status == 0){echo " (".$pozostalo." dni)";}
                           echo "</td>";
                        echo "<td>" . $firma. "</td>";
                        echo "<td>" . $nr_konta. "</td>";
                        echo "<td> "
//                        Formularz zmieniający status
                                . "<form method='post'>"
                                    . " <input id='check".$id."'class = 'form-control' name='status' value='1' type='checkbox' onchange='this.form.submit()' "; 
                                    if ( $status == 1){echo "checked";} echo ">"
                                    . "<input type='hidden' name='zmianaStatusu' value='".$id."'>"
                                            . "</form>"
                                            . "</td>";
                        echo "<td><button type='button' class='btn btn-danger' data-toggle='modal' data-target='#OplataModal".$id."'>Edytuj</button>";
//                        Modal Window start    
                        echo "<div class='modal fade' id='OplataModal".$id."' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                            <div class='modal-dialog' role='document'>
                                <form method='post'>    
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='exampleModalLongTitl'>Edytuj Opłatę</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                              <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                        <input name='Id' value = '$id' hidden>";
                                     if($result1 = $mysqli->query($sql1)){
                                         if($result1->num_rows > 0){
                                 echo '<label for="firma_id" class="col-sm-5 col-form-label">Nazwa</label>'
                                             . '<select class="custom-select" name="firma_id">';
                                    while($row1 = $result1->fetch_array()){                                        
                                          echo '<option value="'.$row1['firma_id'].'">'.$row1['firma'].'</option>';
                                          
                                         }
                                        $result1->free();

                                    } else{
                                        echo "No records matching your query were found.";
                                        }
                                    } else{ 
                                        echo "ERROR: Could not able to execute $sql1. " . $mysqli->error;
                                    }
                                    echo '<option value="'.$firma_id.'" selected>'.$firma.'</option>';
          echo "</select>";
                                 echo "  <label for='kwota".$id."' class='col-sm-5 col-form-label'>Kwota</label>
                                    <input id='kwota".$id."'class = 'form-control inputNazwa' name='kwotaUpdate' type='text' value='" . $koszt . "'>
                                    <label for='deadline".$id."' class='col-sm-5 col-form-label'>Termin płatności</label>                                        
                                    <input id='deadline".$id."'class = 'form-control inputKonto' name='dataUpdate' type='date' value='" . $deadline . "'>
                                    <div class='form-group row checkbox_row'> 
                                        <div class='col-sm-5'>
                                        <label for='check".$id."' class='col-sm-10 col-form-label'>Status płatności</label>
                                        </div>
                                        <div class='col-sm-3'>                                       
                                            <input id='check".$id."'class = 'form-control' name='statusUpdate' value='1' type='checkbox' "; 
                                        if ( $status == 1){echo "checked";} echo ">
                                        </div>
                                    </div>
                                        <div class='modal-footer'>
                                        <button type='submit' name ='edytujSubmit' class='btn btn-primary'>Zapisz zmiany</button>                                          
                                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Zamknij</button>
                                        <button type='submit' onclick='return confirm(`Czy napewno chcesz usunąć tę opłatę?`)' class='btn btn-danger' id='delete' name='delete'>Usuń</button>
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

    <nav aria-label=...>
      <ul class="pagination">
          <li class="page-item" id="previous-page"><a class="page-link" href="javascript:void(0)" aria-label=Previous><span aria-hidden=true>&laquo;</span></a></li>
      </ul>
    </nav>


                          

 

</div> 
</body>
</html>