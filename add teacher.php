<?php
session_start();
require_once 'db.php';

// Admin qofatu barsiisaa galmeessuu danda'a
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$message = "";

if (isset($_POST['save_teacher'])) {
    $full_name  = trim($_POST['full_name']);
    $email      = trim($_POST['email']);
    $phone      = trim($_POST['phone']);
    $department = trim($_POST['department']);

    if (!empty($full_name) && !empty($email) && !empty($department)) {
        try {
            $stmt = $conn->prepare("INSERT INTO teachers (full_name, email, phone, department) VALUES (:full_name, :email, :phone, :department)");
            $stmt->execute([
                ':full_name'  => $full_name,
                ':email'      => $email,
                ':phone'      => $phone,
                ':department' => $department
            ]);
            $message = "<div class='alert alert-success'>Barsiisaan milkaa'inaan galmaa'era!</div>";
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>Dogoggora: Email kun koraan galmaa'eera!</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Usoo hin guutin hin dhiisin!</div>";
    }
}

// Barsiisota jiran fiduu
$teachers = $conn->query("SELECT * FROM teachers ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Teachers - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Teacher Management</h2>
        <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>

    <?= $message; ?>

    <div class="row">
        <!-- Form Add Teacher -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Barsiisaa Haaraa Galmeessi</h5>
                </div>
                <div class="card-body">
                    <form action="add_teacher.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Department (Mummaddii)</label>
                            <input type="text" name="department" class="form-control" placeholder="fkn: Computer Science" required>
                        </div>
                        <button type="submit" name="save_teacher" class="btn btn-primary w-100">Save Teacher</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Teachers List -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Tarree Barsiisotaa</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Department</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($teachers) > 0): ?>
                                <?php foreach ($teachers as $t): ?>
                                    <tr>
                                        <td><?= $t['id']; ?></td>
                                        <td><?= htmlspecialchars($t['full_name']); ?></td>
                                        <td><?= htmlspecialchars($t['email']); ?></td>
                                        <td><?= htmlspecialchars($t['department']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center">Barsiisaan galmaa'e hin jiru.</td>
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