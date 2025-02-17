<?php include 'db.php'; session_start(); ?>
<?php
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->execute([$username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Handle Message Sending
if (isset($_POST['send_message'])) {
    $receiver = $_POST['receiver_number'];
    $message = $_POST['message'];

    $sql = "INSERT INTO messages (sender_number, receiver_number, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user['virtual_number'], $receiver, $message]);
}

// Handle Call Simulation
if (isset($_POST['make_call'])) {
    $receiver = $_POST['receiver_number'];

    $sql = "INSERT INTO calls (caller_number, receiver_number, call_status) VALUES (?, ?, 'Outgoing')";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$user['virtual_number'], $receiver]);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
        }
        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.5em;
        }
        main {
            padding: 20px;
            text-align: center;
        }
        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin: auto;
            width: 300px;
        }
        form input, form textarea, form select, form button {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }
        form button {
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        form button:hover {
            background-color: #218838;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table th {
            background-color: #007bff;
            color: white;
        }
        .actions button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 5px;
        }
        .actions button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <h1>Welcome, <?= htmlspecialchars($user['username']) ?></h1>
    </header>
    <main>
        <p>Your Virtual Number: <strong><?= $user['virtual_number'] ?></strong></p>

        <section>
            <h2>Send a Message</h2>
            <form method="POST">
                <input type="text" name="receiver_number" placeholder="Receiver's Number" required>
                <textarea name="message" placeholder="Type your message here" required></textarea>
                <button type="submit" name="send_message">Send Message</button>
            </form>
        </section>

        <section>
            <h2>Make a Call</h2>
            <form method="POST">
                <input type="text" name="receiver_number" placeholder="Receiver's Number" required>
                <button type="submit" name="make_call">Call</button>
            </form>
        </section>

        <section>
            <h2>Your Messages</h2>
            <table>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th>Message</th>
                    <th>Time</th>
                </tr>
                <?php
                $sql = "SELECT * FROM messages WHERE sender_number = ? OR receiver_number = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$user['virtual_number'], $user['virtual_number']]);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['sender_number']}</td>
                            <td>{$row['receiver_number']}</td>
                            <td>{$row['message']}</td>
                            <td>{$row['created_at']}</td>
                          </tr>";
                }
                ?>
            </table>
        </section>

        <section>
            <h2>Call Logs</h2>
            <table>
                <tr>
                    <th>Caller</th>
                    <th>Receiver</th>
                    <th>Status</th>
                    <th>Time</th>
                </tr>
                <?php
                $sql = "SELECT * FROM calls WHERE caller_number = ? OR receiver_number = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$user['virtual_number'], $user['virtual_number']]);
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>
                            <td>{$row['caller_number']}</td>
                            <td>{$row['receiver_number']}</td>
                            <td>{$row['call_status']}</td>
                            <td>{$row['created_at']}</td>
                          </tr>";
                }
                ?>
            </table>
        </section>
    </main>
</body>
</html>
