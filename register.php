<?php
// DB connection
$host = 'localhost';
$user = 'root';
$password = "";
$dbname = 'learning';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $residence = trim($_POST['residence'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    // Basic validation
    if (empty($fullname) || empty($phone) || empty($residence) || empty($password) || empty($confirmPassword)) {
        $message = "Please fill in all required fields.";
    } elseif ($password !== $confirmPassword) {
        $message = "Passwords do not match.";
    } elseif (!preg_match('/^\+254\d{9}$/', $phone)) {
        $message = "Invalid phone number format.";
    } else {
        // Check if phone already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE phone = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Phone number already registered.";
        } else {
            // Hash password
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Set default role and status
            $role = 'student';
            $status = 'pending';

            // Insert user
            $stmt = $conn->prepare("INSERT INTO users (fullname, phone, residence, password_hash, role, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $fullname, $phone, $residence, $password_hash, $role, $status);

            if ($stmt->execute()) {
                $message = "Registration successful. Wait for approval.";
            } else {
                $message = "Error: " . $conn->error;
            }
        }

        $stmt->close();
    }
}

$conn->close();

// Output message as JavaScript alert if needed
if (!empty($message)) {
    echo "<script>alert('$message'); window.location.href='register.html';</script>";
    exit();
}
?>
