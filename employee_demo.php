<?php
require "db.php";

$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['emp_name']        ?? '');
    $job      = trim($_POST['job_name']        ?? '');
    $salary   = isset($_POST['salary'])        ? floatval($_POST['salary'])        : 0;
    $hire     = trim($_POST['hire_date']       ?? '');
    $deptId   = isset($_POST['department_id']) ? intval($_POST['department_id'])   : 0;
    $deptName = trim($_POST['department_name'] ?? '');

    if (empty($name) || empty($job) || $salary <= 0 || empty($hire) || $deptId <= 0 || empty($deptName)) {
        $message = "All fields are required and salary must be greater than 0.";
        $messageType = "error";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO employees (emp_name, job_name, salary, hire_date, department_id, department_name)
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        if ($stmt === false) {
            $message = "Error: " . htmlspecialchars($conn->error);
            $messageType = "error";
        } else {
            $stmt->bind_param("ssssis", $name, $job, $salary, $hire, $deptId, $deptName);

            if ($stmt->execute()) {
                $insertId = $stmt->insert_id;
                $message = "Employee saved successfully! (ID: $insertId)";
                $messageType = "success";
                $_POST = [];
            } else {
                $message = "Error: " . htmlspecialchars($stmt->error);
                $messageType = "error";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Employee Demo Form</title>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body class="demo-page">
    <div class="demo-shell">
        <div class="demo-card">
            <h1 class="demo-title">Employee Management Form</h1>
            <p class="demo-subtitle">CW-06 &middot; Web Programming &middot; Prof. Henry Louis &middot; GSU</p>
        </div>

        <?php if ($message): ?>
            <div class="demo-msg <?= $messageType; ?>">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <div class="demo-card">
            <form method="POST" action="">
                <div class="demo-grid">
                    <div class="demo-field">
                        <label class="demo-label" for="emp_name">Employee Name *</label>
                        <input class="demo-input" type="text" id="emp_name" name="emp_name"
                            placeholder="e.g., John Doe"
                            value="<?= htmlspecialchars($_POST['emp_name'] ?? ''); ?>" required />
                    </div>

                    <div class="demo-field">
                        <label class="demo-label" for="job_name">Job Title *</label>
                        <input class="demo-input" type="text" id="job_name" name="job_name"
                            placeholder="e.g., Software Engineer"
                            value="<?= htmlspecialchars($_POST['job_name'] ?? ''); ?>" required />
                    </div>

                    <div class="demo-field">
                        <label class="demo-label" for="salary">Salary ($) *</label>
                        <input class="demo-input" type="number" id="salary" name="salary"
                            step="0.01" placeholder="e.g., 75000.00"
                            value="<?= htmlspecialchars($_POST['salary'] ?? ''); ?>" required />
                    </div>

                    <div class="demo-field">
                        <label class="demo-label" for="hire_date">Hire Date *</label>
                        <input class="demo-input" type="date" id="hire_date" name="hire_date"
                            value="<?= htmlspecialchars($_POST['hire_date'] ?? ''); ?>" required />
                    </div>

                    <div class="demo-field">
                        <label class="demo-label" for="department_id">Department ID *</label>
                        <input class="demo-input" type="number" id="department_id" name="department_id"
                            placeholder="e.g., 1"
                            value="<?= htmlspecialchars($_POST['department_id'] ?? ''); ?>" required />
                    </div>

                    <div class="demo-field">
                        <label class="demo-label" for="department_name">Department Name *</label>
                        <input class="demo-input" type="text" id="department_name" name="department_name"
                            placeholder="e.g., Engineering"
                            value="<?= htmlspecialchars($_POST['department_name'] ?? ''); ?>" required />
                    </div>
                </div>

                <div class="demo-actions">
                    <button type="submit" class="demo-btn">Save Employee</button>
                    <a href="read_employees.php" class="demo-link">View All Records</a>
                </div>
            </form>
        </div>

        <div class="demo-card">
            <p class="demo-info">
                Uses prepared statements with bind_param() to prevent SQL injection.
                All output uses htmlspecialchars() to prevent XSS. Input is validated before insertion.
            </p>
        </div>
    </div>
</body>
</html>
