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




// Ilość typów wydatku
$number_of_exptype = exptypenumber();




  
    if(isset($_POST['save_change'])){
 function update_exp_type(){

    
 
    $id = filter_var($_POST['hid_id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($_POST['expense_type_name'], FILTER_SANITIZE_STRING);
    $exp_cat = filter_var($_POST['exp_cat_id'], FILTER_SANITIZE_STRING);
  
   $mysqli = dbconnect();
       
   $query = "UPDATE exptypename SET  name=?, exp_cat_id=?  WHERE exptype_id=$id";
   $statement = $mysqli->prepare($query);
   $statement->bind_param('ss', $name, $exp_cat);
    if($statement->execute()){
        print '<div class = "text-success"> Zmieniono rekord o id : ' .$id.' i nazwie '.$name.' </div>'; 
   }else{
       die('Error : ('. $mysqli->errno .') '. $mysqli->error);
   }
   $statement->close();
}


update_exp_type();
    }

 

//            Dodawanie rekordu do tabeli

    if(isset($_POST['add_type'])){
    
   
    $expcat_id = filter_var($_POST['expcat_id'], FILTER_SANITIZE_STRING);
    $expense_type_name = filter_var($_POST['expense_type_name'], FILTER_SANITIZE_STRING);
   
 
    
    $expenseid = ins_new_epense_type($expense_type_name,$expcat_id);


  

}
                
?>

        <!-----------------------Dodawanie typu wydatku FORM------------------------------------>
        
        <div class="row">
        <div class="container bg-light col-sm-9" id="wyd-add">
            <h5 class="text-center mb-3">Dodaj typ wydatku </h5>
                <form method="post">
    


                <div class="form-group row  ">
                    <!-- <label for="exp_type_id" class="col-sm-2 col-form-label">ID</label>
                    <div class="col-sm-2">
                        <input type="text" name="exp_type_id" class="form-control" id="exp_type_id" >
                    </div> -->
               
                
                    <label for="dod_wyd_data" class="col-sm-2 col-form-label">Nazwa</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="dod_wyd_data" name="expense_type_name" required="true">
                    </div>
                
               
<?php
   
                $sql = "SELECT * FROM expcategory ORDER BY expcat_id ASC"; 
                            if($result = $mysqli->query($sql)){
                                if($result->num_rows > 0){
                                    echo '<select class="custom-select col-sm-4 " name="expcat_id">';
                                        while($row = $result->fetch_array()){                                        
                                  echo '<option value="'.$row['expcat_id'].'">'.$row['expcat_name'].'</option>';          
                                }
                $result->free();
             
                } else{
                    echo "No records matching your query were found.";
                }
            } else{
                echo "ERROR: Could not able to execute $sql1. " . $mysqli->error;
            }
?>

             
                
                <div class="form-group row">
                     <div class="col-sm">
                    <input type="submit" class="m-4 form-control btn btn-success" name="add_type" value="Dodaj">
                    </div>
                </div>
                </form>
            </div>
         
        </div>
        <!--Koniec dodawanie typów wydatku-->
        
<!--        Tabela z Typami wydatków-->

    <?php
/******************************************Tabel query************************************ */
    $sql = "SELECT * FROM exptypename 
         ORDER BY exptype_id ASC";

    if($result = $mysqli->query($sql)){
        if($result->num_rows > 0){
            ?>
            <div class="container">
            <table class='table table-hover' id='exptype_tabel' >
                <thead>
                    <tr>
                        <th scope='col'>Id</th>
                        <th scope='col'>Nazwa</th>
                       <th scope='col'>Kategoria</th>
                     <th scope='col'></th>
                   </tr>
              </thead>
             <tbody id='daneTabeli'>
            <?php
            while($row = $result->fetch_array()){
              
                $exptype_id = $row["exptype_id"];
                $typename = $row["name"];
                $exp_cat_id = $row["exp_cat_id"];

               
                
                ?>
              
             
 
              <form method="post">
                    <tr>
                    <td><input name='hid_id' value = '<?php echo $exptype_id;?>' hidden> 
                        <input type="text" class="form-control"  name="exptype_id" value="<?php echo $exptype_id;?>" required="true" disabled></td>
                    <td> <input type="text" class="form-control"  name="expense_type_name" value="<?php echo $typename;?>"required="true"></td>
                    <td> 
                        <?php                               
                                 $sql1 = "SELECT * FROM expcategory ORDER BY expcat_id ASC"; 
                                     if($result1 = $mysqli->query($sql1)){
                                         if($result1->num_rows > 0){

                                 echo '<select class="custom-select" name="exp_cat_id">';
                                    while($row1 = $result1->fetch_array()){                                        
                                          echo '<option value="'.$row1['expcat_id'].'">'.$row1['expcat_name'].'</option>';
                                          
                                         }
                                        $result1->free();

                                    } else{
                                        echo "No records matching your query were found.";
                                        }
                                    } else{ 
                                        echo "ERROR: Could not able to execute $sql1. " . $mysqli->error;
                                    }       
                                    echo '<option value="'.$exp_cat_id.'" selected>'. expcatename($exp_cat_id).'</option>';?>
                                    </select>  
                    
                </td>                   
                                           
                        <td> <input type="submit" class="btn btn-success form-control" id="save_change<?php echo $exptype_id;?>" name="save_change" onclick='return confirm(`Czy chcesz zapisać zmiany?`)' value="Zapisz zmiany">
                        </td>
                            

                      
                    </tr>
              </form>
                    <?php } ?>
                </tbody>
            </table>
           <?php
            $result->free();
        } else{
            echo "No records matching your query were found.";
        }
    } else{
        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
    }
//Koniec tabeli z wydatkami
    
    
    
    // Close connection
    $mysqli->close();
    ?>
            </div>

                        



</div> 

</body>
</html>