<?php

$conn = new mysqli("db", "student", "student123", "responsi");

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM students LIMIT 1");
$data = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Web 1</title>
</head>
<body>

<h1>WEB SERVER 1</h1>

<hr>

<p>
Nama Praktikan:
<strong><?= $data['nama'] ?></strong>
</p>

<p>
NIM:
<strong><?= $data['nim'] ?></strong>
</p>

<p>
Container:
<strong>WEB-1</strong>
</p>

</body>
</html>
