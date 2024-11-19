<?php
include 'db_connection.php';

// Handle Create Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    // Check for duplicates before inserting
    $check_sql = "SELECT * FROM time_slot WHERE day_of_week = '$day_of_week' AND start_time = '$start_time' AND end_time = '$end_time'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo '<div class="error">Error: A time slot with the same day, start time, and end time already exists.</div>';
    } else {
        // Insert the new time slot
        $sql = "INSERT INTO time_slot (day_of_week, start_time, end_time) VALUES ('$day_of_week', '$start_time', '$end_time')";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="success">New time slot created successfully.</div>';  // Success message
        } else {
            echo '<div class="error">Error: ' . $conn->error . '</div>';  // Error message for insertion failure
        }
    }
}

// Handle Update Operation
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM time_slot WHERE time_slot_id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['time_slot_id'];
    $day_of_week = $_POST['day_of_week'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $sql = "UPDATE time_slot SET 
        day_of_week='$day_of_week', 
        start_time='$start_time', 
        end_time='$end_time'
        WHERE time_slot_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Time slot updated successfully.</div>';  // Success message
    } else {
        echo '<div class="error">Error updating record: ' . $conn->error . '</div>';  // Error message
    }
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM time_slot WHERE time_slot_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Time slot deleted successfully.</div>';  // Success message
    } else {
        echo '<div class="error">Error deleting record: ' . $conn->error . '</div>';  // Error message
    }
}

// Fetch all time slots for display
$sql = "SELECT * FROM time_slot";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Time Slot Management</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
     /* Shared styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f9fc; /* Light blue background to reflect Captain America */
    color: #333; /* Dark text for readability */
    margin: 0;
    padding: 0;
    display: flex;
}

h2, h3 {
    color: white; /* White headings for contrast */
    margin-bottom: 10px;
    text-align: center;
}

.error, .success {
    position: fixed;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    padding: 8px 15px;
    margin-bottom: 10px;
    border-radius: 5px;
    font-size: 14px; /* Smaller text size */
    z-index: 9999; /* Ensure it's on top of other content */
    width: auto;
    max-width: 80%; /* Limit the width of the message */
    text-align: center;
}

.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    animation: fadeOut 5s forwards;
}

.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
    animation: fadeOut 3s forwards;
}

@keyframes fadeOut {
    0% { opacity: 1; }
    99% { opacity: 1; }
    100% { opacity: 0; display: none; }
}

/* Buttons */
.submit-button {
    margin-left: 10px;
    background-color: #d91c34; /* Captain America red */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.back-button {
    display: flex;
    text-align: center; /* Center text within the button */
    background-color: #005fa1; /* Captain America blue */
    color: white;
    padding: 8px 12px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s;
    margin: 20px auto;
    margin-top: 4px;
    width: fit-content;
    max-width: 150px;
}

.back-button:hover, .submit-button:hover {
    background-color: #003366; /* Darker blue on hover */
}

/* Container */
.container {
    text-align: left;
    max-width: 100%;
    margin: 0 auto;
    padding: 20px;
    background-color: #005fa1; /* Captain America blue */
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
}

/* Table Styling */
table {
    max-width: 100%;
    margin-left: 0px;
    border-collapse: collapse;
    margin-bottom: 20px;
    background-color: #3c3c3c;
}

th, td {
    border: 1px solid #444;
    padding: 10px;
    text-align: left;
}

th {
    background-color: #2F4F4F;
    color: white;
}

tr:nth-child(even) {
    background-color: #4a4a4a;
}

tr:hover {
    background-color: #555;
}

/* Action links */
.action-link {
    color: #007bff; /* Blue for action links */
    text-decoration: none;
}

.action-link:hover {
    text-decoration: underline;
}

/* Instructor Form */
.instructor-form {
    background-color: #3c3c3c;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    margin-bottom: 20px;
    width: 600px;
    margin: auto;
}

/* Form Inputs */
.instructor-form input[type="text"], 
.instructor-form input[type="number"] {
    width: calc(100% - 22px);
    padding: 10px;
    border: 1px solid #444;
    border-radius: 5px;
    background-color: #555;
    color: #e0e0e0;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #e0e0e0;
}

input[type="text"], input[type="number"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #444;
    border-radius: 5px;
    background-color: #555;
    color: #e0e0e0;
}

input[type="submit"], input[type="button"] {
    background-color: #d91c34; /* Red (Captain America) */
    display: flex;
    align-items: center;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover, input[type="button"]:hover {
    background-color: #005fa1; /* Blue on hover */
}

/* Form Grouping */
.form-group {
    flex: 1;
    min-width: 200px;
    display: flex;
    flex-direction: column;
}

.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    margin-bottom: 15px;
}

.form-actions {
    text-align: left;
}

.form-group input,
.form-group select {
    padding: 8px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.form-group input[type="date"] {
    max-width: 250px;
}

select {
    background-color: #555;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 10px;
    width: 95%;
    font-size: 16px;
    transition: border-color 0.3s;
    color: white;
}

select:focus {
    border-color: #007BFF; /* Blue focus color */
    outline: none; /* Remove default outline */
}

select option {
    padding: 10px; /* Padding for options */
}

select:hover {
    border-color: #007BFF; /* Blue on hover */
}

/* Sidebar */
.sidebar {
    width: 250px;
    background-color: #003366; /* Dark blue background for sidebar */
    padding: 20px;
    height: 100vh;
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
    position: fixed;
}

.sidebar a {
    display: block;
    color: #e0e0e0;
    text-decoration: none;
    padding: 10px 15px;
    margin-bottom: 10px;
    background-color: #2F4F4F; /* Darker button background */
    border-radius: 5px;
    transition: background-color 0.3s;
}

.sidebar a:hover {
    background-color: #d91c34; /* Captain America Red on hover */
}

    </style>
</head>
<body>

        <div class="sidebar">
            <h2>University Management</h2>
            <a href="university.php">Dashboard</a>
            <a href="instructor.php">Instructor</a>
            <a href="department.php">Department</a>
            <a href="course.php">Course</a>
            <a href="classroom.php">Classroom</a>
            <a href="time_slot.php">Time Slot</a>
            <a href="student.php">Student</a>
        </div>


    <div class="container">
        <h2>Time Slot Management</h2>
        
        <!-- Create Form -->
        <h3>Add New Time Slot</h3>
        <form method="POST" class="time_slot-form">
            <input type="hidden" name="time_slot_id" value="<?php echo isset($row) ? $row['time_slot_id'] : ''; ?>">
            <label>Day of Week:</label>
                <select id="day_of_week" name="day_of_week" required>
                    <option value="Monday" <?php echo isset($row) && $row['day_of_week'] == 'Monday' ? 'selected' : ''; ?>>MONDAY</option>
                    <option value="Tuesday" <?php echo isset($row) && $row['day_of_week'] == 'Tuesday' ? 'selected' : ''; ?>>TUESDAY</option>
                    <option value="Wednesday" <?php echo isset($row) && $row['day_of_week'] == 'Wednesday' ? 'selected' : ''; ?>>WEDNESDAY</option>
                    <option value="Thursday" <?php echo isset($row) && $row['day_of_week'] == 'Thursday' ? 'selected' : ''; ?>>THURSDAY</option>
                    <option value="Friday" <?php echo isset($row) && $row['day_of_week'] == 'Friday' ? 'selected' : ''; ?>>FRIDAY</option>
                </select>

            <label>Start Time:</label> 
            <input type="time" id="start_time" name="start_time" required value="<?php echo isset($row) ? $row['start_time'] : ''; ?>">
            <label>End Time:</label> 
            <input type="time" id="end_time" name="end_time" required value="<?php echo isset($row) ? $row['end_time'] : ''; ?>"><br><br>
            <input type="submit" name="<?php echo isset($row) ? 'update' : 'create'; ?>" value="<?php echo isset($row) ? 'Update Time Slot' : 'Create Time Slot'; ?>">
            <input type="button" class="submit-button" value="Clear" onclick="clearForm()">
        </form>

        <!-- Read Table -->
        <h3>Time Slot List</h3>
        <table>
            <tr>
                <th>Day of Week</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['day_of_week']}</td>
                        <td>{$row['start_time']}</td>
                        <td>{$row['end_time']}</td>
                        <td>
                            <a href='time_slot.php?edit={$row['time_slot_id']}'>Edit</a> | 
                            <a href='time_slot.php?delete={$row['time_slot_id']}'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No time slots found</td></tr>";
            }
            ?>
        </table>
    </div>

    <script>
        function clearForm() {
            document.getElementById('day_of_week').value = '';
            document.getElementById('start_time').value = '';
            document.getElementById('end_time').value = '';
        }
    </script>
</body>

</html>

<?php
$conn->close();
?>
