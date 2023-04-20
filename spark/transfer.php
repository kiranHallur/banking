<?php
// Start the session
session_start();

// Include the database configuration file
include 'dbconfig.php';

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the sender's and receiver's IDs and the amount to transfer
    $sender_id = mysqli_real_escape_string($conn, $_POST['sender_id']);
    $receiver_id = mysqli_real_escape_string($conn, $_POST['receiver_id']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);

    // Check if the sender and receiver are different
    if ($sender_id == $receiver_id) {
        $_SESSION['message'] = 'Sender and receiver cannot be the same!';
        $_SESSION['message_type'] = 'error';
        header('Location: transfer.php?id=' . $sender_id);
        exit;
    }

    // Check if the sender has enough balance to transfer
    $sender_query = mysqli_query($conn, "SELECT current_balance FROM customers WHERE id=$sender_id");
    $sender_row = mysqli_fetch_assoc($sender_query);
    $sender_balance = $sender_row['current_balance'];

    if ($amount > $sender_balance) {
        $_SESSION['message'] = 'Insufficient balance to transfer!';
        $_SESSION['message_type'] = 'error';
        header('Location: transfer.php?id=' . $sender_id);
        exit;
    }

    // Perform the transfer
    $receiver_query = mysqli_query($conn, "SELECT current_balance FROM customers WHERE id=$receiver_id");
    $receiver_row = mysqli_fetch_assoc($receiver_query);
    $receiver_balance = $receiver_row['current_balance'];

    $new_sender_balance = $sender_balance - $amount;
    $new_receiver_balance = $receiver_balance + $amount;

    mysqli_query($conn, "UPDATE customers SET current_balance=$new_sender_balance WHERE id=$sender_id");
    mysqli_query($conn, "UPDATE customers SET current_balance=$new_receiver_balance WHERE id=$receiver_id");

    // Record the transfer in the transfers table
    $timestamp = date('Y-m-d H:i:s');
    mysqli_query($conn, "INSERT INTO transfers (sender_id, receiver_id, amount, date_time) VALUES ($sender_id, $receiver_id, $amount, '$timestamp')");

    $_SESSION['message'] = 'Transfer successful!';
    $_SESSION['message_type'] = 'success';
    header('Location: customers.php');
    exit;
}

// Check if the ID parameter is set in the URL
if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);

    // Get the sender's details
    $sender_query = mysqli_query($conn, "SELECT * FROM customers WHERE id=$id");
    $sender = mysqli_fetch_assoc($sender_query);

    // Get all the customers' details except the sender's
    $customers_query = mysqli_query($conn, "SELECT * FROM customers WHERE id!=$id");
} else {
    $_SESSION['message'] = 'Invalid request!';
    $_SESSION['message_type'] = 'error';
    header('Location: customers.php');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transfer Money</title>
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

    h1 {
        margin: 0;
        font-size: 36px;
    }

    .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
    }

    form {
        background-color: #f2f2f2;
        padding: 20px;
        border-radius: 5px;
    }

    input[type="submit"] {
        background-color: #003366;
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    label, input {
        display: block;
        margin-bottom: 10px;
    }

    input[type="text"], select {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        box-sizing: border-box;
    }

    .success {
        background-color: #4CAF50;
        color: #fff;
        padding: 10px;
        margin-bottom: 20px;
    }

    .error {
        background-color: #f44336;
        color: #fff;
        padding: 10px;
        margin-bottom: 20px;
    }
</style>
</head>
<body>
    <header>
        <h1>Transfer Money</h1>
    </header>
    <main>
        <h2>Transfer from <?php echo $sender['name']; ?></h2>
        <form method="post">
            <label for="receiver">Transfer to:</label>
            <select name="receiver_id" id="receiver">
                <?php while ($customer = mysqli_fetch_assoc($customers_query)) { ?>
                    <option value="<?php echo $customer['id']; ?>"><?php echo $customer['name']; ?></option>
                <?php } ?>
            </select>
            <br><br>
            <label for="amount">Amount:</label>
            <input type="number" name="amount" id="amount" min="0" max="<?php echo $sender['current_balance']; ?>">
            <br><br>
            <input type="hidden" name="sender_id" value="<?php echo $sender['id']; ?>">
            <input type="submit" value="Transfer">
        </form>
    </main>
</body>
</html>
       
