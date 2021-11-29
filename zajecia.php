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
$miesiac = date("m");
$teraz = date("Ymd");
$terazstr = strtotime( $teraz );
$wspwart = 0.7745 * 41.84; //Współczynnik do wyliczenia wartości zajęcia
if (isset($_GET['newMonth']))
{
    $rokMiesiac = filter_var($_GET['newMonth'], FILTER_SANITIZE_NUMBER_INT);
    $rokMiesiacTab = explode("-", $rokMiesiac);
    $rok = $rokMiesiacTab[0];
    $miesiac = $rokMiesiacTab[1];

}

$sql = "SELECT SUM(`ilosc_zajec`) AS TenSezonSum
            FROM `zajecia_artystyczne` 
            WHERE `data` BETWEEN '2018-09-01' AND '2019-08-31'";
    if($result = $mysqli->query($sql)){
    if($result->num_rows > 0){
    while($row = $result->fetch_array()){
   $TenSezonSum = $row["TenSezonSum"];

    }
    
               $result->free();
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
    }

   
   
//Edycja rekordu z modal window/////////////////////
if (isset($_POST['edytujSubmit']))
{
    $id = filter_var($_POST['Id'], FILTER_SANITIZE_NUMBER_INT);
    $data = filter_var($_POST['dataUpdate'], FILTER_SANITIZE_STRING);
    $ilosc = filter_var($_POST['iloscUpdate'], FILTER_SANITIZE_NUMBER_INT);
    $wart = filter_var($_POST['wartUpdate'], FILTER_SANITIZE_STRING);
 
   $query = "UPDATE zajecia_artystyczne SET data=?, ilosc_zajec=?, wartosc=? WHERE id=?";
   $statement = $mysqli->prepare($query);
   $statement->bind_param('sisi', $data, $ilosc, $wart, $id);
    if($statement->execute()){
        print '<div class = "text-success"> Zmieniono rekord o id : <b>' .$id.'</b> data zajeć <b>'.$data.'</b> ilość <b>'.$ilosc.'</b></div>'; 
   }else{
       die('Error : ('. $mysqli->errno .') '. $mysqli->error);
   }
}
//Usuwanie rekordu//////////////////////////////////
if (isset($_POST['delete']))
{
    $id = filter_var($_POST['Id'], FILTER_SANITIZE_NUMBER_INT);
    $data = filter_var($_POST['dataUpdate'], FILTER_SANITIZE_STRING);
    $ilosc = filter_var($_POST['iloscUpdate'], FILTER_SANITIZE_NUMBER_INT);
    
 
   $query = "DELETE FROM `zajecia_artystyczne` WHERE id=?";
   $statement = $mysqli->prepare($query);
   $statement->bind_param('i', $id);
    if($statement->execute()){
        print '<div class = "text-danger"> Usunięto rekord o id : ' .$id.' data zajeć '.$data.' ilość '.$ilosc.'</div>'; 
   }else{
       die('Error : ('. $mysqli->errno .') '. $mysqli->error);
   }
}

//            Dodawanie rekordu do tabeli MYSQL //////////////////////////
    if(isset($_POST['dodaj_zajecia'])){
    $data = filter_var($_POST['termin_zajec'], FILTER_SANITIZE_STRING);
    $ilosc_zajec = filter_var($_POST['ilosc_zajec'], FILTER_SANITIZE_NUMBER_INT);
    if($TenSezonSum>180){
    $wartosc = $wspwart*$ilosc_zajec * 6;
    }else{
         $wartosc = $wspwart*$ilosc_zajec;
    }
    
    
    $query = "INSERT INTO zajecia_artystyczne (data, ilosc_zajec, wartosc) VALUES(?,?,?)";
    $statement = $mysqli->prepare($query);
    $statement->bind_param('sis',$data, $ilosc_zajec, $wartosc);
    if($statement->execute()){
        print '<div class = "text-success"> Dodano rekord o id : ' .$statement->insert_id .' data zajec '.$data.' </div>'; 
    }else{
        die('Error : ('. $mysqli->errno .') '. $mysqli->error);
    }
    $statement->close();

}
                
?>
        <!--Dodawanie zajęcia-->
        <section>
            <div class="row">
                <div class="container bg-light col-sm-6" id="wyd-add">
                    <h5>Dodaj zajęcie</h5>
                    <form method="post">
                        <div class="form-group row">
                            <label for="termin_zajec" class="col-sm-4 col-form-label">Data</label>
                            <div class="col-sm-6">
                                <input type="date" class="form-control" id="termin_zajec" name="termin_zajec" required="true">
                            </div>
                        </div>
                        <div class="form-group row ">
                            <label for="ilosc_zajec" class="col-sm-4 col-form-label">Ilość zajęć</label>
                            <div class="col-sm-6">
                                <input type="text" name="ilosc_zajec" class="form-control" id="ilosc_zajec" required="true">
                            </div>
                        </div>
                        <div class="form-group row">
                        <div class="col-sm">
                            <input type="submit" class="form-control btn btn-success" name="dodaj_zajecia" value="Dodaj">
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!--Koniec dodawanie zajęcia-->
        
<!--        Tabela z zajęciami-->
<div class="row">
   
    
    <form class="col-3"  id="formYear" method="get" >
        <div class="form-group row">
       <label for="choseMonth" class="col-form-label col">Miesiąc</label>
       <input class="form-control col" type="month" name="newMonth" onchange="this.form.submit()" id="choseMonth"> 
        </div>
    </form>

</div>
    <?php
    $sql = "SELECT * FROM zajecia_artystyczne
        WHERE MONTH(data) = '$miesiac'        
        AND YEAR( data ) ='$rok' 
        ORDER BY data  DESC"; 
    if($result = $mysqli->query($sql)){
        if($result->num_rows > 0){
            echo "<table class='table table-hover table-striped' id='tabelaZajec' >";
                echo "<thead>";
                    echo "<tr>";
                        echo "<th scope='col'>ID</th>";
                        echo "<th scope='col'>Data</th>";
                        echo "<th scope='col'>Ilość zajęć</th>";
                        echo "<th scope='col'>Wartość</th>";
                        echo "<th scope='col'></th>";
                    echo "</tr>";
                echo "</thead>";
                echo "<tbody id='daneTabeli'>";
            while($row = $result->fetch_array()){

                $bgkolor = "";
                $id =  $row["id"];
                $ilosc = $row["ilosc_zajec"];
                $data = $row["data"];
                $wart = round($row["wartosc"], 2);

                    echo "<tr>";
                    echo "<td>" . $id . "</td>";
                    echo "<td>" . $data . "</td>";
                    echo "<td> <span>". $ilosc . " </span></td>";
                    echo "<td> <span>". $wart . " zł</span></td>";
                       
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
                                            <input name='Id' value = '$id' hidden>        
                                            <div class='form-group row'>
                                                <label for='data".$id."' class='col col-form-label'>Data</label>                                        
                                                <input id='data".$id."'class = 'col-6 form-control' name='dataUpdate' type='date' value='" . $data . "'>
                                            </div>                                        
                                            <div class='form-group row'>
                                                <label for='ilosc".$id."' class='col col-form-label'>Ilość zajęć</label>
                                                <input id='ilosc".$id."'class = 'col-6 form-control inputIlosc' name='iloscUpdate' type='text' value='" . $ilosc . "'>
                                            </div>
                                            <div class='form-group row'>
                                                <label for='wart".$id."' class='col col-form-label'>Wartość</label>
                                                <input id='wart".$id."'class = 'col-6 form-control inputWart' name='wartUpdate' type='text' value='" . $wart . "'>
                                            </div>
                                        <div class='modal-footer'>
                                        <button type='submit' name ='edytujSubmit' class='btn btn-primary'>Zapisz zmiany</button>
                                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Zamknij</button>
                                        <button type='submit' onclick='return confirm(`czy napewno chcesz usunąć to zajęcie`)' class='btn btn-danger' id='delete' name='delete'>Usuń</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                          </div>
                        </td>
                    </tr>";
                     }
                echo "</tbody>";
                     
            $result->free();
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
    }
//    Suma zajęć w danym miesiącu/////////////////////////
     $sqlsum = "Select (select SUM(`ilosc_zajec`) from `zajecia_artystyczne` WHERE MONTH(data) = '$miesiac'AND YEAR( data ) ='$rok') as SumaZajec, "
             . "(select SUM(`wartosc`) from `zajecia_artystyczne` WHERE MONTH(data) = '$miesiac'AND YEAR( data ) ='$rok') as SumaWart";
        if($resultsum = $mysqli->query($sqlsum)){
        if($resultsum->num_rows > 0){
             while($row = $resultsum->fetch_array()){
        $suma =  $row["SumaZajec"];
        $kwota_dodatku = round( $row["SumaWart"],2);
           
             echo"  <tfoot>
                    <tr>
                        <th colspan ='2'>suma</th>
                        <th>".$suma."</th>
                        <th>".$kwota_dodatku." zł</th>
                    </tr>
                    </tfoot>";
             }
    
//Koniec tabeli z opłatami
    
             
            $resultsum->free();
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
    }
    


    ?>

            </table>           
            <hr>
    <table class='table table-hover table-striped'>
        <thead>
            <tr>
                <th scope="col">sezon</th>
                <th scope="col">ilość zajęć dodatkowych</th>
                <th scope="col">w sumie kwota dodatków</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
<?php
for ($i=2018; $i <= $rok;$i++ ){
    $sql = "Select (select SUM(`ilosc_zajec`) from `zajecia_artystyczne` WHERE `data` BETWEEN '".$i."-09-01' AND '".($i+1)."-08-31') as SumaZajec, "
             . "(select SUM(`wartosc`) from `zajecia_artystyczne` WHERE `data` BETWEEN '".$i."-09-01' AND '".($i+1)."-08-31') as SumaWart";
          
    if($result = $mysqli->query($sql)){
    if($result->num_rows > 0){
    while($row = $result->fetch_array()){
   $sezonSum = $row["SumaZajec"];
   $sezonWart = round($row["SumaWart"],2);
  
                echo'<tr>
                    <td>'.$i.'-'.($i+1).'</td>
                <td>'.$sezonSum.'</td>
                <td>'.$sezonWart.' zł</td>    
                </tr>';
    }
                   
            $result->free();
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
    }
}
    // Close connection
    $mysqli->close();
    ?>
                </tbody>
            </table>
            </div>
    </body>
</html>