<?php
include_once '../dbcon/dbconnect.php';
echo "USERS<br/>";
echo "===============<br/>";
$id=1;
$str="CALL get_user(@uid)";
$stmt = $con->prepare('SET @uid := ?');
$stmt->bind_param('i', $id);
$stmt->execute();
if($result=$con->query($str,MYSQLI_USE_RESULT)){
    while($row=$result->fetch_assoc()){
        echo $row['fname']."<br/>";
        echo $row['mname']."<br/>";
        echo $row['lname']."<br/>";
        echo $row['email']."<br/>";
    }
}
clearStoredResults();

echo "<br/>PURCHASES<br/>";
echo "===============<br/>";
$str1="CALL get_purchase(@pid)";
$stmt1 = $con->prepare('SET @pid := ?');
$stmt1->bind_param('i', $id);
$stmt1->execute();
if($result1=$con->query($str1,MYSQLI_USE_RESULT)){
    while($row1=$result1->fetch_array()){
        echo $row1['user']."<br/>";
        echo $row1['item']."<br/>";
        echo $row1['price']."<br/>";
    }
}

$con->close();


function clearStoredResults(){
    global $con;
    
    do {
         if ($res = $con->store_result()) {
           $res->free();
         }
    } while ($con->more_results() && $con->next_result());        
    
}
?>