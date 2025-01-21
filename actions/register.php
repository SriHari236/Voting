<!--
 <?php
// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $name = $_POST['name'];
//     $mobile = $_POST['mobile'];
//     $password = $_POST['password'];
//     $confirm_password = $_POST['confirm_password'];
//     $image = $_POST['photo'] ['name'];
//     $tmp_name = $_POST['photo'] ['tmp_name'];
//     $std = $_POST['address'];
//     $role = $_POST['role'];

//     // Check if passwords match
//     if ($password !== $confirm_password) {
//         die("Passwords do not match.");
//     }

//     // Handle file upload
//     $target_dir = "uploads/";
//     $image_name = basename($_FILES["image"]["name"]);
//     $target_file = $target_dir . $image_name;
//     if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
//         die("Error uploading image.");
//     }

//     // Database connection
//     $conn = new mysqli("localhost", "root", "", "voting_system");
//     if ($conn->connect_error) {
//         die("Connection failed: " . $conn->connect_error);
//     }

//     // Insert data into database
//     $sql = "INSERT INTO users (name, mobile, password, address, image, role) VALUES (?, ?, ?, ?, ?, ?)";
//     $stmt = $conn->prepare($sql);
//     $stmt->bind_param("ssssss", $name, $mobile, password_hash($password, PASSWORD_BCRYPT), $address, $image_name, $role);

//     if ($stmt->execute()) {
//         echo "Registration successful!";
//     } else {
//         echo "Error: " . $conn->error;
//     }

//     $stmt->close();
//     $conn->close();
// }
?>
 -->
 <?php 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $name = htmlspecialchars(trim($_POST['name']));
    $mobile = filter_var($_POST['mobile'], FILTER_SANITIZE_NUMBER_INT);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = htmlspecialchars(trim($_POST['address']));
    $role = htmlspecialchars(trim($_POST['role']));

    // Check if passwords match
    if ($password !== $confirm_password) {
        die("Passwords do not match.");
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Handle file upload securely
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true); // Create directory if it does not exist
    }

    $image_name = basename($_FILES["photo"]["name"]);
    $target_file = $target_dir . $image_name;

    // Validate uploaded file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = mime_content_type($_FILES["photo"]["tmp_name"]);

    if (!in_array($file_type, $allowed_types)) {
        die("Invalid file type. Only JPG, PNG, and GIF are allowed.");
    }

    // Move the uploaded file
    if (!move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file)) {
        die("Error uploading image.");
    }

    // Database connection
    $conn = new mysqli("localhost", "root", "", "voting_system");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Use prepared statements to insert data into the database
    $sql = "INSERT INTO users (name, mobile, password, address, image, role) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("ssssss", $name, $mobile, $hashed_password, $address, $image_name, $role);

    if ($stmt->execute()) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
