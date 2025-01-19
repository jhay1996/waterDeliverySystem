<?php
// Include database connection
include('db_connect.php');

$search = "";
if (isset($_POST['search'])) {
    $search = $_POST['search'];
    $sql = "SELECT id, ordernum, name, address, mobile, total_amount, order_items, image_data 
            FROM payment_details 
            WHERE name LIKE '%$search%' OR ordernum LIKE '%$search%'";
} else {
    $sql = "SELECT id, ordernum, name, address, mobile, total_amount, order_items, image_data FROM payment_details";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        .search-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .search-container input[type="text"] {
            padding: 10px;
            font-size: 16px;
            width: 300px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-container button {
            padding: 10px 20px;
            background-color: #FF6347;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .search-container button:hover {
            background-color: #e55342;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .payment-table th, .payment-table td {
            padding: 14px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .payment-table th {
            background-color: #FF6347;
            color: white;
            font-weight: bold;
        }

        .payment-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .payment-table tr:hover {
            background-color: #f1f1f1;
        }

        .payment-table img {
            max-width: 100px;
            max-height: 100px;
            object-fit: contain;
            cursor: pointer;
        }

       /* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
    width: 60%;
    height: 60%;
    overflow: auto;
    background-color: rgba(0,0,0,0.7);
    padding-top: 60px;
}

.modal-content {
    margin: auto;
    display: block;
    max-width: 100%;
    max-height: 100%;
    border-radius: 8px;
}

.close {
    position: absolute;
    top: 10px;
    right: 20px;
    color: white;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
}

.close:hover {
    color: #FF6347;
}

    </style>
</head>
<body>
    <div class="container">
        <h1>Payment Details</h1>

        <!-- Search Form -->
        <div class="search-container">
            <form method="POST" action="index.php?page=payments">
                <input type="text" name="search" placeholder="Search by name or order number" value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <!-- Payment Details Table -->
        <table class="payment-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order Number</th>
                    <th>Name</th>
                    <th>Address</th>
                    <th>Mobile</th>
                    <th>Total Amount</th>
                    <th>Order Items</th>
                    <th>Image</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Decode the 'order_items' column stored as JSON
                        $order_items = json_decode($row["order_items"], true);
                        $order_items_display = '';

                        foreach ($order_items as $item) {
                            $order_items_display .= '<strong>' . $item["name"] . '</strong> (Quantity: ' . $item["quantity"] . ', Price: ' . $item["price"] . ', Amount: ' . $item["amount"] . ')<br>';
                        }

                        // Construct full image URL
                        $image_url = "https://samplesytems.shop/backend/uploads/" . htmlspecialchars($row["image_data"]);

                        echo "<tr>
                                <td>" . $row["id"] . "</td>
                                <td>" . $row["ordernum"] . "</td>
                                <td>" . $row["name"] . "</td>
                                <td>" . $row["address"] . "</td>
                                <td>" . $row["mobile"] . "</td>
                                <td>" . $row["total_amount"] . "</td>
                                <td>" . $order_items_display . "</td>
                                <td>";
                        if (!empty($row["image_data"])) {
                            echo '<img src="' . $image_url . '" alt="Image" onclick="openModal(\'' . $image_url . '\')">';
                        } else {
                            echo 'No Image';
                        }
                        echo "</td></tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No data found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal for image preview -->
    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img class="modal-content" id="modalImage" src="">
    </div>

    <script>
        // Open the modal with the clicked image
        function openModal(imageUrl) {
            var modal = document.getElementById("imageModal");
            var modalImage = document.getElementById("modalImage");

            modal.style.display = "block";
            modalImage.src = imageUrl;
        }

        // Close the modal
        function closeModal() {
            var modal = document.getElementById("imageModal");
            modal.style.display = "none";
        }

        // Close the modal if clicked outside of the image
        window.onclick = function(event) {
            var modal = document.getElementById("imageModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>

    <?php
    // Close the connection
    $conn->close();
    ?>
</body>
</html>
