<?php
session_start();
require_once 'db.php';

// Mirkaneessuu: User-ni login gochuu fi Role-n isaa 'student' ta'uu isaa
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'student') {
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// 1. Odeeffannoo Barataa Fiduu
$stmt_stud = $conn->prepare("SELECT * FROM students WHERE id = :id");
$stmt_stud->execute([':id' => $student_id]);
$student = $stmt_stud->fetch(PDO::FETCH_ASSOC);

// 2. Qabxii Barataa Sanaa Fiduu
$stmt_grade = $conn->prepare("SELECT * FROM grades WHERE student_id = :id");
$stmt_grade->execute([':id' => $student_id]);
$grades = $stmt_grade->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Welcome, <?= htmlspecialchars($student['full_name'] ?? $_SESSION['username']); ?>!</h2>
        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>

    <div class="row">
        <!-- Student Profile Card -->
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">My Profile</h5>
                </div>
                <div class="card-body">
                    <p><strong>Email:</strong> <?= htmlspecialchars($student['email'] ?? 'N/A'); ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($student['phone'] ?? 'N/A'); ?></p>
                    <p><strong>Course:</strong> <?= htmlspecialchars($student['course'] ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>

        <!-- Grade Results Table -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">My Academic Results (Qabxii Koo)</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Subject (Gosa Barnootaa)</th>
                                <th>Marks (Qabxii 100% irraa)</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($grades) > 0): ?>
                                <?php foreach ($grades as $grade): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($grade['subject_name']); ?></td>
                                        <td><?= htmlspecialchars($grade['marks']); ?></td>
                                        <td>
                                            <?php if ($grade['marks'] >= 50): ?>
                                                <span class="badge bg-success">Pass</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Fail</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">Qabxiin kee ammayyuu hin galmeeffamne.</td>
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