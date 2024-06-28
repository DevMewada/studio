<?php
include("database.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pID = $_POST['pID'];
    $title = $_POST['title'];
    $studentReq = $_POST['studentReq'];
    $sDesc = $_POST['sDesc'];
    $techDesc = $_POST['techDesc'];
    $appSdate = $_POST['appSdate'];
    $appEdate = $_POST['appEdate'];
    $pSdate = $_POST['pSdate'];
    $pEdate = $_POST['pEdate'];
    $status = $_POST['status'];
    $iType = $_POST['iType'];
    $extra = $_POST['extra'];
    $descOrder = $_POST['descOrder'];
    $pDesc = $_POST['pDesc'];

    $updateProject = "UPDATE projects SET 
        title = '$title', 
        studentReq = '$studentReq', 
        sDesc = '$sDesc', 
        techDesc = '$techDesc', 
        appSdate = '$appSdate', 
        appEdate = '$appEdate', 
        pSdate = '$pSdate', 
        pEdate = '$pEdate', 
        status = '$status', 
        iType = '$iType', 
        extra = '$extra' 
        WHERE pID = '$pID'";

    if (mysqli_query($connection, $updateProject)) {
        $deleteDesc = "DELETE FROM detail_desc WHERE pID = '$pID'";
        mysqli_query($connection, $deleteDesc);

        foreach ($pDesc as $index => $desc) {
            $order = $descOrder[$index];
            $insertDesc = "INSERT INTO detail_desc (pID, pDesc, descOrder) VALUES ('$pID', '$desc', '$order')";
            mysqli_query($connection, $insertDesc);
        }

        echo "Project updated successfully";
    } else {
        echo "Error updating project: " . mysqli_error($connection);
    }
}
?>
