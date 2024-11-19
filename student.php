<?php
include 'db_connection.php';

// Fetch departments for dropdown
$departments_sql = "SELECT department_id, department_name FROM department";
$departments_result = $conn->query($departments_sql);

// Handle Create Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $department_id = $_POST['department_id']; // Use department_id from dropdown
    $identification_number = $_POST['identification_number'];
    $total_credit = $_POST['total_credit'];  // Get the total_credit value
    $street_number = $_POST['street_number'];
    $street_name = $_POST['street_name'];
    $apt_number = $_POST['apt_number'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];

    // Check for duplicates before inserting
    $check_sql = "SELECT * FROM student WHERE identification_number = '$identification_number'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo '<div class="error">Error: A student with the same identification number already exists.</div>';
    } else {
        // Insert the new student including the required fields
        $sql = "INSERT INTO student (first_name, last_name, date_of_birth, department_id, identification_number, total_credit, street_number, street_name, apt_number, city, state, postal_code) 
                VALUES ('$first_name', '$last_name', '$date_of_birth', '$department_id', '$identification_number', '$total_credit', '$street_number', '$street_name', '$apt_number', '$city', '$state', '$postal_code')";

        if ($conn->query($sql) === TRUE) {
            echo '<div class="success">New student created successfully.</div>';
        } else {
            echo '<div class="error">Error: ' . $conn->error . '</div>';
        }
    }
}

// Handle Update Operation
if (isset($_GET['edit'])) {
    $identification_number = $_GET['edit'];
    $sql = "SELECT * FROM student WHERE identification_number = '$identification_number'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $date_of_birth = $_POST['date_of_birth'];
    $department_id = $_POST['department_id']; // Use department_id
    $identification_number = $_POST['identification_number'];
    $total_credit = $_POST['total_credit'];  // Get the total_credit value
    $street_number = $_POST['street_number'];
    $street_name = $_POST['street_name'];
    $apt_number = $_POST['apt_number'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postal_code = $_POST['postal_code'];

    $sql = "UPDATE student SET 
        identification_number = '$identification_number',
        first_name='$first_name', 
        last_name='$last_name', 
        date_of_birth='$date_of_birth', 
        department_id='$department_id', 
        total_credit='$total_credit', 
        street_number='$street_number', 
        street_name='$street_name', 
        apt_number='$apt_number', 
        city='$city', 
        state='$state', 
        postal_code='$postal_code'
        WHERE identification_number = '$identification_number'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Student updated successfully.</div>';
    } else {
        echo '<div class="error">Error updating record: ' . $conn->error . '</div>';
    }
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $identification_number = $_GET['delete'];
    $sql = "DELETE FROM student WHERE identification_number = '$identification_number'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Student deleted successfully.</div>';
    } else {
        echo '<div class="error">Error deleting record: ' . $conn->error . '</div>';
    }
}

    // Fetch all students for display
    $sql = "SELECT s.*, d.department_name 
            FROM student s 
            LEFT JOIN department d ON s.department_id = d.department_id";
    $result = $conn->query($sql);
?>



<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
      /* Error and Success Message Styles */
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

/* General Body Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f9fc; /* Light blue background */
    color: #333; /* Dark text color */
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
}

/* Headings */
h2, h3 {
    color: white; /* White for headings */
    text-align: center;
}

/* Container Styling */
.container {
    max-width: 900px;
    margin: auto;
    color: white;
    padding: 20px;
    background-color: #005fa1; /* Captain America blue */
    border-radius: 8px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
    text-align: left;
}

/* Table Styles */
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

th, td {
    border: 1px solid #444;
    padding: 10px;
    text-align: center;
}

th {
    background-color: #2F4F4F; /* Dark gray for table headers */
    color: white;
}

tr:nth-child(even) {
    background-color: #4a4a4a; /* Even rows with a dark background */
}

tr:hover {
    background-color: #555; /* Darker hover effect */
}

/* Input and Submit Button Styling */
input[type="text"], input[type="date"], input[type="email"], input[type="submit"] {
    width: 100%;
    padding: 8px;
    margin: 5px 0 15px 0;
    border: 1px solid #444;
    border-radius: 5px;
    background-color: #555;
    color: #e0e0e0;
}

/* Submit and Button Styling */
input[type="submit"], input[type="button"] {
    background-color: #D91C34; /* Captain America red */
    width: 20%;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

input[type="submit"]:hover, input[type="button"]:hover {
    background-color: #005fa1; /* Captain America blue */
}

/* Centering the Submit Button */
.form-group {
    margin-top: 20px;
    text-align: center;
}

/* Form Styling */
.student-form {
    background-color: #2F4F4F; /* Dark gray */
    padding: 20px;
    width: 400px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
    margin-bottom: 20px;
    margin: 20px auto;
}

/* Submit Button (for forms) */
.submit-button {
    margin-left: 10px;
    background-color: #D91C34; /* Red (Captain America) */
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

/* Select Input Styling */
select {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    margin-bottom: 10px;
    margin-top: 10px;
    width: 50%;
    font-size: 16px;
    background-color: #555;
    color: white;
    transition: border-color 0.3s;
}

select:focus {
    border-color: #005fa1; /* Blue on focus */
    outline: none;
}

select option {
    padding: 10px; /* Padding for options */
}

select:hover {
    border-color: #005fa1; /* Blue on hover */
}

/* Sidebar Styling */
.sidebar {
    width: 250px;
    left: 0;
    background-color: #003366; /* Dark blue for the sidebar */
    padding: 20px;
    height: 100vh; /* Full height */
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
    background-color: #D91C34; /* Captain America Red on hover */
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
    <h2>Student Management</h2>

    <!-- Create Form -->
    <h3>Add New Student</h3>
    <form method="POST" class="student-form">

        <label>Identification Number:</label>
        <input type="text" id="identification_number" name="identification_number" value="<?php echo isset($row) ? $row['identification_number'] : ''; ?>">

        <label>First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo isset($row) ? $row['first_name'] : ''; ?>" required>

        <label>Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo isset($row) ? $row['last_name'] : ''; ?>" required>

        <label>Date of Birth:</label>
        <input type="date" id="date_of_birth" name="date_of_birth" value="<?php echo isset($row) ? $row['date_of_birth'] : ''; ?>" required>

        <label>Total Credit:</label>
        <input type="number" id="total_credit" name="total_credit" min="0" max="999" value="<?php echo isset($row) ? $row['total_credit'] : ''; ?>">

        <label>Street Number:</label>
        <input type="text" id="street_number" name="street_number" value="<?php echo isset($row) ? $row['street_number'] : ''; ?>">

        <label>Street Name:</label>
        <input type="text" id="street_name" name="street_name" value="<?php echo isset($row) ? $row['street_name'] : ''; ?>">

        <label>Apt Number:</label>
        <input type="text" id="apt_number" name="apt_number" value="<?php echo isset($row) ? $row['apt_number'] : ''; ?>">

        <label>City:</label>
        <input type="text" id="city" name="city" value="<?php echo isset($row) ? $row['city'] : ''; ?>">

        <label>State:</label>
        <input type="text" id="state" name="state" value="<?php echo isset($row) ? $row['state'] : ''; ?>">

        <label>Postal Code:</label>
        <input type="text" id="postal_code" name="postal_code" value="<?php echo isset($row) ? $row['postal_code'] : ''; ?>">

        <label>Department:</label>
        <select id="department_id" name="department_id" required>
            <option value="">Select Department</option>
            <?php
            // Fetch departments for the dropdown
            $departments_sql = "SELECT department_id, department_name FROM department";
            $departments_result = $conn->query($departments_sql);
            if ($departments_result->num_rows > 0) {
                while($department = $departments_result->fetch_assoc()) {
                    $selected = (isset($row) && $row['department_id'] == $department['department_id']) ? 'selected' : '';
                    echo "<option value=\"{$department['department_id']}\" $selected>{$department['department_name']}</option>";
                }
            }
            ?>
        </select>

        <div class="form-group">
            <input type="submit" name="<?php echo isset($row) ? 'update' : 'create'; ?>" value="<?php echo isset($row) ? 'Update Student' : 'Create Student'; ?>">
            <input type="button" class="submit-button" value="Clear" onclick="clearForm()">
        </div>
    </form>

   <!-- Read Table -->
   <h3>Student List</h3>
    <table>
        <tr>
            <th>Identification Number</th> 
            <th>First Name</th>
            <th>Last Name</th>
            <th>Date of Birth</th>
            <th>Department</th> <!-- Display department name using department_id -->
            <th>Total Credit</th> <!-- Updated to Total Credit -->
            <th>Actions</th>
        </tr>
        <?php
        // Fetch all students for display
        $sql = "SELECT * FROM student";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                // Fetch department name based on department_id
                $department_sql = "SELECT department_name FROM department WHERE department_id = {$row['department_id']}";
                $department_result = $conn->query($department_sql);
                $department_name = $department_result->num_rows > 0 ? $department_result->fetch_assoc()['department_name'] : 'N/A';

                echo "<tr>
                    <td>{$row['identification_number']}</td> <!-- Display Identification Number -->
                    <td>{$row['first_name']}</td>
                    <td>{$row['last_name']}</td>
                    <td>{$row['date_of_birth']}</td>
                    <td>{$department_name}</td> <!-- Display Department Name -->
                    <td>{$row['total_credit']}</td> <!-- Display Total Credit -->
                    <td>
                        <a href='student.php?edit={$row['identification_number']}'>Edit</a> | 
                        <a href='student.php?delete={$row['identification_number']}'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='7'>No students found</td></tr>"; // Adjusted colspan to match number of columns
        }
        ?>
    </table>

    <script>
        function clearForm() {
            document.getElementById('identification_number').value = '';
            document.getElementById('first_name').value = '';
            document.getElementById('last_name').value = '';
            document.getElementById('date_of_birth').value = '';
            document.getElementById('total_credit').value = '';
            document.getElementById('street_number').value = '';
            document.getElementById('street_name').value = '';
            document.getElementById('apt_number').value = '';
            document.getElementById('city').value = '';
            document.getElementById('state').value = '';
            document.getElementById('postal_code').value = '';
            document.getElementById('department_id').value = '';
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
