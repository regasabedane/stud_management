<?php
require_once 'db.php';

$message = "";

if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Password hash gochuu
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        try {
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute([
                ':username' => $username,
                ':password' => $hashed_password
            ]);

            $message = "<div class='alert alert-success'>User milkaa'inaan galmaa'era! Amma <a href='login.php'>Login</a> gochuu danda'ta.</div>";
        } catch (PDOException $e) {
            $message = "<div class='alert alert-danger'>Dogoggora: Maqaan kun koraan galmaa'eera ta'u danda'a!</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Usoo hin guutin hin dhiisin!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

<div class="card shadow-sm col-md-4">
    <div class="card-header bg-success text-white text-center">
        <h4>Create Account</h4>
    </div>
    <div class="card-body">
        <?= $message; ?>

        <form action="register.php" method="POST">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" required placeholder="Choose username">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Choose password">
            </div>
            <button type="submit" name="register" class="btn btn-success w-100 mb-2">Register</button>
            <a href="login.php" class="btn btn-link w-100 text-center">Already have an account? Login</a>
        </form>
    </div>
</div>

</body>
</html>