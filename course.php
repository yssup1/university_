<?php
include 'db_connection.php';

// Fetch departments for dropdown
$departments_sql = "SELECT DISTINCT department_id, department_name FROM department";
$departments_result = $conn->query($departments_sql);

// Handle Create Operation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $total_credit = empty($_POST['total_credit']) ? "'N/A'" : $_POST['total_credit']; 
    $department_id = $_POST['department_id'];

    // Check for duplicates before inserting
    $check_sql = "SELECT * FROM course WHERE course_code = '$course_code'";
    $check_result = $conn->query($check_sql);

    if ($check_result->num_rows > 0) {
        echo '<div class="error">Error: A course with the same code already exists.</div>';
    } else {
        // Insert the new course
        $sql = "INSERT INTO course (course_code, course_name, total_credit, department_id) 
                VALUES ('$course_code', '$course_name', $total_credit, $department_id)";
        if ($conn->query($sql) === TRUE) {
            echo '<div class="success">New course created successfully.</div>';
        } else {
            echo '<div class="error">Error: ' . $conn->error . '</div>';
        }
    }
}

// Handle Update Operation
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $sql = "SELECT * FROM course WHERE course_id='$id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['course_id'];
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    $total_credit = empty($_POST['total_credit']) ? "'N/A'" : $_POST['total_credit']; 
    $department_id = $_POST['department_id'];

    $sql = "UPDATE course SET 
        course_code='$course_code', 
        course_name='$course_name', 
        total_credit=$total_credit, 
        department_id=$department_id
        WHERE course_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Course updated successfully.</div>';
    } else {
        echo '<div class="error">Error updating record: ' . $conn->error . '</div>';
    }
}

// Handle Delete Operation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM course WHERE course_id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo '<div class="success">Course deleted successfully.</div>';
    } else {
        echo '<div class="error">Error deleting record: ' . $conn->error . '</div>';
    }
}

// Fetch all courses for display
$sql = "SELECT c.course_id, c.course_code, c.course_name, c.total_credit, d.department_name 
        FROM course c
        LEFT JOIN department d ON c.department_id = d.department_id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Course Management</title>
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
            <h2>Course Management</h2>

            <!-- Create Form -->
            <h3>Add New Course</h3>
            <form method="POST" class="course-form">
            <input type="hidden" name="course_id" value="<?php echo isset($row) ? $row['course_id'] : ''; ?>">

            <div class="form-group">
                <label for="course_code">Course Code:</label>
                <input type="text" id="course_code" name="course_code" required value="<?php echo isset($row) ? $row['course_code'] : ''; ?>">
            </div>

            <div class="form-group">
                <label for="course_name">Course Name:</label>
                <input type="text" id="course_name" name="course_name" required value="<?php echo isset($row) ? $row['course_name'] : ''; ?>">
            </div>

            <div class="form-group">
                <label for="total_credit">Total Credits:</label>
                <input type="number" id="total_credit" name="total_credit"  value="<?php echo isset($row) ? $row['total_credit'] : ''; ?>">
            </div>

            <div class="form-group">
                <label for="department_id">Department Name:</label>
                <select id="department_id" name="department_id" required >
                    <option value="">Select Department</option>
                    <?php
                    if ($departments_result->num_rows > 0) {
                        while ($department = $departments_result->fetch_assoc()) {
                            $selected = (isset($row) && $row['department_id'] == $department['department_id']) ? 'selected' : '';
                            echo "<option value=\"{$department['department_id']}\" $selected>{$department['department_name']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <input type="submit" class="submit-button" name="<?php echo isset($row) ? 'update' : 'create'; ?>" value="<?php echo isset($row) ? 'Update Course' : 'Create Course'; ?>">
            <input type="button" class="submit-button" value="Clear" onclick="clearForm()">

        </form>


    <!-- Read Table -->
    <h3>Course List</h3>
    <table>
        <tr>
            <th>Course Code</th>
            <th>Course Name</th>
            <th>Total Credits</th>
            <th>Department Name</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['course_code']}</td>
                    <td>{$row['course_name']}</td>
                    <td>{$row['total_credit']}</td>
                    <td>{$row['department_name']}</td>
                    <td>
                        <a href='course.php?edit={$row['course_id']}'>Edit</a> | 
                        <a href='course.php?delete={$row['course_id']}'>Delete</a>
                    </td>
                </tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No courses found</td></tr>";
        }
        ?>
    </table>
</div>


<script>
    function clearForm() {
        document.getElementById('course_name').value = '';
        document.getElementById('course_code').value = '';
        document.getElementById('department_name').value = '';
        document.getElementById('total_credit').value = '';
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
