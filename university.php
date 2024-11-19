<!DOCTYPE html>
<html>
<head>
    <title>University Management System</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <style>
       /* General body styling */
body {
    font-family: Arial, sans-serif;
    background-color: #e9f1f5; /* Light blue background to mimic Captain America's color palette */
    color: #333; /* Dark text color for readability */
    margin: 0;
    padding: 0;
    display: flex;
}

/* Sidebar styling */
.sidebar {
    width: 250px;
    background-color: #003366; /* Dark blue background to represent Captain America’s shield */
    padding: 20px;
    height: 100vh; /* Full height */
    box-shadow: 2px 0 5px rgba(0, 0, 0, 0.5);
    color: white; /* White text for contrast */
}

.sidebar h1 {
    color: white; /* White heading to stand out */
    font-size: 24px;
    text-align: center;
    margin-bottom: 30px;
}

/* Sidebar links */
.sidebar a {
    display: block;
    color: #fff; /* White text */
    text-decoration: none;
    padding: 12px 15px;
    margin-bottom: 12px;
    background-color: #d91c34; /* Red background to represent Captain America's shield red color */
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.sidebar a:hover {
    background-color: #005fa1; /* Blue background on hover to reflect the blue shield */
    color: white; /* Ensure the text remains white on hover */
}

/* Main content area styling */
.content {
    flex: 1; /* Take the remaining space */
    padding: 30px;
    background-color: #ffffff; /* White background to balance out the strong colors */
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    margin: 20px;
}

.content h2 {
    color: #003366; /* Dark blue color for headings */
    font-size: 28px;
    margin-bottom: 20px;
}

.content p {
    color: #333; /* Dark text for paragraphs for easy reading */
    font-size: 16px;
}

/* Optional: Add a Captain America shield-inspired border to the content area */
.content {
    border: 3px solid #d91c34; /* Red border */
    border-radius: 10px;
}


    </style>
</head>
<body>

<div class="sidebar">
    <h1>University Management</h1>
    <a href="instructor.php" class="button">Instructor</a>
    <a href="department.php" class="button">Department</a>
    <a href="course.php" class="button">Course</a>
    <a href="classroom.php" class="button">Classroom</a>
    <a href="time_slot.php" class="button">Time Slot</a>
    <a href="student.php" class="button">Student</a>
</div>

<div class="content">
    <h2>Welcome to the University Management System</h2>
    <p>Use the navigation links on the sidebar to manage different sections of the university database.</p>
</div>

</body>
</html>
