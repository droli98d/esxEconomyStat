<?php
$servername = "127.0.0.1";
$username = "essentialmode";
$password = "password";
$dbname = "essentialmode";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Sum of money and bank from users table
$sql1 = "SELECT SUM(money) as total_money, SUM(bank) as total_bank FROM users";
$result1 = $conn->query($sql1);

if ($result1->num_rows > 0) {
    $row1 = $result1->fetch_assoc();
    if($row1["total_money"] != 0) $total_money = number_format($row1["total_money"]);
    if($row1["total_bank"] != 0) $total_bank = number_format($row1["total_bank"]);
    $total = number_format($row1["total_money"] + $row1["total_bank"]);
}

// Count of owned vehicles from owned_vehicles table
$sql2 = "SELECT COUNT(*) as total_vehicles FROM owned_vehicles";
$result2 = $conn->query($sql2);

if ($result2->num_rows > 0) {
    $row2 = $result2->fetch_assoc();
    if($row2["total_vehicles"] != 0) $total_vehicles = number_format($row2["total_vehicles"]);
}

echo "Total Money: " . $total_money . "<br>";
echo "Total Bank: " . $total_bank . "<br>";
echo "Total Vehicles: " . $total_vehicles . "<br>";
echo "Total: " . $total . "<br>";

// Check if it's been more than an hour
$sql_time = "SELECT date FROM statistics ORDER BY date DESC LIMIT 1";
$result_time = $conn->query($sql_time);
$last_record_time = NULL;
if ($result_time->num_rows > 0) {
    $row_time = $result_time->fetch_assoc();
    $last_record_time = $row_time['date'];
}
if($last_record_time == NULL || (time() - strtotime($last_record_time)) > 3600) {
    $sql = "INSERT INTO statistics (money, bank, vehicles) VALUES ('$total_money', '$total_bank', '$total_vehicles')";
    if ($conn->query($sql) === TRUE) {
        echo "Record created successfully";
    } else {
        echo "Error recording data: " . $conn->error;
    }
}

$conn->close();
?>