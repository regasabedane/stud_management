<?php
session_start();
require_once 'db.php';

// Restrict access to Admin only
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";

// Handle Course Addition
if (isset($_POST['save_course'])) {
    $course_code   = strtoupper(trim($_POST['course_code']));
    $course_name   = trim($_POST['course_name']);
    $department_id = $_POST['department_id'];

    if (!empty($course_code) && !empty($course_name) && !empty($department_id)) {
        try {
            $stmt = $conn->prepare("INSERT INTO courses (course_code, course_name, department_id) VALUES (:code, :name, :dept_id)");
            $stmt->execute([
                ':code'    => $course_code,
                ':name'    => $course_name,
                ':dept_id' => $department_id
            ]);
            $message = "<div class='alert alert-success'>Gosi barnootaa (Course) milkaa'inaan galmaa'era!</div>";
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>Dogoggora: Course Code kun koraan galmaa'eera!</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Usoo hin guutin hin dhiisin!</div>";
    }
}

// Fetch all Departments for selection dropdown
$departments = $conn->query("SELECT * FROM departments ORDER BY department_name ASC")->fetchAll(PDO::FETCH_ASSOC);

// Fetch all Courses with their respective Department names
$query = "SELECT courses.*, departments.department_name 
          FROM courses 
          JOIN departments ON courses.department_id = departments.id 
          ORDER BY courses.id DESC";
$courses = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Courses - Student Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Course Management</h2>
        <div>
            <a href="manage_academic.php" class="btn btn-outline-secondary me-2">Departments</a>
            <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <?= $message; ?>

    <div class="row">
        <!-- Add Course Form -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Add New Course</h5>
                </div>
                <div class="card-body">
                    <form action="manage_courses.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Department Filadhu</label>
                            <select name="department_id" class="form-select" required>
                                <option value="">-- Select Department --</option>
                                <?php foreach ($departments as $d): ?>
                                    <option value="<?= $d['id']; ?>"><?= htmlspecialchars($d['department_name']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course Code</label>
                            <input type="text" name="course_code" class="form-control" placeholder="fkn: CS201" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course Name</label>
                            <input type="text" name="course_name" class="form-control" placeholder="fkn: Web Development" required>
                        </div>
                        <button type="submit" name="save_course" class="btn btn-success w-100">Save Course</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Courses List Table -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Tarree Golla Barnoota (Courses)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped align-middle">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Code</th>
                                <th>Course Name</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($courses) > 0): ?>
                                <?php foreach ($courses as $c): ?>
                                    <tr>
                                        <td><?= $c['id']; ?></td>
                                        <td><span class="badge bg-primary"><?= htmlspecialchars($c['course_code']); ?></span></td>
                                        <td><?= htmlspecialchars($c['course_name']); ?></td>
                                        <td><?= htmlspecialchars($c['department_name']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Courses galmaa'an hin jiran.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>