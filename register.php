<?php
require_once 'db.php';

$message = "";

if (isset($_POST['register'])) {
    $username   = trim($_POST['username']);
    $password   = trim($_POST['password']);
    $student_id = $_POST['student_id']; // Dropdown irraa kan filatamu

    if (!empty($username) && !empty($password) && !empty($student_id)) {
        // Password hash gochuu
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            // KOODII KANAS ASITTI FAYYADAMNA:
            $stmt = $conn->prepare("INSERT INTO users (username, password, role, student_id) VALUES (:username, :password, 'student', :student_id)");
            $stmt->execute([
                ':username'   => $username,
                ':password'   => $hashed_password,
                ':student_id' => $student_id
            ]);

            $message = "<div class='alert alert-success'>Account Barataa milkaa'inaan galmaa'era! Amma <a href='login.php'>Login</a> gochuu danda'ta.</div>";
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>Dogoggora: Maqaan kun koraan galmaa'eera ykn dhibee biraatu jira!</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Odeeffannoo hunda guutuu mirkaneessaa!</div>";
    }
}

// Barattoota table 'students' keessa jiran fiduu (Dropdown tiif)
$students = $conn->query("SELECT id, full_name FROM students")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Student Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card shadow-sm col-md-5">
    <div class="card-header bg-success text-white text-center">
        <h4>Create Student Account</h4>
    </div>
    <div class="card-body">
        <?= $message; ?>

        <form action="register.php" method="POST" autocomplete="off">
            <div class="mb-3">
                <label class="form-label">Select Your Student Name (Maqaa Kee Filadhu)</label>
                <select name="student_id" class="form-select" required>
                    <option value="">-- Barataa Filadhu --</option>
                    <?php foreach ($students as $s): ?>
                        <option value="<?= $s['id']; ?>"><?= htmlspecialchars($s['full_name']); ?> (ID: <?= $s['id']; ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Choose username" autocomplete="off">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Choose password" autocomplete="new-password">
            </div>
            <button type="submit" name="register" class="btn btn-success w-100 mb-2">Register Account</button>
            <a href="login.php" class="btn btn-link w-100 text-center">Already have an account? Login</a>
        </form>
    </div>
</div>

</body>
</html>