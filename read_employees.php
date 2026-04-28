<?php
require "db.php";

$result = $conn->query(
    "SELECT emp_id, emp_name, job_name, salary, hire_date, department_id, department_name
     FROM employees
     ORDER BY emp_id DESC"
);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>READ &mdash; Employees</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="shell">
    <div class="card">
        <h1>Employee Records</h1>
        <p class="subtitle">READ operation &middot; SELECT * FROM employees</p>

        <nav class="nav">
            <a href="index.php">Home</a>
            <a href="read_employees.php" class="active">Read</a>
            <a href="create_employee.php">Create</a>
            <a href="update_employee.php">Update</a>
            <a href="delete_employee.php">Delete</a>
            <a href="employee_demo.php">Demo Form</a>
        </nav>

        <div class="msg info">
            Total rows fetched: <strong><?= $result->num_rows ?></strong>
        </div>

        <?php if ($result->num_rows === 0): ?>
            <p>No employees found. <a href="create_employee.php">Add the first one</a>.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Job</th>
                        <th>Salary</th>
                        <th>Hired</th>
                        <th>Dept ID</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$row['emp_id'] ?></td>
                        <td><?= htmlspecialchars($row['emp_name']) ?></td>
                        <td><?= htmlspecialchars($row['job_name']) ?></td>
                        <td>$<?= number_format((float)$row['salary'], 2) ?></td>
                        <td><?= htmlspecialchars($row['hire_date']) ?></td>
                        <td><?= (int)$row['department_id'] ?></td>
                        <td><?= htmlspecialchars($row['department_name']) ?></td>
                        <td class="actions-cell">
                            <a href="update_employee.php?id=<?= (int)$row['emp_id'] ?>">Edit</a>
                            <a href="delete_employee.php?id=<?= (int)$row['emp_id'] ?>">Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="actions">
            <a class="btn" href="create_employee.php">Add new employee</a>
        </div>
    </div>

    <footer>ucota1 &middot; CW-06 &middot; READ</footer>
</div>
</body>
</html>
<?php $conn->close(); ?>
