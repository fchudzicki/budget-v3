<!--
/* 
 * Copyright Filip Chudzicki 2019
 */
-->
<?php
include 'header.php'; ?>
<div class="container">
  
<?php
$rok = date("Y");
$teraz = date("Ymd");
$terazstr = strtotime( $teraz );
if (isset($_GET['newYear']))
{
    $rok = filter_var($_GET['newYear'], FILTER_SANITIZE_NUMBER_INT);
}

//Edycja rekordu z modal window
if (isset($_POST['edytujSubmit']))
{
    $id = filter_var($_POST['Id'], FILTER_SANITIZE_NUMBER_INT);
    $data = filter_var($_POST['dataUpdate'], FILTER_SANITIZE_STRING);
    $kwota = filter_var($_POST['kwotaUpdate'], FILTER_SANITIZE_STRING);
    $kwota = str_replace(",",".",$kwota);
    $typ_id = $_POST['typ_id'];
    $szczegoly = filter_var($_POST['szczegUpdate'], FILTER_SANITIZE_STRING);
    $sposob_id = $_POST['sposob_id'];

    
    
    
   $query = "UPDATE wydatki SET typ_id=?, szczegoly=?, data=?, kwota=?, sposob_id=? WHERE wyd_id=?";
   $statement = $mysqli->prepare($query);
   $statement->bind_param('isssii', $typ_id, $szczegoly, $data, $kwota, $sposob_id, $id);
    if($statement->execute()){
        print '<div class = "text-success"> Zmieniono rekord o id : ' .$id.' data wydatku '.$data.' kwota '.$kwota.' zł</div>'; 
   }else{
       die('Error : ('. $mysqli->errno .') '. $mysqli->error);
   }
}
 
 
/*
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
 
 */
//            Dodawanie rekordu do tabeli
    if(isset($_POST['dodaj_wydatek'])){
    $typid = $_POST['typ_wydatku'];
    $szczegoly = filter_var($_POST['szczegoly'], FILTER_SANITIZE_STRING);
    $kwota = filter_var($_POST['kwota'], FILTER_SANITIZE_STRING);
    $kwota = str_replace(",",".",$kwota);
    $termin = filter_var($_POST['termin'], FILTER_SANITIZE_STRING);
    $sposobid = $_POST['sposob_platnosci'];

    $query = "INSERT INTO wydatki (typ_id, szczegoly, data, kwota, czas_wpisu, sposob_id) VALUES(?,?,?,?,?,?)";
    $statement = $mysqli->prepare($query);
    $statement->bind_param('issssi',$typid,$szczegoly,$termin,$kwota,date("YmdHis"),$sposobid);
    if($statement->execute()){
        print '<div class = "text-success"> Dodano rekord o id : ' .$statement->insert_id .' data wydatku '.$termin.' </div>'; 
    }else{
        die('Error : ('. $mysqli->errno .') '. $mysqli->error);
    }
    $statement->close();

}
                
?>
        <!--Dodawanie wydatku-->
        <div class="row">
        <div class="container bg-light col-sm-6" id="wyd-add">
            <h5>Dodaj wydatek</h5>
                <form method="post">
                              <div class="form-group row">
                    <label for="sposob_platnosci" class="col-sm-4 col-form-label">Sposób płatności</label>
                    <div class="col-sm-6">
                        <?php
                       
                           $sql1 = "SELECT * FROM sposob_platnosci ORDER BY sposob ASC"; 
                            if($result = $mysqli->query($sql1)){
                                if($result->num_rows > 0){
                                    echo '<select class="custom-select" name="sposob_platnosci">';
                                        while($row = $result->fetch_array()){                                        
                                  echo '<option value="'.$row['sposob_id'].'">'.$row['sposob'].'</option>';          
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
                    <label for="typ_wydatku" class="col-sm-4 col-form-label">Typ Wydatku</label>
                    <div class="col-sm-6">
                        <?php
                           $sql2 = "SELECT * FROM typ_wydatku ORDER BY typ ASC"; 
                            if($result = $mysqli->query($sql2)){
                                if($result->num_rows > 0){
                                    echo '<select class="custom-select" name="typ_wydatku">';
                                        while($row = $result->fetch_array()){                                        
                                  echo '<option value="'.$row['typ_id'].'">'.$row['typ'].'</option>';          
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
                <div class="form-group row ">
                    <label for="dod_wyd_szczegoly" class="col-sm-4 col-form-label">Szczegóły</label>
                    <div class="col-sm-6">
                        <input type="text" name="szczegoly" class="form-control" id="dod_wyd_szczegoly" required="true">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="dod_wyd_data" class="col-sm-4 col-form-label">Data</label>
                    <div class="col-sm-6">
                        <input type="date" class="form-control" id="dod_wyd_data" name="termin" required="true">
                    </div>
                </div>
                <div class="form-group row ">
                    <label for="dod_wyd_kwota" class="col-sm-4 col-form-label">Kwota</label>
                    <div class="col-sm-6">
                        <input type="text" name="kwota" class="form-control" id="dod_wyd_kwota" required="true">
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
    $sql = "SELECT * FROM wydatki, typ_wydatku, sposob_platnosci 
        WHERE wydatki.typ_id = typ_wydatku.typ_id
        AND wydatki.sposob_id = sposob_platnosci.sposob_id
        AND YEAR( wydatki.data ) ='$rok' 
        ORDER BY wydatki.data  DESC"; 
    if($result = $mysqli->query($sql)){
        if($result->num_rows > 0){
            echo "<table class='table table-hover' id='tabelaOplat' >";
                echo "<thead>";
                    echo "<tr>";
                        echo "<th scope='col'>Data</th>";
                        echo "<th scope='col'>Kwota</th>";
                        echo "<th scope='col'>Płatność</th>";
                        echo "<th scope='col'>Typ Wydatku</th>";
                        echo "<th scope='col'>Szczegóły</th>";
                        echo "<th scope='col'></th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody id='daneTabeli'>";
            while($row = $result->fetch_array()){

                $bgkolor = "";
                $id =  $row["wyd_id"];
                $koszt = $row["kwota"];
                $koszt = str_replace(".",",",$koszt);
                $data = $row["data"];
                $szczeg_wyd = $row["szczegoly"];
                $typ_wydatku = $row["typ"];
                $typ_id_wyd = $row["typ_id"];
                $sposob_wyd = $row["sposob"];
                $sposob_wyd_id= $row["sposob_id"];
             
 

                    echo "<tr>";
                    echo "<td>" . $data . "</td>";
                    echo "<th scope='row'> <span>". $koszt . " zł</span></td>";
                       
                        echo "<td>" . $sposob_wyd. "</td>";
                        echo "<td>" . $typ_wydatku. "</td>";
                        echo "<td>" .$szczeg_wyd."</td>";
                        echo "<td><button type='button' class='btn btn-danger' data-toggle='modal' data-target='#wydModal".$id."'>Edytuj</button>";
//                        Modal Window start    
                        echo "<div class='modal fade' id='wydModal".$id."' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                            <div class='modal-dialog' role='document'>
                                <form method='post'>    
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title' id='exampleModalLongTitl'>Edytuj Wydatek</h5>
                                            <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                              <span aria-hidden='true'>&times;</span>
                                            </button>
                                        </div>
                                        <div class='modal-body'>
                                        <input name='Id' value = '$id' hidden>";
                                     if($result1 = $mysqli->query($sql1)){
                                         if($result1->num_rows > 0){
                                 echo '<div class="form-group row">'
                                             . '<label for="sposob_id" class="col col-form-label">Sposób płatności</label>'
                                             . '<select class="custom-select col-6" name="sposob_id">';
                                    while($row1 = $result1->fetch_array()){                                        
                                          echo '<option value="'.$row1['sposob_id'].'">'.$row1['sposob'].'</option>';
                                          
                                         }
                                        $result1->free();

                                    } else{
                                        echo "No records matching your query were found.";
                                        }
                                    } else{ 
                                        echo "ERROR: Could not able to execute $sql1. " . $mysqli->error;
                                    }
                                    echo '<option value="'.$sposob_wyd_id.'" selected>'.$sposob_wyd.'</option>';
                                echo "</select></div>";
                                
                                 if($result2 = $mysqli->query($sql2)){
                                                               if($result2->num_rows > 0){
                                 echo '<div class="form-group row">'
                                    . '<label for="typ_id" class="col col-form-label">Typ wydatku</label>'
                                    . '<select class="col-6 custom-select" name="typ_id">';
                                    while($row2 = $result2->fetch_array()){                                        
                                          echo '<option value="'.$row2['typ_id'].'">'.$row2['typ'].'</option>';
                                          
                                         }
                                        $result1->free();

                                    } else{
                                        echo "No records matching your query were found.";
                                        }
                                    } else{ 
                                        echo "ERROR: Could not able to execute $sql1. " . $mysqli->error;
                                    }
                                    echo '<option value="'.$typ_id_wyd.'" selected>'.$typ_wydatku.'</option>';
                                     echo "</select></div>";
                                     
                                 echo "
                                    <div class='form-group row'>
                                        <label for='kwota".$id."' class='col col-form-label'>Kwota</label>
                                        <input id='kwota".$id."'class = 'col-6 form-control inputNazwa' name='kwotaUpdate' type='text' value='" . $koszt . "'>
                                    </div>
                                    <div class='form-group row'>
                                        <label for='data".$id."' class='col col-form-label'>Termin płatności</label>                                        
                                        <input id='data".$id."'class = 'col-6 form-control inputKonto' name='dataUpdate' type='date' value='" . $data . "'>
                                    </div>
                                    <label for='szczeg".$id."' class='col-sm-5 col-form-label'>Szczegóły</label>                                        
                                    <input id='szczeg".$id."'class = 'form-control inputSzczeg' name='szczegUpdate' type='text' value='" . $szczeg_wyd . "'>
                                
                                        <div class='modal-footer'>
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


                          

 

</div> 
</body>
</html>