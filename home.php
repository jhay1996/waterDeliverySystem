<?php
include 'admin/db_connect.php';
if (isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
    $recaptchaResponse = $_POST['g-recaptcha-response'];
    $secretKey = '6Lfy2bUqAAAAAHa5xGDFDzruP1_7UwUMfBoOTYBU';  // Replace with your secret key
    $remoteIp = $_SERVER['REMOTE_ADDR'];

    // Prepare the data for the POST request
    $data = [
        'secret' => $secretKey,
        'response' => $recaptchaResponse,
        'remoteip' => $remoteIp
    ];

    // Initialize cURL session
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

    // Execute cURL session and get the response
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response
    $responseKeys = json_decode($response, true);

    // Check if reCAPTCHA verification was successful
    if (isset($responseKeys['success']) && $responseKeys['success'] === true) {
        // Display success message via JavaScript alert
        echo '<script>alert("reCAPTCHA verification successful.");</script>';
    } else {
        $errorMessage = isset($responseKeys['error-codes']) ? implode(", ", $responseKeys['error-codes']) : 'Unknown error';
        // Display failure message via JavaScript alert
        echo '<script>alert("reCAPTCHA verification failed. Error: ' . $errorMessage . '. Please try again.");</script>';
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get order data from the form
    
    $name = $_POST['name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $status = 'To be Delivered';  // Default status
    $order_date = date('Y-m-d H:i:s');
    
    // Calculate total amount
    $total_amount = 0;
    if (isset($_POST['product_id']) && isset($_POST['qty'])) {
        $product_ids = $_POST['product_id'];
        $quantities = $_POST['qty'];

        foreach ($product_ids as $index => $product_id) {
            $qty = $quantities[$index];
            
            // Fetch the product price from the database
            $result = $conn->query("SELECT price FROM product_list WHERE id = $product_id");
            $product = $result->fetch_assoc();
            $price = $product['price'];
            
            // Calculate the total amount for this product
            $total_amount += $price * $qty;
        }
    }

    // Insert order into the new_orders table including the total_amount
    $stmt = $conn->prepare("INSERT INTO new_orders (Name, Address, Email, Mobile, Status, orderdate) VALUES ( ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die('MySQL prepare statement failed: ' . $conn->error);
    }
    $stmt->bind_param("ssssss", $name, $address, $email, $mobile, $status, $order_date);
    $stmt->execute();
    
    if ($stmt->affected_rows > 0) {
        $order_id = $stmt->insert_id; // Get the inserted order ID
        
        // Insert order items into order_items table
        foreach ($product_ids as $index => $product_id) {
            $qty = $quantities[$index];
            
            // Fetch the product price from the database
            $result = $conn->query("SELECT price FROM product_list WHERE id = $product_id");
            $product = $result->fetch_assoc();
            $price = $product['price'];
            
            // Calculate the amount for this product (price * quantity)
            $amount = $price * $qty;
            
            // Insert into order_items table, saving the amount for each product
            $stmt2 = $conn->prepare("INSERT INTO order_items (ordernum, product_id, quantity, amount) VALUES (?, ?, ?, ?)");
            if ($stmt2 === false) {
                die('MySQL prepare statement failed: ' . $conn->error);
            }
            
            // Bind the four parameters: order_id, product_id, qty, amount
            $stmt2->bind_param("iiii", $order_id, $product_id, $qty, $amount);
            $stmt2->execute();
        }
      
        echo '<div id="successMessage" style="background-color: green; color: white; padding: 10px; text-align: left;">';
        echo 'Order and items inserted successfully.';
        echo '</div>';
        
        echo '<script>
        setTimeout(function() {
            document.getElementById("successMessage").style.display = "none";
        }, 1000);
        </script>';
       
        
    } else {
        echo "Error inserting order.";
    }

    $stmt->close();
    $stmt2->close();
}
?>

<!-- Masthead --> 
<header class="masthead">
    <div class="container h-100">
        <div class="row h-100 align-items-center justify-content-center text-center">
            <div class="col-lg-10 align-self-center mb-4 page-title">
                <h1 class="text-white">Welcome to <?php echo $_SESSION['setting_name']; ?></h1>
                <hr class="divider my-4 bg-dark" />
                <!-- Change this to trigger modal -->
                <button class="btn btn-dark bg-black btn-xl" data-toggle="modal" data-target="#addOrderModal">Order Now</button>
            </div>
        </div>
    </div>
</header>

<section class="page-section" id="menu">
    <h1 class="text-center text-cursive" style="font-size:3em"><b>Menu</b></h1>
    <div class="d-flex justify-content-center">
        <hr class="border-dark" width="5%">
    </div>
    <div id="menu-field" class="card-deck mt-2 justify-content-center">
        <?php 
        $limit = 10;
        $page = (isset($_GET['_page']) && $_GET['_page'] > 0) ? $_GET['_page'] - 1 : 0 ;
        $offset = $page > 0 ? $page * $limit : 0;
        $all_menu = $conn->query("SELECT id FROM product_list")->num_rows;
        $page_btn_count = ceil($all_menu / $limit);
        $qry = $conn->query("SELECT * FROM product_list order by name asc Limit $limit OFFSET $offset ");
        while($row = $qry->fetch_assoc()):
        ?>
        <div class="col-lg-3 mb-3">
            <div class="card menu-item rounded-0">
                <div class="position-relative overflow-hidden" id="item-img-holder">
                    <img src="assets/img/<?php echo $row['img_path'] ?>" class="card-img-top" alt="...">
                </div>
                <div class="card-body rounded-0">
                    <h5 class="card-title"><?php echo $row['name'] ?></h5>
                    <p class="card-text truncate"><?php echo $row['description'] ?></p>
                    <p class="card-price text-dark"><strong>₱<?php echo number_format($row['price'], 2); ?></strong></p>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
    <div class="w-100 mx-4 d-flex justify-content-center">
        <div class="btn-group paginate-btns">
            <a class="btn btn-default border border-dark" <?php echo ($page == 0)? 'disabled' :'' ?> href="./?_page=<?php echo ($page) ?>">Prev.</a>
            <?php for($i = 1; $i <= $page_btn_count; $i++): ?>
            <a class="btn btn-default border border-dark <?php echo ($i == ($page + 1)) ? 'active' : ''; ?>" href="./?_page=<?php echo $i ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <a class="btn btn-default border border-dark" <?php echo (($page+1) == $page_btn_count)? 'disabled' :'' ?> href="./?_page=<?php echo ($page+2) ?>">Next</a>
        </div>
    </div>
</section>

<!-- Add Order Modal -->
<div class="modal fade" id="addOrderModal" tabindex="-1" aria-labelledby="addOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addOrderModalLabel">Add New Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Address</label>
                        <input type="text" class="form-control" id="address" name="address" required>
                    </div>
                    <button type="button" id="openMapBtn" class="btn btn-secondary mb-3">Open Map</button>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="mobile">Mobile</label>
                        <input type="text" class="form-control" id="mobile" name="mobile" required>
                    </div>
                    <hr>
                    <div id="orderItems">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="product">Product</label>
                                <select class="form-control" name="product_id[]" required>
                                    <?php
                                    // Fetch products from the database
                                    $products = $conn->query("SELECT * FROM product_list");
                                    while ($product = $products->fetch_assoc()) {
                                        echo "<option value='{$product['id']}' data-price='{$product['price']}'>";
                                        echo "{$product['name']} - ₱{$product['price']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="qty">Quantity</label>
                                <input type="number" class="form-control qty" name="qty[]" min="1" value="1" required>
                            </div>
                            <div class="col-md-3">
                                <label for="price">Price</label>
                                <input type="text" class="form-control itemPrice" readonly>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="addProductBtn" class="btn btn-secondary mb-3">Add Another Product</button>
                    <hr>
                    <div class="g-recaptcha" data-sitekey="6Lfy2bUqAAAAAKBdtSpX60N3DxydlatAFxf3FgfQ"></div>
                    <div class="form-group">
                        <label for="totalAmount">Total Amount</label>
                        <input type="text" id="totalAmount" class="form-control" name="total_amount" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Order</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Map Modal -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mapModalLabel">Select a Location</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map" style="height: 400px;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="selectLocationBtn" disabled>Select Location</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        let map, marker;

        // Function to dynamically add another product row
        $('#addProductBtn').click(function () {
            let newRow = ` 
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="product">Product</label>
                        <select class="form-control" name="product_id[]" required>
                            <?php
                            $products = $conn->query("SELECT * FROM product_list");
                            while ($product = $products->fetch_assoc()) {
                                echo "<option value='{$product['id']}' data-price='{$product['price']}'>";
                                echo "{$product['name']} - ₱{$product['price']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="qty">Quantity</label>
                        <input type="number" class="form-control qty" name="qty[]" min="1" value="1" required>
                    </div>
                    <div class="col-md-3">
                        <label for="price">Price</label>
                        <input type="text" class="form-control itemPrice" readonly>
                    </div>
                </div>`;  
            $('#orderItems').append(newRow);
        });

        // Calculate price and total amount when quantity changes
        $(document).on('change', '.qty', function () {
            let row = $(this).closest('.row');
            let price = row.find('select option:selected').data('price');
            let qty = $(this).val();
            let totalPrice = price * qty;
            row.find('.itemPrice').val('₱' + totalPrice.toFixed(2));
            calculateTotal();
        });

        // Calculate total amount
        function calculateTotal() {
            let total = 0;
            $('.itemPrice').each(function () {
                total += parseFloat($(this).val().replace('₱', '').replace(',', ''));
            });
            $('#totalAmount').val('₱' + total.toFixed(2));
        }

        // Open Google Maps modal
        $('#openMapBtn').click(function () {
            $('#mapModal').modal('show');
            initializeMap();
        });

        // Initialize Google Map
        function initializeMap() {
            const mapOptions = {
                center: { lat: 6.5000, lng: 124.7500 }, // Default center (Manila)
                zoom: 12
            };
            map = new google.maps.Map(document.getElementById('map'), mapOptions);

            google.maps.event.addListener(map, 'click', function (event) {
                placeMarker(event.latLng);
            });
        }

        // Place marker and update address field
        function placeMarker(location) {
            if (marker) {
                marker.setPosition(location);
            } else {
                marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
            }
            $('#selectLocationBtn').prop('disabled', false); // Enable button once pin is placed
            
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ 'location': location }, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $('#address').val(results[0].formatted_address);
                    }
                }
            });
        }

        // Select location and close map modal
        $('#selectLocationBtn').click(function () {
            $('#mapModal').modal('hide');
        });
    });
</script>

<!-- Include Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC70wVtKNui5s8L3xKPevA_NE8pYuh9XDk&callback=initializeMap" async defer></script>
<script src="https://www.google.com/recaptcha/api.js" async defer></script><!-- Add this style block or include it in your stylesheet -->
<style>
    .modal-body {
        max-height: 500px; /* Adjust as needed */
        overflow-y: auto;
    }
</style>
