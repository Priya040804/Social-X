 <?php
$host = 'localhost';
$username = 'root';
$password = 'priyamysql';
$database = 'project';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function sanitize_data($data) {
    global $conn;
    return $conn->real_escape_string($data);
}

function create_user($first_name, $last_name, $username, $mobile_number, $email, $password, $gender, $age, $bio, $hobbies) {
    global $conn;

    // Insert into users table
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, username, mobile_number, email, password_hash, gender) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $first_name, $last_name, $username, $mobile_number, $email, $password, $gender);
    $stmt->execute();
    $user_id = $stmt->insert_id;
    $stmt->close();

    // Insert into profiles table
    $stmt = $conn->prepare("INSERT INTO profiles (user_id, age, gender, bio) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $age, $gender, $bio);
    $stmt->execute();
    $stmt->close();

    // Insert into complete_profiles table
    $stmt = $conn->prepare("INSERT INTO complete_profiles (user_id, age, gender, hobbies, bio) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $user_id, $age, $gender, $hobbies, $bio);
    $stmt->execute();
    $stmt->close();

    // Insert into user_groups table
    $stmt = $conn->prepare("INSERT INTO user_groups (user_id, hobby_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $hobbies);
    $stmt->execute();
    $stmt->close();

    echo "User created successfully";
}

// Example usage:
// create_user('John', 'Doe', 'john_doe', '1234567890', 'john@example.com', 'hashed_password', 'male', 25, 'I love coding and hiking.', 1);
// Adjust the parameters accordingly based on your needs.

$conn->close();
?>