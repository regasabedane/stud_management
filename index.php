<?php require_once 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Student Management System</h2>


    <!-- Alert Messages -->
<?php if (isset($_GET['status'])): ?>
    <?php if ($_GET['status'] == 'success'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Barataan milkaa'inaan galmaa'era!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($_GET['status'] == 'deleted'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Barataan haqameera!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif ($_GET['status'] == 'updated'): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            Odeeffannoon barataa fooyya'eera!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

    <div class="row">
        <!-- Form Barataa Galmeessu (Kuufe Tokko Qofa) -->
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Add New Student</h5>
                </div>
                <div class="card-body">
                    <form action="process.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required placeholder="Abebe Balcha">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required placeholder="abebe@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control" required placeholder="0911223344">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Course</label>
                            <input type="text" name="course" class="form-control" required placeholder="Web Development">
                        </div>
                        <button type="submit" name="add_student" class="btn btn-primary w-100">Save Student</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Barattoota Agarsiisu -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0">Student List</h5>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $conn->query("SELECT * FROM students ORDER BY id DESC");
                            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            if (count($students) > 0):
                                foreach ($students as $student):
                            ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['id']); ?></td>
                                    <td><?= htmlspecialchars($student['full_name']); ?></td>
                                    <td><?= htmlspecialchars($student['email']); ?></td>
                                    <td><?= htmlspecialchars($student['phone']); ?></td>
                                    <td><?= htmlspecialchars($student['course']); ?></td>
                                    <td>
                                        <a href="edit.php?id=<?= $student['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="process.php?delete_id=<?= $student['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Barataa kana haquu ni barbaaddaa?')">Delete</a>
                                    </td>
                                </tr>
                            <?php 
                                endforeach;
                            else: 
                            ?>
                                <tr>
                                    <td colspan="6" class="text-center">Barataan tokkollee hin galmaagnee</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>