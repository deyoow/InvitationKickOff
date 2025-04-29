<?php
session_start();

// Database configuration
// Database connection parameters
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

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $willAttend = mysqli_real_escape_string($conn, $_POST['willAttend']); // Updated to 'willAttend'

    // Insert data into the database using prepared statements
    $stmt = $conn->prepare("INSERT INTO attendees (name, position, attend_status) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $position, $willAttend);


    if ($stmt->execute()) {
        echo "<script>alert('Welcome to 2024 CS Conference & Kick Off Celebration!');</script>";

    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Close connection
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You're Invited!</title>
    
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

        .background-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

        .background-video-2 {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }


        #playButton {
            /* Existing styles */
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 10px;
            cursor: pointer;
            border-radius: 15px;
            font-size: 15px;
            font-weight: bold;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        #playButton img {
            width: 30px;
            height: 30px;
            border-radius: 15px;
            transition: transform 0.3s ease-in-out; /* Add a smooth transition effect for zoom-in */
        }

        #playButton:hover img {
            transform: scale(1.2); /* Zoom in effect on hover */
        }


        /* #playButton:hover {
            background-color: rgba(0, 0, 0, 0.3);
            box-shadow: 0 0 25px rgba(255, 255, 153, 0.9);
        } */



        #skipButton {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.7);
            color: #fff;
            border: none;
            cursor: pointer;
            border-radius: 10px;
        }

        .overlay-video {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            opacity: 1; /* Initially set to 1 for visibility */
        }

        form {
            display: none;
            flex-direction: column;
            align-items: center;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.7);
            padding: 20px;
            border-radius: 20px;
            color: #fff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            z-index: 1;
            font-weight: bold;
            margin-top: 100px;
            opacity: 0; /* Initially set to 0 to hide */
        }



        form label {
            display: block;
            margin-bottom: 5px;
            font-size: 15px;
        }

        form input {
            width: 200px;
            padding: 8px;
            margin-bottom: 10px;
            font-size: 15px;
            display: inline-block; /* Add this to make the input fields appear on the same line as the labels */
        }

        form select {
            width: 214px; /* Adjust the width as needed to match the text inputs */
            padding: 8px;
            margin-bottom: 10px;
            font-size: 15px;
        }

        form button[type="submit"] {
            background-color: gold;
            color: #000;
            border: none;
            cursor: pointer;
            border-radius: 10px;
            font-size: 20px;
            font-weight: bold;
            padding: 10px 20px;
            transition: background-color 0.3s; /* Add a smooth transition effect */
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }

        form button[type="submit"]:hover {
            background-color: darkorange; /* Change the background color on hover */
        }

        .attendance-button {
            background-color: gold;
            color: #000;
            border: none;
            cursor: pointer;
            border-radius: 10px;
            font-size: 17px;
            font-weight: bold;
            padding: 10px 35px;
            transition: background-color 0.3s, color 0.3s;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-left: 2px;
        }

        .attendance-button:hover,
        .attendance-button.selected {
            background-color: darkred;
            color: #fff;
        }

        @media screen and (max-width: 768px) {

        form {
            margin-top: 125px;
        }
    }
        

    </style>
</head>

<body>

    <video class="background-video" autoplay muted playsinline>
        <source src="assets/video/einvite.mp4" type="video/mp4">
    </video>

    <video class="background-video-2" autoplay muted playsinline loop style="display: none;">
        <source src="assets/video/einvite2.mp4" type="video/mp4">
    </video>

    <video class="overlay-video" id="overlayVideo" style="display: none;" playsinline>
        <source src="assets/video/invitation.mp4" type="video/mp4">
    </video>

    <img id="playButton" src="assets/img/logofinal.png" alt="Play" style="width: 390px; height: 500px;" onclick="playVideo()">
    <!-- <button id="skipButton">Skip Invitation</button> -->

    <!-- New form section -->
    <form id="attendanceForm" action="" method="post">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>

        <label for="position">Position:</label>
        <input type="text" id="position" name="position" required>

        <!-- <label for="company">Company:</label>
        <input type="text" id="company" name="company" required> -->

        <div>
        <label style='position: relative; left: 40px;'>Will you attend?</label>

        <button type="button" class="attendance-button" id="yesButton" onclick="setAttendance('Yes')">Yes</button>
        <button type="button" class="attendance-button" id="noButton" onclick="setAttendance('No')">No</button>
        <input type="hidden" id="willAttend" name="willAttend" required>

        </div>
<br>

        <button type="submit" name="submit" onclick="return showConfirmation();">SUBMIT</button>
    </form>

<script>
    const overlayVideo = document.getElementById('overlayVideo');
    const playButton = document.getElementById('playButton');
    const skipButton = document.getElementById('skipButton');
    const attendanceForm = document.getElementById('attendanceForm');
    const yesButton = document.getElementById('yesButton');
    const noButton = document.getElementById('noButton');
    const willAttendInput = document.getElementById('willAttend');
    const backgroundMusic = new Audio('assets/audio/bgmusic.mp3');
    let isPageVisible = true;

    // Check for Page Visibility API support
    if ('hidden' in document) {
        document.addEventListener('visibilitychange', handleVisibilityChange);

        // Initial check for visibility
        if (document.hidden) {
            isPageVisible = false;
            pauseBackgroundMusic();
        }
    }

    function handleVisibilityChange() {
        if (document.hidden) {
            isPageVisible = false;
            pauseBackgroundMusic();
        } else {
            isPageVisible = true;
            playBackgroundMusic();
        }
    }

    function playBackgroundMusic() {
        if (isPageVisible) {
            backgroundMusic.play();
        }
    }

    function pauseBackgroundMusic() {
        backgroundMusic.pause();
    }


    playButton.addEventListener('click', () => {
    playButton.style.display = 'none';
    overlayVideo.style.display = 'block';
    overlayVideo.play().then(() => {
        // Show the form when the video ends
        overlayVideo.addEventListener('ended', () => {
            overlayVideo.style.opacity = '0'; // Fade out the overlay video
            attendanceForm.style.opacity = '1'; // Fade in the form
            attendanceForm.style.display = 'flex';
            backgroundMusic.play(); // Play background music
            playSecondBackgroundVideo(); // Trigger the second background video
            hideRSVPText(); // Hide the RSVP text
        });

        // Show text 5 seconds before the video ends
        // setTimeout(() => {
        //     showRSVPText();
        // }, (overlayVideo.duration - 4.8) * 1000);
    });
});

// Function to show "Scroll down for RSVP" text
// function showRSVPText() {
//     const rsvpText = document.createElement('div');
//     rsvpText.innerText = 'Scroll down for RSVP';
//     rsvpText.id = 'rsvpText';
//     rsvpText.style.position = 'absolute';
//     rsvpText.style.top = '550px';
//     rsvpText.style.left = '50%';
//     rsvpText.style.transform = 'translateX(-50%)';
//     rsvpText.style.color = '#fff';
//     rsvpText.style.fontWeight = 'bold';
//     rsvpText.style.fontSize = '19px';
//     rsvpText.style.
//     rsvpText.style.zIndex = '2';
//     overlayVideo.parentNode.appendChild(rsvpText); // Append to the parent of overlayVideo
// }

// Function to hide the RSVP text
// function hideRSVPText() {
//     const rsvpText = document.getElementById('rsvpText');
//     if (rsvpText) {
//         rsvpText.style.display = 'none';
//     }
// }

function playSecondBackgroundVideo() {
    const backgroundVideo2 = document.querySelector('.background-video-2');
    backgroundVideo2.style.display = 'block';
    backgroundVideo2.play();
}


    skipButton.addEventListener('click', () => {
        playButton.style.display = 'none';
        overlayVideo.pause();
        overlayVideo.style.opacity = '0'; // Fade out the video
        attendanceForm.style.opacity = '1'; // Fade in the form
        attendanceForm.style.display = 'flex';
        backgroundMusic.play(); // Play background music
    });

yesButton.addEventListener('click', () => {
    setAttendance('Yes');
});

noButton.addEventListener('click', () => {
    setAttendance('No');
});

// Event listener for form submission
attendanceForm.addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    // Check if all required fields are filled out
    if (!isFormComplete()) {
        alert("Please fill out all the required fields.");
        return false;
    }

    // Fetch form data
    const formData = new FormData(attendanceForm);

    // Send form data to the server using fetch
    fetch('', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            console.log(data); // You can handle the response as needed
            showConfirmation(); // Show confirmation after form submission
        })
        .catch(error => {
            console.error('Error:', error);
        });
});

// Function to set attendance and update button styles
function setAttendance(attendance) {
    willAttendInput.value = attendance;

    // Remove the 'selected' class from all buttons
    document.querySelectorAll('.attendance-button').forEach(button => button.classList.remove('selected'));

    // Add the 'selected' class to the clicked button
    const clickedButton = document.getElementById(`${attendance.toLowerCase()}Button`);
    clickedButton.classList.add('selected');
}


// Function to check if all required fields are filled out
function isFormComplete() {
    const name = document.getElementById('name').value;
    const position = document.getElementById('position').value;
    const company = document.getElementById('company').value;
    const willAttend = document.getElementById('willAttend').value;

    // Check if any of the required fields are empty
    if (name === '' || position === '' || company === '' || willAttend === '') {
        return false;
    }

    return true;
}

// Function to show confirmation dialog
function showConfirmation() {
    // Check if the form is complete
    if (!isFormComplete()) {
        alert("Please fill out all the required fields.");
        return false;
    }

    const attendanceStatus = willAttendInput.value;

    // Check if attendance status is set
    if (attendanceStatus !== "Yes" && attendanceStatus !== "No") {
        alert("Please select whether you will attend or not.");
        return false;
    }

    // Show confirmation dialog only if the form is complete
    if (confirm("Are you sure all the information is correct?")) {
        alert("Welcome to OG Glitz & Glam Christmas Gala!");
        // window.location.href = 'invitation';
        return true;
    } else {
        return false;
    }
}


</script>




</body>

</html>
