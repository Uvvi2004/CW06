<?php
require "db.php";

$message = "";
$messageType = "";
$row = null;

$empId = (int)($_POST['emp_id'] ?? $_GET['id'] ?? 0);
$confirmed = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm']));

if ($confirmed && $empId > 0) {
    $stmt = $conn->prepare("DELETE FROM employees WHERE emp_id = ?");
    $stmt->bind_param("i", $empId);

    if ($stmt->execute()) {
        $deleted = $stmt->affected_rows;
        if ($deleted === 1) {
            $message = "Deleted 1 row(s). ID " . $empId . " removed.";
            $messageType = "success";
        } else {
            $message = "No row deleted. ID " . $empId . " did not exist.";
            $messageType = "info";
        }
    } else {
        $message = "Error: " . $stmt->error;
        $messageType = "error";
    }
    $stmt->close();
    $empId = 0;
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
    <title>Delete Employee</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="shell">
    <div class="card">
        <h1>Delete Employee</h1>
        <p class="subtitle">Remove an employee record from the database</p>

        <nav class="nav">
            <a href="index.php">Home</a>
            <a href="read_employees.php">Read</a>
            <a href="create_employee.php">Create</a>
            <a href="update_employee.php">Update</a>
            <a href="delete_employee.php" class="active">Delete</a>
            <a href="employee_demo.php">Demo Form</a>
        </nav>

        <?php if ($message): ?>
            <div class="msg <?= htmlspecialchars($messageType) ?>"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php if ($row): ?>
            <div class="msg info">Confirm delete for the record below. This cannot be undone.</div>
            <table>
                <tr><th>Field</th><th>Value</th></tr>
                <tr><td>ID</td><td><?= (int)$row['emp_id'] ?></td></tr>
                <tr><td>Name</td><td><?= htmlspecialchars($row['emp_name']) ?></td></tr>
                <tr><td>Job</td><td><?= htmlspecialchars($row['job_name']) ?></td></tr>
                <tr><td>Salary</td><td>$<?= number_format((float)$row['salary'], 2) ?></td></tr>
                <tr><td>Hired</td><td><?= htmlspecialchars($row['hire_date']) ?></td></tr>
                <tr><td>Department</td><td><?= htmlspecialchars($row['department_name']) ?> (<?= (int)$row['department_id'] ?>)</td></tr>
            </table>
            <form method="post" onsubmit="return confirm('Permanently delete employee #<?= (int)$row['emp_id'] ?>?');">
                <input type="hidden" name="emp_id" value="<?= (int)$row['emp_id'] ?>">
                <input type="hidden" name="confirm" value="1">
                <div class="actions">
                    <button class="btn danger" type="submit">Confirm delete</button>
                    <a class="btn ghost" href="read_employees.php">Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <form method="get">
                <div class="form-grid">
                    <div class="field">
                        <label for="id">Employee ID to delete</label>
                        <input id="id" name="id" type="number" min="1" required>
                    </div>
                </div>
                <div class="actions">
                    <button class="btn" type="submit">Look up record</button>
                    <a class="btn ghost" href="read_employees.php">Browse all</a>
                </div>
            </form>
        <?php endif; ?>
    </div>

    <footer>ucota1 &middot; CW-06</footer>
</div>
</body>
</html>
<?php $conn->close(); ?>
