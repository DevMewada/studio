    <?php
    include("database.php");
    ?>

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
<!--     <div class="main">
        <div class="projects-section">
            <div class="per-project">
                <div class="title"></div>
                <div class="content"></div>
            </div>
        </div>
    </div> -->
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
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<div class='project'>";
                                echo "<h3>" . ($row["title"]) . "</h3>";
                                echo "<p>ID: " . htmlspecialchars($row["pID"]) . "</p>";
                                echo "<p>Students Required: " . htmlspecialchars($row["studentReq"]) . "</p>";
                                echo "<p>Short Description: " . htmlspecialchars($row["sDesc"]) . "</p>";
                                echo "<p>Detailed Description: </p>";
                                // Display detail_desc records as a list
                                if (!empty($row['pDesc'])) {
                                    $descriptions = explode('<br>', $row['pDesc']);
                                    echo "<ul>";
                                    foreach ($descriptions as $desc) {
                                        echo "<li>" . htmlspecialchars_decode($desc) . "</li>";
                                    }
                                    echo "</ul>";
                                }
                                echo "<p>Technical Description: " . htmlspecialchars($row["techDesc"]) . "</p>";
                                echo "<p>Application Start Date: " . htmlspecialchars($row["appSdate"]) . "</p>";
                                echo "<p>Application End Date: " . htmlspecialchars($row["appEdate"]) . "</p>";
                                echo "<p>Project Start Date: " . htmlspecialchars($row["pSdate"]) . "</p>";
                                echo "<p>Project End Date: " . htmlspecialchars($row["pEdate"]) . "</p>";
                                if ($row["status"] == 1) {
                                    // echo "<p>Active</p>";
                                    $status = "Active";
                                } else {
                                    // echo "<p>Deactive</p>";
                                    $status = "Inactive";
                                }
                                echo "<p>Status: " . $status . "</p>";
                                echo "<p>Type: " . htmlspecialchars($row["iType"]) . "</p>";
                                echo "<p>Extra: " . htmlspecialchars($row["extra"]) . "</p>";
                                echo "</div>";
                            }
                            echo "</table>";
                        } else {
                            echo "No results found";
                        }
                    }
                }

?>

</html>