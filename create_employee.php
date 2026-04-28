<?php
require "db.php";

$message = "";
$messageType = "";
$insertedId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['emp_name']        ?? '');
    $job      = trim($_POST['job_name']        ?? '');
    $salary   = (float)($_POST['salary']       ?? 0);
    $hire     = trim($_POST['hire_date']       ?? '');
    $deptId   = (int)($_POST['department_id']  ?? 0);
    $deptName = trim($_POST['department_name'] ?? '');

    if ($name === '' || $job === '' || $hire === '' || $deptName === '' || $salary <= 0 || $deptId <= 0) {
        $message = "All fields are required and salary / department id must be positive.";
        $messageType = "error";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO employees
                (emp_name, job_name, salary, hire_date, department_id, department_name)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->bind_param("ssssis", $name, $job, $salary, $hire, $deptId, $deptName);

        if ($stmt->execute()) {
            $insertedId = $stmt->insert_id;
            $message = "Success! Inserted employee ID: " . $insertedId;
            $messageType = "success";
        } else {
            $message = "Error: " . $stmt->error;
            $messageType = "error";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CREATE &mdash; New Employee</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="shell">
    <div class="card">
        <h1>Create Employee</h1>
        <p class="subtitle">CREATE operation &middot; INSERT INTO employees with prepared statement</p>

        <nav class="nav">
            <a href="index.php">Home</a>
            <a href="read_employees.php">Read</a>
            <a href="create_employee.php" class="active">Create</a>
            <a href="update_employee.php">Update</a>
            <a href="delete_employee.php">Delete</a>
            <a href="employee_demo.php">Demo Form</a>
        </nav>

        <?php if ($message): ?>
            <div class="msg <?= htmlspecialchars($messageType) ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-grid">
                <div class="field">
                    <label for="emp_name">Employee name</label>
                    <input id="emp_name" name="emp_name" type="text" required maxlength="100" placeholder="Sara Cole">
                </div>
                <div class="field">
                    <label for="job_name">Job title</label>
                    <input id="job_name" name="job_name" type="text" required maxlength="100" placeholder="Designer">
                </div>
                <div class="field">
                    <label for="salary">Salary (USD)</label>
                    <input id="salary" name="salary" type="number" step="0.01" min="0" required placeholder="71000.00">
                </div>
                <div class="field">
                    <label for="hire_date">Hire date</label>
                    <input id="hire_date" name="hire_date" type="date" required>
                </div>
                <div class="field">
                    <label for="department_id">Department ID</label>
                    <input id="department_id" name="department_id" type="number" min="1" required placeholder="3">
                </div>
                <div class="field">
                    <label for="department_name">Department name</label>
                    <input id="department_name" name="department_name" type="text" required maxlength="100" placeholder="Marketing">
                </div>
            </div>

            <div class="actions">
                <button class="btn" type="submit">Insert employee</button>
                <a class="btn ghost" href="read_employees.php">View all records</a>
            </div>
        </form>
    </div>

    <footer>ucota1 &middot; CW-06 &middot; CREATE</footer>
</div>
</body>
</html>
<?php $conn->close(); ?>
