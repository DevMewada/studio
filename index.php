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
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900&display=swap" rel="stylesheet">

</head>

<body>
    <div class="main">
        <div class="search">
            <!-- the search form  -->
            <form action="index.php" method="POST">
                <div class="search-bar">
                    <input type="search" name="query" placeholder="Title / Keyword">
                    <input type="number" name="year" min="2000" max="2050" step="1" placeholder="Year">
                </div>
                <button type="submit" name="submit">Search</button>
            </form>
        </div>
        <div class="project-section">
            <?php
            // the search query conditions
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

                    // the data view
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<div class='accordian'>";
                            echo "<div class='accordian-child'>";
                            $id = $row["pID"];
                            echo "<input type='checkbox' id='accordian-$id' class='accordion-toggle'>";
                            echo "<label for='accordian-$id'>" . htmlspecialchars($row["title"]) . "</label>";
                            echo "<div class='content'>";
                            echo "<div class='short-desc'>";
                            echo "<p>" . htmlspecialchars($row["sDesc"]) . "</p>";
                            echo "</div>";
                            echo "<div class='description'>";
                            echo "<div class='detailed-desc'>";
                            echo "<p id='deatil-desc-head'>Detailed Description</p>";
                            // Display detail_desc records as a list
                            if (!empty($row['pDesc'])) {
                                $descriptions = explode('<br>', $row['pDesc']);
                                echo "<ul>";
                                foreach ($descriptions as $desc) {
                                    echo "<li>" . htmlspecialchars_decode($desc) . "</li>";
                                }
                                echo "</ul>";
                            }
                            echo "</div>";
                            echo "<div class='tech-desc'>";
                            echo "<p id='tech-desc-head'>Technical Description</p>";
                            echo "<p>" . htmlspecialchars($row["techDesc"]) . "</p>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='other-details'>";
                            echo "<div class='other-details-child'>";
                            echo "<p id='odc-head'>Application Date</p>";
                            echo "<p>" . htmlspecialchars($row["appSdate"]) . "</p>";
                            echo "<p>To</p>";
                            echo "<p>" . htmlspecialchars($row["appEdate"]) . "</p>";
                            echo "</div>";
                            echo "<div class='other-details-child'>";
                            echo "<p id='odc-head'>Project Date</p>";
                            echo "<p>" . htmlspecialchars($row["pSdate"]) . "</p>";
                            echo "<p>To</p>";
                            echo "<p>" . htmlspecialchars($row["pEdate"]) . "</p>";
                            echo "</div>";
                            echo "<div class='other-details-child'>";
                            echo "<p id='odc-head'>Students Required</p>";
                            echo "<p>" . htmlspecialchars($row["studentReq"]) . "</p>";
                            echo "<p id='odc-head'>Internship Type</p>";
                            echo "<p>" . htmlspecialchars($row["iType"]) . "</p>";
                            echo "</div>";
                            echo "<div class='other-details-child'>";
                            echo "<p id='odc-head'>Extra</p>";
                            echo "<p>" . htmlspecialchars($row["extra"]) . "</p>";
                            echo "</div>";
                            echo "</div>";
                            echo "<div class='edit-option'>";
                            if ($row["status"] == 1) $status = "Active";
                            else $status = "Inactive";
                            $statusColor = ($status == 'Active') ? 'green' : 'red';
                            echo "<p style='color: $statusColor;'>" . $status . "</p>";
                            //    the edit button modal view
                            echo "<button class='editBtn' data-id='$id' data-title='" . htmlspecialchars($row["title"]) . "' data-studentReq='" . htmlspecialchars($row["studentReq"]) . "' data-sDesc='" . htmlspecialchars($row["sDesc"]) . "' data-techDesc='" . htmlspecialchars($row["techDesc"]) . "' data-appSdate='" . htmlspecialchars($row["appSdate"]) . "' data-appEdate='" . htmlspecialchars($row["appEdate"]) . "' data-pSdate='" . htmlspecialchars($row["pSdate"]) . "' data-pEdate='" . htmlspecialchars($row["pEdate"]) . "' data-status='" . htmlspecialchars($row["status"]) . "' data-iType='" . htmlspecialchars($row["iType"]) . "' data-extra='" . htmlspecialchars($row["extra"])  . "' data-pDesc='" . htmlspecialchars($row["pDesc"]) . "'> Edit </button>";
                            echo "</div>";
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


    <!-- Modal Structure -->
    <div id="myModal" class="modal">
        
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Edit Form</h2><br>
        <form id="editForm" action="update_project.php" method="POST">
            <label for="pID">Project ID:</label>
            <input type="text" id="pID" name="pID" readonly><br><br>

                <label for="title">Title:</label>
                <input type="text" id="title" name="title"><br><br>

                <label for="studentReq">Student Requirement:</label>
                <input type="text" id="studentReq" name="studentReq"><br><br>

                <label for="sDesc">Short Description:</label>
                <textarea id="sDesc" name="sDesc"></textarea><br><br>

                <label for="techDesc">Technical Description:</label>
                <textarea id="techDesc" name="techDesc"></textarea><br><br>

                <label for="appSdate">Application Start Date:</label>
                <input type="date" id="appSdate" name="appSdate"><br><br>

                <label for="appEdate">Application End Date:</label>
                <input type="date" id="appEdate" name="appEdate"><br><br>

                <label for="pSdate">Project Start Date:</label>
                <input type="date" id="pSdate" name="pSdate"><br><br>

                <label for="pEdate">Project End Date:</label>
                <input type="date" id="pEdate" name="pEdate"><br><br>

                <label for="status">Status:</label>
                <input type="text" id="status" name="status"><br><br>

                <label for="iType">Industry Type:</label>
                <input type="text" id="iType" name="iType"><br><br>

                <label for="extra">Extra:</label>
                <textarea id="extra" name="extra"></textarea><br><br>

                <button type="button" id="addDesc">Add Description</button>
            <div id="descContainer">
                <!-- Detailed Descriptions will be dynamically added here -->
            </div>
                <input type="submit" value="Save Changes">
            </form>
        </div>
    </div>


    <script>
            var modal = document.getElementById("myModal");
            var btns = document.getElementsByClassName("editBtn");
            var span = document.getElementsByClassName("close")[0];
            var addDescBtn = document.getElementById('addDesc');

            for (var i = 0; i < btns.length; i++) {
                btns[i].onclick = function() {
                    modal.style.display = "block";
                    document.getElementById('pID').value = this.getAttribute('data-id');
                    document.getElementById('title').value = this.getAttribute('data-title');
                    document.getElementById('studentReq').value = this.getAttribute('data-studentReq');
                    document.getElementById('sDesc').value = this.getAttribute('data-sDesc');
                    document.getElementById('techDesc').value = this.getAttribute('data-techDesc');
                    document.getElementById('appSdate').value = this.getAttribute('data-appSdate');
                    document.getElementById('appEdate').value = this.getAttribute('data-appEdate');
                    document.getElementById('pSdate').value = this.getAttribute('data-pSdate');
                    document.getElementById('pEdate').value = this.getAttribute('data-pEdate');
                    document.getElementById('status').value = this.getAttribute('data-status');
                    document.getElementById('iType').value = this.getAttribute('data-iType');
                    document.getElementById('extra').value = this.getAttribute('data-extra');

                    var descContainer = document.getElementById('descContainer');
                    descContainer.innerHTML = '';

                    var descs = this.getAttribute('data-pDesc').split('<br>');
                    descs.forEach((desc, index) => {
                        addDescriptionField(desc, index);
                    });
                }
            }

            function addDescriptionField(desc = '', index = 0) {
                var descContainer = document.getElementById('descContainer');
                var descField = document.createElement('div');
                descField.className = 'desc-field';
                descField.innerHTML = `
                    <label for="descOrder-${index}">Description Order:</label>
                    <input type="number" id="descOrder-${index}" name="descOrder[]" value="${index + 1}" min="1"><br><br>
                    <label for="pDesc-${index}">Detailed Description:</label>
                    <textarea id="pDesc-${index}" name="pDesc[]">${desc}</textarea><br><br>
                    <button type="button" class="removeDesc">Remove Description</button>
                    <br><br>
                `;
                descContainer.appendChild(descField);

                // Add event listener to remove button
                descField.querySelector('.removeDesc').onclick = function() {
                    descContainer.removeChild(descField);
                };
            }

            addDescBtn.onclick = function() {
                var descContainer = document.getElementById('descContainer');
                var index = descContainer.children.length;
                addDescriptionField('', index);
            };

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            document.getElementById('editForm').onsubmit = function(event) {
                event.preventDefault();
                var formData = new FormData(this);

                fetch('update_project.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        modal.style.display = "none";
                        location.reload();
                    })
                    .catch(error => console.error('Error:', error));
            }

            document.addEventListener('DOMContentLoaded', (event) => {
                var accordions = document.querySelectorAll('.accordion-toggle');

                accordions.forEach((accordion) => {
                    accordion.addEventListener('change', (e) => {
                        if (accordion.checked) {
                            accordions.forEach((otherAccordion) => {
                                if (otherAccordion !== accordion) {
                                    otherAccordion.checked = false;
                                }
                            });
                        }
                    });
                });
            });

    </script>


</body>

</html>