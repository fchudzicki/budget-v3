<?php
/////////////////////////////////////////////////////
////            starting mysqli                 /////
/////////////////////////////////////////////////////

 function dbconnect(){

 
    /* Attempt to connect to MySQL database */
    // $mysqli = new mysqli('localhost', 'chudziccy_1','QAZ123wsx', 'chudziccy_1');
    $mysqli = new mysqli('localhost', 'root','', 'chudziccy_1');
     $mysqli->set_charset("utf8");
    // Check connection
    if($mysqli === false){
        die("ERROR: Could not connect. " . $mysqli->connect_error);
    }
    
    return $mysqli;
    
    }

function insExpenseType($value,$expcat,$expensid){
    $mysqli = dbconnect();

    $value = filter_var($value, FILTER_SANITIZE_STRING);
    $value = str_replace(",",".",$value);

    $query = "INSERT INTO exptype (expenseid, typenameid, cost) VALUES(?,?,?)";
    $statement = $mysqli->prepare($query);
    $statement->bind_param('iis',$expensid,$expcat,$value);
     if($statement->execute()){
           }else{
         die('Error : ('. $mysqli->errno .') '. $mysqli->error);
  }
$statement->close();
   

}

function exptypename($id){
    $mysqli = dbconnect();

    $sqlexptypename = "SELECT * FROM exptypename";
  
if($result = $mysqli->query($sqlexptypename)){
        if($result->num_rows > 0){

           $result -> data_seek($id-1);
           $row = $result->fetch_row();
           return $row[1];

        }
    }
}

function insExpense($userid,$expensedate,$description,$sum){

    $mysqli = dbconnect();

$query = "INSERT INTO expenses (userid, expdate, expdescription, expsum) VALUES(?,?,?,?)";
$statement = $mysqli->prepare($query);
$statement->bind_param('isss',$userid,$expensedate,$description,$sum);


if($statement->execute()){
    $expenseid = $statement->insert_id;
    print '<div class = "text-success"> Dodano rekord o id : ' . $expenseid .' data wydatku '.$expensedate.' </div>'; 
}else{
    die('Error : ('. $mysqli->errno .') '. $mysqli->error);
}


$statement->close();

return $expenseid;
}


?>