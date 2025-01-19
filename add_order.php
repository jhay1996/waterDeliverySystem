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

                    <!-- Button to add another product -->
                    <button type="button" id="addProductBtn" class="btn btn-info btn-sm">Add Another Product</button>

                    <hr>
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
