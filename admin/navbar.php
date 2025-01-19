<nav id="sidebar" class="mx-lt-5 bg-dark">
    <div class="sidebar-list">
        <a href="index.php?page=home" class="nav-item nav-home"><span class="icon-field"><i class="fa fa-house-user"></i></span> Home</a>
        <a href="index.php?page=orders" class="nav-item nav-orders"><span class="icon-field"><i class="fa fa-receipt"></i></span> Orders</a>
        <a href="index.php?page=categories" class="nav-item nav-categories"><span class="icon-field"><i class="fa fa-th-list"></i></span> Category List</a>

        <!-- Water Products link with hidden options inside it -->
        <a href="javascript:void(0);" class="nav-item nav-water_products" onclick="toggleVisibility('waterProductsOptions')" aria-expanded="false">
            <span class="icon-field"><i class="fa fa-water"></i></span> Water Products
        </a>
        <div id="waterProductsOptions" class="nav-options">
            <a href="index.php?page=menu" class="nav-item nav-add_water_product"><span class="icon-field"><i class="fa fa-plus"></i></span> Add Water Product</a>
        </div>

        <!-- Gallons link with hidden options -->
        <a href="javascript:void(0);" class="nav-item nav-gallons" onclick="toggleVisibility('gallonsOptions')" aria-expanded="false">
    <span class="icon-field"><i class="fa fa-tint"></i></span> Gallons
</a>
<div id="gallonsOptions" class="nav-options">
    <a href="index.php?page=add_gallons" class="nav-item nav-add_gallons"><span class="icon-field"><i class="fa fa-plus"></i></span> Add Gallons</a>
    <a href="index.php?page=borrow_gallons" class="nav-item nav-borrow_gallons"><span class="icon-field"><i class="fa fa-hand-holding"></i></span> Rent</a>
</div>



        <!-- Inventory link with hidden options -->
        <a href="javascript:void(0);" class="nav-item nav-inventory" onclick="toggleVisibility('inventoryOptions')" aria-expanded="false">
            <span class="icon-field"><i class="fa fa-box"></i></span> Inventory
        </a>
        <div id="inventoryOptions" class="nav-options">
            <a href="index.php?page=products" class="nav-item nav-products"><span class="icon-field"><i class="fa fa-cogs"></i></span> Products</a>
            <a href="index.php?page=inventory_gallons" class="nav-item nav-inventory_gallons"><span class="icon-field"><i class="fa fa-dolly"></i></span> Gallons</a>
        </div>

        <!-- Reports link with hidden options -->
        <a href="javascript:void(0);" class="nav-item nav-reports" onclick="toggleVisibility('reportsOptions')" aria-expanded="false">
            <span class="icon-field"><i class="fa fa-chart-bar"></i></span> Reports
        </a>
        <div id="reportsOptions" class="nav-options">
            <a href="index.php?page=sales_report" class="nav-item nav-sales_report"><span class="icon-field"><i class="fa fa-file-invoice"></i></span> Sales Report</a>
            <a href="index.php?page=inventory_report" class="nav-item nav-inventory_report"><span class="icon-field"><i class="fa fa-warehouse"></i></span> Inventory Report</a>
            <a href="index.php?page=orders_report" class="nav-item nav-orders_report"><span class="icon-field"><i class="fa fa-receipt"></i></span> Orders Report</a>
        </div>

        <a href="index.php?page=payments" class="nav-item nav-payments"><span class="icon-field"><i class="fa fa-money-bill-wave"></i></span> Payments</a>
        
        <?php if ($_SESSION['login_type'] == 1): ?>
        <a href="index.php?page=users" class="nav-item nav-users"><span class="icon-field"><i class="fa fa-user-cog"></i></span> Users</a>
        <a href="index.php?page=site_settings" class="nav-item nav-site_settings"><span class="icon-field"><i class="fa fa-tools"></i></span> Display Settings</a>
        <?php endif; ?>
    </div>
</nav>

<script>
    // Function to toggle the visibility of options
    function toggleVisibility(id) {
        var options = document.getElementById(id);
        var expanded = options.style.display === "block";
        options.style.display = expanded ? "none" : "block";
        event.target.setAttribute("aria-expanded", !expanded);
    }

    // Highlight the active page in the sidebar
    $('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active');
</script>

<style>
    /* General styles for nav items */
    .nav-item {
        padding: 10px;
        color: #ffffff;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s, color 0.3s;
    }

    .nav-item:hover {
        background-color: #007bff;
        color: white;
    }

    /* Styling for options inside collapsible menus */
    .nav-options {
        padding-left: 20px;
        display: none;
    }

    /* Custom styles for nested options */
    .nav-item.nav-add_water_product,
    .nav-item.nav-add_gallons,
    .nav-item.nav-sales_report,
    .nav-item.nav-inventory_report,
    .nav-item.nav-orders_report {
        padding: 8px 10px;
        color: #f8f9fa;
    }

    .nav-item.nav-add_water_product:hover,
    .nav-item.nav-add_gallons:hover,
    .nav-item.nav-sales_report:hover,
    .nav-item.nav-inventory_report:hover,
    .nav-item.nav-orders_report:hover {
        background-color: #007bff;
        color: white;
    }

    .icon-field {
        margin-right: 8px;
    }
</style>
