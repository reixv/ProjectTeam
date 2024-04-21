<?php
// Start session and check if the user is logged in
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$servername = "localhost"; 
$username = "root";
$password = ""; 
$dbname = "login"; 

// Establish a new connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $conn->real_escape_string($_POST["firstname"]);
    $lastname = $conn->real_escape_string($_POST["lastname"]);
    $email = $conn->real_escape_string($_POST["email"]);
    $phone = $conn->real_escape_string($_POST["phone"]);
    $age = $conn->real_escape_string($_POST["age"]);
    $guests = $conn->real_escape_string($_POST["guests"]);
    $guestNumber = isset($_POST["guestNumber"]) ? $conn->real_escape_string($_POST["guestNumber"]) : 0;
    $to_activity = isset($_POST["activity"]) ? $conn->real_escape_string($_POST["activity"]) : '';
    $date = $conn->real_escape_string($_POST["date"]);

    date_default_timezone_set('Asia/Riyadh');
    $today = date("Y-m-d");
    $date = date('Y-m-d', strtotime($date)); // Format the date from the form

    if ($date >= $today) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO tickets (firstname, lastname, email, phone, age, guests, guestNumber, to_activity, date_of_visit) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssisssi", $firstname, $lastname, $email, $phone, $age, $guests, $guestNumber, $to_activity, $date);
        $stmt->execute();
        echo "<script>alert('Your ticket has been booked successfully.');</script>";
        $stmt->close();
    } else {
        echo "<script>alert('Please select a future date for your visit.');</script>";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ticket</title>
    <link rel="stylesheet" href="book_ticket.css">
</head>
<body>
    <div class="ticket-form">
        <h2>Book Ticket
            <br>
        Let’s create your entrance Ticket!</h2>
        <form action="book_ticket.php" method="post">
            <div class="form-group">
                <label for="firstname">First Name:</label>
                <input type="text" id="firstname" name="firstname" required>
            </div>
            <div class="form-group">
                <label for="lastname">Last Name:</label>
                <input type="text" id="lastname" name="lastname" required>
            </div>
            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="phone"> Phone Number:</label>
                <div class="input-with-flag">
                    <input type="tel" id="phone" name="phone" pattern="\+966\d{9}" title="+966 followed by 9 digits" required>
                </div>
            </div>
            <div class="form-group">
                <label for="age">Age:</label>
                <input type="number" id="age" name="age" required>
            </div>
            <div class="form-group">
                <fieldset>
                    <legend>Are you bringing any guests?</legend>
                    <label><input type="radio" name="guests" value="yes"> Yes</label>
                    <label><input type="radio" name="guests" value="no" checked> No</label>
                </fieldset>
            </div>
            <div class="form-group" id="guestsNumber" style="display: none;">
                <label for="guestNumber">Number of Guests:</label>
                <select id="guestNumber" name="guestNumber">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            <div class="form-group">
                <label for="to">choose  Activity:</label>
                <select id="to" name="to" required>
                    <option value="maraya - 100SAR">Maraya Alula - 100SAR</option>
                    <option value="wonder - 100SAR">Wonder Garden Riyadh -100SAR</option>
                    <option value="Ithra - 100SAR">Ithra Alkhober -100SAR</option>
                    <option value="bayada - 100SAR">Bayada Island Jeddah -100SAR</option>
                    <option value="buraydah - 100SAR">Dates City Buraydah -100SAR</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date of Visit:</label>
                <input type="date" id="date" name="date" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Book a Ticket">
            </div>
            <div class="form-group">
                <a href="VistSaudi.php" class="return-home">Back to Home Page</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const guestsYesRadio = document.querySelector('input[name="guests"][value="yes"]');
            const guestsNoRadio = document.querySelector('input[name="guests"][value="no"]');
            const guestsNumberDiv = document.getElementById("guestsNumber");

            function toggleGuestNumberDisplay() {
                if (guestsYesRadio.checked) {
                    guestsNumberDiv.style.display = "block";
                } else {
                    guestsNumberDiv.style.display = "none";
                }
            }

            guestsYesRadio.addEventListener("change", toggleGuestNumberDisplay);
            guestsNoRadio.addEventListener("change", toggleGuestNumberDisplay);

            const phoneInput = document.getElementById("phone");
            const form = document.querySelector("form");

            form.addEventListener("submit", function(event) {
                const phoneValue = phoneInput.value;
                if (!phoneValue.startsWith("+966") || phoneValue.length !== 13) {
                    alert("Please enter a valid Saudi phone number starting with +966.");
                    event.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
