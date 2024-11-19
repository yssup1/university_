<?php
include 'db_connection.php';

// Handle Create Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $department_name = $_POST['department_name'];
    $building = $_POST['building'];
    $budget = !empty($_POST['budget']) ? $_POST['budget'] : null; // Optional budget

    // Check for duplicates
    $check_sql = "SELECT * FROM department WHERE department_name='$department_name' AND building='$building' AND budget='$budget'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        echo '<div class="error">Error: A department with the same name, building, and budget already exists.</div>';
    } else {
        // Insert the new department
        $sql = "INSERT INTO department (department_name, building, budget) VALUES ('$department_name', '$building', '$budget')";
        
        if ($conn->query($sql) === TRUE) {
            echo '<div class="success">New department created successfully.</div>';
        } else {
            echo '<div class="error">Error: ' . $sql . "<br>" . $conn->error;
        }
    }
}

// Handle Update Operation
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM department WHERE department_id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['department_id'];
    $department_name = $_POST['department_name'];
    $building = $_POST['building'];
    $budget = $_POST['budget'];

    // Update the department
    $sql = "UPDATE department SET department_name='$department_name', building='$building', budget='$budget' WHERE department_id='$id'";
    
    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Department updated successfully.</div>';
    } else {
        echo '<div class="error">Error updating record: ' . $conn->error;
    }
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM department WHERE department_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Department deleted successfully.</div>';
    } else {
        echo '<div class="error">Error deleting record: ' . $conn->error;
    }
}

// Fetch all departments for display
$sql = "SELECT * FROM department";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Department Management</title>
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
        <h2>Department Management</h2>

        <!-- Create Form -->
        <h3>Add New Department</h3>
        <form method="POST" class="department-form">
            <input type="hidden" name="department_id" value="<?php echo isset($row) ? $row['department_id'] : ''; ?>">
            <div class="form-group">
                <label for="department_name">Department Name:</label>
                <input type="text" id="department_name" name="department_name" required value="<?php echo isset($row) ? $row['department_name'] : ''; ?>">
            </div>

            <div class="form-group">
                <label for="building">Building:</label>
                <select id="building" name="building" required>
                    <option value="">Select Building</option>
                    <?php
                    // Fetch building names from the classroom table
                    $building_sql = "SELECT DISTINCT building FROM classroom";
                    $building_result = $conn->query($building_sql);

                    if ($building_result->num_rows > 0) {
                        while ($building_row = $building_result->fetch_assoc()) {
                            // Check if this building is selected in the form (for editing)
                            $selected = (isset($row) && $row['building'] === $building_row['building']) ? 'selected' : '';
                            echo "<option value='{$building_row['building']}' $selected>{$building_row['building']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="budget">Budget:</label>
                <input type="number" id="budget" name="budget" step="0.01" value="<?php echo isset($row) ? $row['budget'] : ''; ?>">
            </div>

            <input type="submit" class="submit-button" name="<?php echo isset($row) ? 'update' : 'create'; ?>" value="<?php echo isset($row) ? 'Update Department' : 'Create Department'; ?>">
            <input type="button" class="submit-button" value="Clear" onclick="clearForm()">
        </form>

        <!-- Read Table -->
        <h3>Department List</h3>
        <table>
            <tr>
                <th>Department Name</th>
                <th>Building</th>
                <th>Budget</th>
                <th>Actions</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                        <td>{$row['department_name']}</td>
                        <td>{$row['building']}</td>
                        <td>" . (is_null($row['budget']) ? 'NULL' : $row['budget']) . "</td>
                        <td>
                            <a href='department.php?edit={$row['department_id']}' class='action-link'>Edit</a> |
                            <a href='department.php?delete={$row['department_id']}' class='action-link'>Delete</a>
                        </td>
                    </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No departments found</td></tr>";
            }
            ?>
        </table>
    </div>
            
    <script>
        function clearForm() {
            document.getElementById('department_name').value = '';
            document.getElementById('building').value = '';
            document.getElementById('budget').value = '';
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>
