<?php
require "db.php";
$totalRows = 0;
$res = $conn->query("SELECT COUNT(*) AS c FROM employees");
if ($res) {
    $totalRows = (int)$res->fetch_assoc()['c'];
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CW-06 | PHP + MySQL CRUD</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="shell">
    <div class="card">
        <h1>CW-06 &mdash; PHP + MySQL CRUD</h1>
        <p class="subtitle">Web Programming &middot; Prof. Henry Louis &middot; GSU</p>

        <nav class="nav">
            <a href="index.php" class="active">Home</a>
            <a href="read_employees.php">Read</a>
            <a href="create_employee.php">Create</a>
            <a href="update_employee.php">Update</a>
            <a href="delete_employee.php">Delete</a>
            <a href="employee_demo.php">Demo Form</a>
        </nav>

        <div class="msg info">
            Database: <strong>ucota1</strong> &middot; Table: <strong>employees</strong> &middot;
            Rows: <strong><?= $totalRows ?></strong>
        </div>

        <h2>What this project demonstrates</h2>
        <ul>
            <li>Secure MySQLi connection with <code>connect_error</code> check (db.php)</li>
            <li>Schema-first design: <code>employees</code> table with AUTO_INCREMENT primary key and NOT NULL constraints</li>
            <li>Full CRUD using prepared statements and <code>bind_param()</code></li>
            <li>Output sanitization with <code>htmlspecialchars()</code></li>
            <li>Targeted UPDATE / DELETE using <code>WHERE emp_id = ?</code> only</li>
            <li>One professional HTML form demo page using external CSS</li>
        </ul>

        <h2>Quick links</h2>
        <div class="actions">
            <a class="btn" href="read_employees.php">View employees</a>
            <a class="btn ghost" href="create_employee.php">Add employee</a>
            <a class="btn ghost" href="employee_demo.php">Open demo form</a>
        </div>
    </div>

    <footer>
        ucota1 &middot; CW-06 MySQL + PHP &middot; Submitted April 2026
    </footer>
</div>
</body>
</html>
