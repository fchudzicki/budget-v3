<?php
require_once 'header.php';
/* 
 * Copyright Filip Chudzicki 2019
 */
        print'<div class = "container"> ';

            if(isset($_POST['edytujSubmit'])){
         
                $newFirmaID = filter_var($_POST['firmaId'], FILTER_SANITIZE_NUMBER_INT);
                $newNazwaFirmy = filter_var($_POST['nazwaFirmy'], FILTER_SANITIZE_STRING);
                $newnrKonta = filter_var($_POST['nrKonta'], FILTER_SANITIZE_STRING);         
               
                if(isset($_POST['view'])){
                 $newview = filter_var($_POST['view'], FILTER_SANITIZE_NUMBER_INT);
                }
                else {$newview = 0;}

                $query = "UPDATE nazwa_oplaty SET firma=?, nrkonta=?,view=? WHERE firma_id=?";
                $statement = $mysqli->prepare($query);
                $statement->bind_param('ssii', $newNazwaFirmy, $newnrKonta, $newview, $newFirmaID);

                if($statement->execute()){
                    print '<span class = "text-success">Zaktualizowano dane dla <b>  : ' .$newNazwaFirmy .'</b></span><br />'; 
                }else{
                    die('Error : ('. $mysqli->errno .') '. $mysqli->error);
                }
            }

            $sql = "SELECT * FROM nazwa_oplaty";
            if($result = $mysqli->query($sql)){
                if($result->num_rows > 0){
                    echo "<table class='table table-striped'>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th scope='col'>id</th>";
                                echo "<th scope='col'>firma</th>";
                                echo "<th scope='col'>nr konta</th>";
                                echo "<th scope='col'>widoczność w liście</th>";
                                echo "<th scope='col'></th>";
                            echo "</tr>";
                        echo "</thead>";
                    while($row = $result->fetch_array()){

                        $firmaID = $row['firma_id'];
                        $view =  $row['view'];
                        echo "<tr>";
                                echo "<th scope='row'>" . $firmaID . "</td>";
                                echo "<td>" . $row['firma'] . "</td>";
                                echo "<td>" . $row['nrkonta'] . "</td>";
                                echo "<td><input id='check".$firmaID."'class = 'form-control' name='view' type='checkbox' disabled ";
                                if ( $view == 1){echo "checked";} echo "></td>";

                            echo "<td><button type='button' class='btn btn-danger' data-toggle='modal' data-target='#WierzycielModal".$firmaID."'>Edytuj</button>";
                                echo "<div class='modal fade' id='WierzycielModal".$firmaID."' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>
                                <div class='modal-dialog' role='document'>
                                <form method='post'>    
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title' id='exampleModalLongTitl'>Edytuj Wierzyciela</h5>
                                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                                          <span aria-hidden='true'>&times;</span>
                                        </button>
                                    </div>
                                <div class='modal-body'>
                            <input name='firmaId' value = '$firmaID' hidden>
                            <input id='nazwa".$row['firma_id']."'class = 'form-control inputNazwa' name='nazwaFirmy' type='text' value='" . $row['firma'] . "'>
                            <input id='nrKonta".$row['firma_id']."'class = 'form-control inputKonto' name='nrKonta' type='text' value='" . $row['nrkonta'] . "'>
                            <input id='check".$firmaID."'class = 'form-control' name='view' value='1' type='checkbox' "; 
                                        if ( $view == 1){echo "checked";} echo ">
                            </div>
                            <div class='modal-footer'>
                              <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                              <button type='submit' name ='edytujSubmit' class='btn btn-primary'>Save changes</button>
                            </div>
                          </div>
                          </form>
                        </div>
                      </div>";

                            echo"</td>";
                        echo "</tr>";
                    }
                    echo "</table>";
                    // Free result set
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
        </div>
    </body>
</html>

    <!-- Button trigger modal -->


