<?php
// Start the session
session_start();

// Include the database configuration file
include_once 'dbconfig.php';

// Fetch all customers from the database
$sql = "SELECT * FROM customers";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Banking System - View all Customers</title>
    <style>
             body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #003366;
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        nav {
            background-color: #F8F8F8;
            border: 1px solid #ccc;
            display: flex;
            justify-content: center;
            padding: 10px;
        }

        nav a {
            color: #000;
            margin: 0 10px;
            text-decoration: none;
        }

        main {
            display: flex;
            justify-content: center;
            margin: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #003366;
            color: #fff;
        }

        .success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
            margin-bottom: 20px;
            padding: 10px;
        }

        .error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            margin-bottom: 20px;
            padding: 10px;
        }

        a {
            color: #003366;
            text-decoration: none;
        }

        footer {
            background-color: #003366;
            color: #fff;
            padding: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <header>
        <h1>Banking System</h1>
    </header>

    <nav>
        <a href="index.php">Home</a>
        <a href="customers.php">View all Customers</a>
    </nav>

    <main>
    <h2>View all Customers</h2>
    <?php
    // Display a message if the session variable exists
    if (isset($_SESSION['message'])) {
        echo '<div class="' . $_SESSION['message_type'] . '">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Current Balance</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['current_balance']; ?></td>
                <td><a href="transfer.php?id=<?php echo $row['id']; ?>">Transfer Money</a></td>
            </tr>
        <?php } ?>
    </table>
</main>
<footer>
        <p>&copy; 2023 Banking System</p>
    </footer>
</body>
</html>
