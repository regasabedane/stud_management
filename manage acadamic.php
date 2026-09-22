<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";

// 1. Save Department
if (isset($_POST['save_dept'])) {
    $dept_name = trim($_POST['department_name']);
    if (!empty($dept_name)) {
        try {
            $stmt = $conn->prepare("INSERT INTO departments (department_name) VALUES (:name)");
            $stmt->execute([':name' => $dept_name]);
            $message = "<div class='alert alert-success'>Department milkaa'inaan dabalameera!</div>";
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>Department kun koraan jira!</div>";
        }
    }
}

// 2. Save Course
if (isset($_POST['save_course'])) {
    $course_name = trim($_POST['course_name']);
    $course_code = trim($_POST['course_code']);
    $dept_id     = $_POST['department_id'];

    if (!empty($course_name) && !empty($course_code) && !empty($dept_id)) {
        try {
            $stmt = $conn->prepare("INSERT INTO courses (course_name, course_code, department_id) VALUES (:name, :code, :dept_id)");
            $stmt->execute([
                ':name'    => $course_name,
                ':code'    => $course_code,
                ':dept_id' => $dept_id
            ]);
            $message = "<div class='alert alert-success'>Course milkaa'inaan dabalameera!</div>";
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>Course Code kun koraan jira!</div>";
        }
    }
}

// Fetch Data
$departments = $conn->query("SELECT * FROM departments ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
$courses     = $conn->query("SELECT courses.*, departments.department_name FROM courses JOIN departments ON courses.department_id = departments.id ORDER BY courses.id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Academic Setup - Departments & Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manage Departments & Courses</h2>
        <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <?= $message; ?>

    <div class="row">
        <!-- Add Department Form -->
        <div class="col-md-5">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Add New Department</h5>
                </div>
                <div class="card-body">
                    <form action="manage_academic.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Department Name</label>
                            <input type="text" name="department_name" class="form-control" placeholder="fkn: Computer Science" required>
                        </div>
                        <button type="submit" name="save_dept" class="btn btn-primary w-100">Save Department</button>
                    </form>
                </div>
            </div>

            <!-- Add Course Form -->
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Add New Course</h5>
                </div>
                <div class="card-body">
                    <form action="manage_academic.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Department Filadhu</label>
                            <select name="department_id" class="form-select" required>
                                <option value="">-- Select Dept --</option>
                                <?php foreach ($departments as $d): ?>
                                    <option value="<?= $d['id']; ?>"><?= htmlspecialchars($d['department_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" name="course_name" class="form-control" placeholder="fkn: Web Programming" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course Code</label>
                            <input type="text" name="course_code" class="form-control" placeholder="fkn: CS201" required>
                        </div>
                        <button type="submit" name="save_course" class="btn btn-success w-100">Save Course</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tables View -->
        <div class="col-md-7">
            <!-- Departments Table -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Departments List</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Department Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($departments as $d): ?>
                                <tr>
                                    <td><?= $d['id']; ?></td>
                                    <td><?= htmlspecialchars($d['department_name']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Courses Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Courses List</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm table-bordered">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Course Name</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $c): ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?= htmlspecialchars($c['course_code']); ?></span></td>
                                    <td><?= htmlspecialchars($c['course_name']); ?></td>
                                    <td><?= htmlspecialchars($c['department_name']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>