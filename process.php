<?php
$conn = new mysqli("localhost", "root", "", "mydb");
if ($conn->connect_error) {
    die("Connection failed!" . $conn->connect_error);
}

if (isset($_GET['action'])) {
    $action = $_GET['action'];
}

if ($action == 'read') {
    $sql = $conn->query("SELECT * FROM users");
    $users = array();
    while ($row = $sql->fetch_assoc()) {
        array_push($users, $row);
    }
    $result['users'] = $users;
}

if ($action == 'create') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT email FROM users WHERE email = ?";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
        $result['error'] = true;
        $result['message'] = "erro 1";
    } else {
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $rowCount = mysqli_stmt_num_rows($stmt);
        if ($rowCount > 0) {
            $result['error'] = true;
            $result['message'] = "duplicado";
        } else {
            $sql = "INSERT INTO users (name, email, password) VALUES(?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                $result['error'] = true;
                $result['message'] = "erro 3";
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                mysqli_stmt_bind_param($stmt, "sss", $name, $email, $hash);
                mysqli_stmt_execute($stmt);
                $result['message'] = "User added successfully!";
            }
        }
        mysqli_stmt_close($stmt);
    }
}

if ($action == 'update') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $sql = $conn->query("UPDATE users set name='$name', email='$email' WHERE id='$id'");

    if ($sql) {
        $result['message'] = "User updated successfully!";
    } else {
        $result['error'] = true;
        $result['message'] = "Failed to update user";
    }
}

if ($action == 'delete') {
    $id = $_POST['id'];
    $sql = $conn->query("DELETE FROM users WHERE id='$id'");

    if ($sql) {
        $result['message'] = "User deleted successfully!";
    } else {
        $result['error'] = true;
        $result['message'] = "Failed to delete user";
    }
}

$conn->close();
echo json_encode($result);
