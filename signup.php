<?php include 'db.php'; ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $virtualNumber = 'VN' . rand(1000, 9999); // Generate unique virtual number

    $sql = "INSERT INTO users (username, password, virtual_number) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username, $password, $virtualNumber]);

    echo "<script>alert('Signup Successful! Virtual Number: $virtualNumber'); window.location='login.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <style>
        body { font-family: Arial; text-align: center; padding: 50px; }
        form { display: inline-block; text-align: left; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        input { margin: 10px 0; padding: 8px; width: 100%; }
        button { background: #007bff; color: white; padding: 10px; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <form method="POST">
        <h2>Sign Up</h2>
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Sign Up</button>
    </form>
</body>
</html>
