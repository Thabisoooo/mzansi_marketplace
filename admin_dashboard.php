<?php
session_start();
require_once 'includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// Get basic stats
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalBuyers = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'buyer'")->fetch_assoc()['count'];
$totalSellers = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'seller'")->fetch_assoc()['count'];
$totalProducts = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$totalTrades = $conn->query("SELECT COUNT(*) as count FROM trades")->fetch_assoc()['count'];
$pendingTrades = $conn->query("SELECT COUNT(*) as count FROM trades WHERE status = 'pending'")->fetch_assoc()['count'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Mzansi Marketplace</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
            background-color: #f2f2f2;
        }
        .dashboard {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px #ccc;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        .card {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 6px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .card h2 {
            font-size: 32px;
            margin: 0;
            color: #007bff;
        }
        .card p {
            margin: 5px 0 0;
            color: #555;
            font-weight: bold;
        }
        .logout {
            text-align: center;
            margin-top: 40px;
        }
        .logout a {
            background: #e74c3c;
            color: white;
            text-decoration: none;
            padding: 10px 25px;
            border-radius: 5px;
            font-weight: bold;
        }
        .logout a:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>

<div class="dashboard">
    <h1>Admin Dashboard</h1>

    <div class="stats">
        <div class="card">
            <h2><?= $totalUsers ?></h2>
            <p>Total Users</p>
        </div>
        <div class="card">
            <h2><?= $totalBuyers ?></h2>
            <p>Total Buyers</p>
        </div>
        <div class="card">
            <h2><?= $totalSellers ?></h2>
            <p>Total Sellers</p>
        </div>
        <div class="card">
            <h2><?= $totalProducts ?></h2>
            <p>Total Products</p>
        </div>
        <div class="card">
            <h2><?= $totalTrades ?></h2>
            <p>Total Trades</p>
        </div>
        <div class="card">
            <h2><?= $pendingTrades ?></h2>
            <p>Pending Trades</p>
        </div>
    </div>

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</div>

</body>
</html>
