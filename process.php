<?php
require_once 'db.php';

// Student haaraa galmeessuu (Create)
if (isset($_POST['add_student'])) {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $course    = trim($_POST['course']);

    if (!empty($full_name) && !empty($email) && !empty($phone) && !empty($course)) {
        try {
            $sql = "INSERT INTO students (full_name, email, phone, course) VALUES (:full_name, :email, :phone, :course)";
            $stmt = $conn->prepare($sql);
            
            $stmt->execute([
                ':full_name' => $full_name,
                ':email'     => $email,
                ':phone'     => $phone,
                ':course'    => $course
            ]);

            // Yoo milkaa'e gara index.php deebi'a
            header("Location: index.php?status=success");
            exit();
        } catch (PDOException $e) {
            die("Error inserting data: " . $e->getMessage());
        }
    } else {
        header("Location: index.php?status=empty");
        exit();
    }
}
?>
<?php
require_once 'db.php';

// 1. Student Haaraa Galmeessuu (Create)
if (isset($_POST['add_student'])) {
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $course    = trim($_POST['course']);

    if (!empty($full_name) && !empty($email) && !empty($phone) && !empty($course)) {
        try {
            $sql = "INSERT INTO students (full_name, email, phone, course) VALUES (:full_name, :email, :phone, :course)";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                ':full_name' => $full_name,
                ':email'     => $email,
                ':phone'     => $phone,
                ':course'    => $course
            ]);

            header("Location: index.php?status=success");
            exit();
        } catch (PDOException $e) {
            die("Error inserting data: " . $e->getMessage());
        }
    }
}

// 2. Barataa Haquu (Delete)
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];

    try {
        $sql = "DELETE FROM students WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':id' => $id]);

        header("Location: index.php?status=deleted");
        exit();
    } catch (PDOException $e) {
        die("Error deleting data: " . $e->getMessage());
    }
}

// 3. Barataa Fooyyessuu (Update)
if (isset($_POST['update_student'])) {
    $id        = $_POST['student_id'];
    $full_name = trim($_POST['full_name']);
    $email     = trim($_POST['email']);
    $phone     = trim($_POST['phone']);
    $course    = trim($_POST['course']);

    try {
        $sql = "UPDATE students SET full_name = :full_name, email = :email, phone = :phone, course = :course WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':full_name' => $full_name,
            ':email'     => $email,
            ':phone'     => $phone,
            ':course'    => $course,
            ':id'        => $id
        ]);

        header("Location: index.php?status=updated");
        exit();
    } catch (PDOException $e) {
        die("Error updating data: " . $e->getMessage());
    }
}
?>