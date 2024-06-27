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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>

<body>
    <div class="main">
        <div class="search">
            <form action="index.php" method="POST">
                <div class="search-bar">
                    <input type="search" name="query" placeholder="Title / Keyword">
                    <input type="number" name="year" min="2000" max="2050" step="1" placeholder="Year">
                </div>
                <button type="submit" name="submit">Search</button>
            </form>
        </div>
        <div class="project-section ">
            <?php
            if (isset($_POST["submit"])) {
                $query = $_POST["query"];
                $year = $_POST["year"];
                if (empty($query) && empty($year)) {
                    echo '<script>alert("At least one field should be filled.");</script>';
                } else {
                    if (!empty($query) && empty($year)) {
                        $projects = "SELECT p.*, GROUP_CONCAT(d.pDesc SEPARATOR '<br>') AS pDesc 
                            FROM projects p 
                            LEFT JOIN detail_desc d ON p.pID = d.pID 
                            WHERE p.title LIKE '%$query%' 
                            GROUP BY p.pID";
                    }
                    if (empty($query) && !empty($year)) {
                        $projects = "SELECT p.*, GROUP_CONCAT(d.pDesc SEPARATOR '<br>') AS pDesc 
                            FROM projects p 
                            LEFT JOIN detail_desc d ON p.pID = d.pID 
                            WHERE YEAR(p.pSdate) = $year 
                            GROUP BY p.pID";
                    }
                    if (!empty($query) && !empty($year)) {
                        $projects = "SELECT p.*, GROUP_CONCAT(d.pDesc SEPARATOR '<br>') AS pDesc 
                            FROM projects p 
                            LEFT JOIN detail_desc d ON p.pID = d.pID 
                            WHERE p.title LIKE '%$query%' AND YEAR(p.pSdate) = $year 
                            GROUP BY p.pID";
                    }

                    $result = mysqli_query($connection, $projects);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<div class='accordian'>";
                            echo "<div class='accordian-child'>";
                            $id = $row["pID"];
                            echo "<input type='checkbox' id='accordian-<?php echo(`$id`); ?>'>";
                            echo "<label for='accordian-<?php echo(`$id`); ?>'>" . ($row["title"]) . "</label>";
                            echo "<div class='content'>";
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
                            if ($row["status"] == 1) $status = "Active";
                            else $status = "Inactive";
                            echo "<p>Status: " . $status . "</p>";
                            echo "<p>Type: " . htmlspecialchars($row["iType"]) . "</p>";
                            echo "<p>Extra: " . htmlspecialchars($row["extra"]) . "</p>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    } else {
                        echo "No results found";
                    }
                }
            }
            ?>
        </div>
    </div>
    <script>
        document.querySelectorAll('.accordian input[type="checkbox"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (checkbox.checked) {
                    document.querySelectorAll('.accordian input[type="checkbox"]').forEach(function(otherCheckbox) {
                        if (otherCheckbox !== checkbox) {
                            otherCheckbox.checked = false;
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>