<?php
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pID = $_POST["pID"];
    $title = $_POST["title"];
    $studentReq = $_POST["studentReq"];
    $sDesc = $_POST["sDesc"];
    $techDesc = $_POST["techDesc"];
    $appSdate = $_POST["appSdate"];
    $appEdate = $_POST["appEdate"];
    $pSdate = $_POST["pSdate"];
    $pEdate = $_POST["pEdate"];
    $status = $_POST["status"];
    $iType = $_POST["iType"];
    $extra = $_POST["extra"];
    $descOrders = $_POST["descOrder"];
    $pDescs = $_POST["pDesc"];

    // Update the project in the database
    $sql = "UPDATE projects 
            SET title='$title', studentReq='$studentReq', sDesc='$sDesc', techDesc='$techDesc', appSdate='$appSdate', appEdate='$appEdate', pSdate='$pSdate', pEdate='$pEdate', status='$status', iType='$iType', extra='$extra' 
            WHERE pID='$pID'";
    
    if (mysqli_query($connection, $sql)) {
        // Update detail_desc table
        foreach ($descOrders as $index => $descOrder) {
            $pDesc = $pDescs[$index];
            $updateDesc = "UPDATE detail_desc SET pDesc='$pDesc' WHERE pID='$pID' AND descOrder='$descOrder'";
            mysqli_query($connection, $updateDesc);
        }
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . mysqli_error($connection);
    }
}
mysqli_close($connection);
?>
