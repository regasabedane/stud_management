<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_POST['save_grade'])) {
    $student_id   = $_POST['student_id'];
    $subject_name = trim($_POST['subject_name']);
    $marks        = $_POST['marks'];

    if (!empty($student_id) && !empty($subject_name) && $marks !== "") {
        $stmt = $conn->prepare("INSERT INTO grades (student_id, subject_name, marks) VALUES (:student_id, :subject_name, :marks)");
        $stmt->execute([
            ':student_id'   => $student_id,
            ':subject_name' => $subject_name,
            ':marks'        => $marks
        ]);
        $message = "<div class='alert alert-success'>Qabxiin barataa milkaa'inaan dabalameera!</div>";
    }
}

// Fetch all students for dropdown
$students = $conn->query("SELECT * FROM students")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Grade - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 col-md-6">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white d-flex justify-content-between">
            <h5 class="mb-0">Barataaf Qabxii Galchuu</h5>
            <a href="index.php" class="btn btn-sm btn-light">Back to Dashboard</a>
        </div>
        <div class="card-body">
            <?= $message; ?>
            <form action="add_grade.php" method="POST">
                <div class="mb-3">
                    <label class="form-label">Barataa Filadhu</label>
                    <select name="student_id" class="form-select" required>
                        <option value="">-- Barataa Filadhu --</option>
                        <?php foreach ($students as $s): ?>
                            <option value="<?= $s['id']; ?>"><?= htmlspecialchars($s['full_name']); ?> (ID: <?= $s['id']; ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Gosa Barnootaa (Subject)</label>
                    <input type="text" name="subject_name" class="form-control" placeholder="fkn: PHP & MySQL" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Qabxii (Marks 100% irraa)</label>
                    <input type="number" name="marks" class="form-control" min="0" max="100" required>
                </div>
                <button type="submit" name="save_grade" class="btn btn-primary w-100">Save Grade</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>