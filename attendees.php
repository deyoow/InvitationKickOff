<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendees Kick Off</title>
    <link rel="icon" type="image/png" href="assets/img/logo.png">
    <style>
        body {
            display: flex;
            justify-content: space-around;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 0px;
            background-color: #f0f0f0;
            background-size: cover;
            background-repeat: no-repeat;
        }

        video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            object-fit: cover;
            z-index: -1;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 20px;
            padding: 20px;
            width: 90%;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            position: relative;
            top: 20px;
            overflow-x: auto; /* Add this line for horizontal scrolling */
            overflow-y: auto; /* Add this line for horizontal scrolling */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            overflow-x: auto;
            margin-top: 40px;
            font-size: 14px;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: black;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .search-container {
            position: absolute;
            top: 5px; /* Adjust the top position */
            right: 20px; /* Adjust the right position */
            display: flex;
            margin-top: 8px;
        }

        #searchInput {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px 0 0 4px;
            width: 60%; /* Adjust the width as needed */
        }

        #searchButton {
            padding: 8px 12px;
            background-color: #3498db;
            color: #fff;
            border: 1px solid #2980b9;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            width: 35%; /* Adjust the width as needed */
        }

        .table-container {
            overflow-y: auto;
            max-height: 400px;
        }

        @media only screen and (max-width: 600px) {
            .container {
                width: 95%;
                top: 10px;
            }

            table {
                font-size: 12px;
            }
        }
    </style>
</head>

<body>
    <video autoplay loop muted>
        <source src="assets/video/einvite2.mp4" type="video/mp4">
    </video>

    <div class="container">
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search">
            <!-- Search button -->
            <button id="searchButton">Search</button>
        </div>
        <?php
            // Replace with your database credentials
            $servername = "localhost";
            $username = "root";
            $password = "";
            $database = "invitation";

            // Create connection
            $conn = mysqli_connect($servername, $username, $password, $database);

            // Check connection
            if (!$conn) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $sql = "SELECT * FROM attendees";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<table style='font-family: Tahoma, Geneva, Verdana, sans-serif;'>";
                echo "<tr><th>Name</th><th>Position</th><th>Attendees</th></tr>";
            
                $totalYes = 0;
                $totalNo = 0;
            
                // output data of each rows
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>".$row["name"]."</td><td>".$row["position"]."</td><td>".$row["attend_status"]."</td></tr>";
            
                    // Counting attendees
                    if ($row["attend_status"] == "Yes") {
                        $totalYes++;
                    } elseif ($row["attend_status"] == "No") {
                        $totalNo++;
                    }
                }
            
                echo "</table>";
            
                // Display total counts on one line with bold formatting and centered
                echo "<p style='text-align: center; font-family: \"Segoe UI\", Tahoma, Geneva, Verdana, sans-serif; font-size: 18px;'><strong>Total of Attendees (Yes): $totalYes <br> Total of Attendees (No): $totalNo</strong></p>";

            } else {
                echo "0 results";
            }
            
            $conn->close();
            ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var searchInput = document.getElementById('searchInput');
            var table = document.querySelector('table');

            searchInput.addEventListener('input', function () {
                var searchTerm = searchInput.value.toLowerCase();

                for (var i = 1; i < table.rows.length; i++) {
                    var row = table.rows[i];
                    var found = false;

                    for (var j = 0; j < row.cells.length; j++) {
                        var cellText = row.cells[j].textContent.toLowerCase();

                        if (cellText.includes(searchTerm)) {
                            found = true;
                            break;
                        }
                    }

                    row.style.display = found ? '' : 'none';
                }
            });
        });
    </script>


</body>
</html>
