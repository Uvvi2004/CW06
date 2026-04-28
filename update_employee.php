<?php
require "db.php";

$message = "";
$messageType = "";
$row = null;

$empId = (int)($_POST['emp_id'] ?? $_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $empId > 0) {
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
            "UPDATE employees
                SET emp_name = ?, job_name = ?, salary = ?, hire_date = ?,
                    department_id = ?, department_name = ?
              WHERE emp_id = ?"
        );
        $stmt->bind_param("ssssisi", $name, $job, $salary, $hire, $deptId, $deptName, $empId);

        if ($stmt->execute()) {
            $affected = $stmt->affected_rows;
            if ($affected === 1) {
                $message = "Updated 1 row(s). Employee ID " . $empId . " saved.";
                $messageType = "success";
            } elseif ($affected === 0) {
                $message = "No changes made. Values may already match or ID does not exist.";
                $messageType = "info";
            } else {
                $message = "Updated " . $affected . " row(s).";
                $messageType = "success";
            }
        } else {
            $message = "Error: " . $stmt->error;
            $messageType = "error";
        }
        $stmt->close();
    }
}

if ($empId > 0) {
    $stmt = $conn->prepare("SELECT * FROM employees WHERE emp_id = ?");
    $stmt->bind_param("i", $empId);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update Employee</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="shell">
    <div class="card">
        <h1>Update Employee</h1>
        <p class="subtitle">Edit an existing employee record</p>

        <nav class="nav">
            <a href="index.php">Home</a>
            <a href="read_employees.php">Read</a>
            <a href="create_employee.php">Create</a>
            <a href="update_employee.php" class="active">Update</a>
            <a href="delete_employee.php">Delete</a>
            <a href="employee_demo.php">Demo Form</a>
        </nav>

        <?php if ($message): ?>
            <div class="msg <?= htmlspecialchars($messageType) ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if (!$row): ?>
            <form method="get">
                <div class="form-grid">
                    <div class="field">
                        <label for="id">Employee ID</label>
                        <input id="id" name="id" type="number" min="1" required>
                    </div>
                </div>
                <div class="actions">
                    <button class="btn" type="submit">Load record</button>
                    <a class="btn ghost" href="read_employees.php">Browse all</a>
                </div>
            </form>
            <?php if ($empId > 0 && !$row && $messageType !== 'error'): ?>
                <div class="msg error">No employee found with ID <?= (int)$empId ?>.</div>
            <?php endif; ?>
        <?php else: ?>
            <form method="post">
                <input type="hidden" name="emp_id" value="<?= (int)$row['emp_id'] ?>">
                <div class="form-grid">
                    <div class="field">
                        <label for="emp_name">Employee name</label>
                        <input id="emp_name" name="emp_name" type="text" required maxlength="100" value="<?= htmlspecialchars($row['emp_name']) ?>">
                    </div>
                    <div class="field">
                        <label for="job_name">Job title</label>
                        <input id="job_name" name="job_name" type="text" required maxlength="100" value="<?= htmlspecialchars($row['job_name']) ?>">
                    </div>
                    <div class="field">
                        <label for="salary">Salary (USD)</label>
                        <input id="salary" name="salary" type="number" step="0.01" min="0" required value="<?= htmlspecialchars($row['salary']) ?>">
                    </div>
                    <div class="field">
                        <label for="hire_date">Hire date</label>
                        <input id="hire_date" name="hire_date" type="date" required value="<?= htmlspecialchars($row['hire_date']) ?>">
                    </div>
                    <div class="field">
                        <label for="department_id">Department ID</label>
                        <input id="department_id" name="department_id" type="number" min="1" required value="<?= htmlspecialchars($row['department_id']) ?>">
                    </div>
                    <div class="field">
                        <label for="department_name">Department name</label>
                        <input id="department_name" name="department_name" type="text" required maxlength="100" value="<?= htmlspecialchars($row['department_name']) ?>">
                    </div>
                </div>
                <div class="actions">
                    <button class="btn" type="submit">Save changes</button>
                    <a class="btn ghost" href="read_employees.php">Cancel</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <footer>ucota1 &middot; CW-06</footer>
</div>
</body>
</html>
<?php $conn->close(); ?>
