<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <link rel="stylesheet" href="index.css">
</head>

<body>
    <form action="index.php" method="POST">
        <div>
            <input type="search" name="query">
            <input type="number" name="year" min="2000" max="2050" step="1" placeholder="Enter Year">
            <button type="submit" name="submit">Search</button>
        </div>
    </form>
    <div class="main">
        <div class="projects-section">
            <div class="per-project">
                <div class="title"></div>
                <div class="content"></div>
            </div>
        </div>
    </div>
</body>

</html>

<?php
include("database.php");

if (isset($_POST["submit"])) {
    $query = $_POST["query"];
    $year = $_POST["year"];
    if (empty($query) && empty($year)) {
        echo '<script>alert("At least one field should be filled.");</script>';
    } else {
        if (!empty($query) && empty($year)) {
            $projects = "SELECT * FROM projects WHERE title LIKE '%$query%'";
        }
        if (empty($query) && !empty($year)) {
            $projects = "SELECT * FROM projects WHERE YEAR(pSdate) = $year";
        }
        if (!empty($query) && !empty($year)) {
            $projects = "SELECT * FROM projects WHERE title LIKE '%$query%' AND YEAR(pSdate) = $year ";
        }

        $result = mysqli_query($connection, $projects);

        if (mysqli_num_rows($result) > 0) {
            echo "<table border>";
            echo "<tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Students Req</th>
                    <th>Short Desc</th>
                    <th>Tech Desc</th>
                    <th>App start</th>
                    <th>App end</th>
                    <th>Proj start</th>
                    <th>Proj end</th>
                    <th>Status</th>
                    <th>Type</th>
                    <th>Extra</th>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr><td>" . $row["pID"] . "</td>
                    <td>" . $row["title"] . "</td>
                    <td>" . $row["studentReq"] . "</td>
                    <td>" . $row["sDesc"] . "</td>
                    <td>" . $row["techDesc"] . "</td>
                    <td>" . $row["appSdate"] . "</td>
                    <td>" . $row["appEdate"] . "</td>
                    <td>" . $row["pSdate"] . "</td>
                    <td>" . $row["pEdate"] . "</td>
                    <td>" . $row["status"] . "</td>
                    <td>" . $row["iType"] . "</td>
                    <td>" . $row["extra"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "No results found";
        }
    }
}

?>


