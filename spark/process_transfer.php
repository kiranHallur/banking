<?php
    // connect to the database
    $db_host = 'localhost';
    $db_user = 'root';
    $db_password = '';
    $db_name = 'banking';
    $conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

    // check connection
    if (!$conn) {
        die('Connection failed: ' . mysqli_connect_error());
    }

    // get the form data
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $amount = $_POST['amount'];

    // get the sender's and receiver's details
    $sql = "SELECT * FROM customers WHERE id = $sender_id";
    $result = mysqli_query($conn, $sql);
    $sender = mysqli_fetch_assoc($result);

    $sql = "SELECT * FROM customers WHERE id = $receiver_id";
    $result = mysqli_query($conn, $sql);
    $receiver = mysqli_fetch_assoc($result);

    // check if the sender has enough balance
    if ($sender['balance'] < $amount) {
        die('Transaction failed: Not enough balance');
    }

    // update the balances
    $sender_balance = $sender['balance'] - $amount;
    $receiver_balance = $receiver['balance'] + $amount;

    $sql = "UPDATE customers SET balance = $sender_balance WHERE id = $sender_id";
    mysqli_query($conn, $sql);

    $sql = "UPDATE customers SET balance = $receiver_balance WHERE id = $receiver_id";
    mysqli_query($conn, $sql);

    // insert the transaction into the transfers table
    $sql = "INSERT INTO transfers (sender_id, receiver_id, amount) VALUES ($sender_id, $receiver_id, $amount)";
    mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaction Success</title>
</head>
<body>
    <h1>Transaction Success</h1>
    <p>Amount transferred: <?php echo '$' . number_format($amount, 2); ?></p>
    <p>Sender: <?php echo $sender['name']; ?></p>
    <p>Receiver: <?php echo $receiver['name']; ?></p>
    <p>New balance for <?php echo $sender['name']; ?>: <?php echo '$' . number_format($sender_balance, 2); ?></p>
    <p>New balance for <?php echo $receiver['name']; ?>: <?php echo '$' . number_format($receiver_balance, 2); ?></p>
    <a href="index.php">Back to Home</a>

    <?php mysqli_close($conn); ?>
</body>
</html>
