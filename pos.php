<?php
// This loggedInHeader should only be used for authenticated pages.
include 'src/templates/common/loggedInHeader.php';

// write your code below this line -------------
?>

    <div class="container">
        <div class="row mt-5">
            <div class="col m-auto">
                <h1>BookshoPOS</h1>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-7 border p-4">
                <form>
                    <div class="row">
                        <div class="col-7 mb-3">
                            <label for="product-search" class="form-label">Product Search</label>
                            <input class="form-control" type="text" id="product-search" placeholder="Search products">
                            <ul id="product-list" class="list-group mt-2" style="max-height: 250px; overflow-y: scroll; "></ul>
                            <input type="hidden" id="selected-product" value="" />
                            <input type="hidden" id="selected-product-price" value="" />
                        </div>
                        <div class="col-5 mb-3">
                            <div class="row">
                                <div class="col-7">
                                    <label for="product-qty" class="form-label">Quantity</label>
                                    <input class="form-control" type="text" id="product-qty" placeholder="Qty">
                                </div>
                                <div class="col-5 d-flex align-bottom">
                                    <button type="button" class="btn btn-outline-success w-100" id="add-to-order-btn">Add to order</button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="col-5 border p-4">
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-danger" id="clear-cart">Clear cart</button>
                </div>
                <?php $grandTotal = 0; ?>

                <ul id="cart-items-list" class="list-group mt-5">
                    <?php if(isset($_SESSION['products']) && count($_SESSION['products']) > 0) { ?>
                        <?php foreach($_SESSION['products'] as $product) { ?>
                            <?php
                                $grandTotal += $product['product_price'] * $product['qty'];
                                echo '<li class="list-group-item d-flex justify-content-between align-items-center">'
                                    . '<span>'
                                    . '<span>' . $product['product_name'] . ' (@ Rs. '. $product['product_price'] . ')</span>'
                                    . '<span> X ' . $product['qty'] . '</span>'
                                    . '</span>'
                                    . '<span>'
                                    . '<span>Rs. ' . $product['product_price'] * $product['qty'] . '</span>'
                                    . '<button class="remove-item ms-2 btn btn-danger" type="button" data-product-id="' . $product['product_id'] . '"> X </button>'
                                    . '</span>'
                                    . '</li>';
                            ?>
                        <?php } ?>
                    <?php } else { ?>
                        <?php echo '<li class="list-group-item d-flex justify-content-between">Your cart is empty</li>'; ?>
                    <?php } ?>
                </ul>
                <div class="d-flex justify-content-between mt-5 fs-2">
                    <span>Grand Total</span>
                    <div>
                        Rs.<span id="total-display"><?php echo $grandTotal ?></span>
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-5 fs-2">
                    <button type="button" id="place-order-btn" class="btn btn-primary w-100 py-3">Place order</button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php

                ?>
            </div>
        </div>
    </div>

    <script src="/dist/js/jquery-3.7.1.min.js"></script>
    <script src="/dist/js/pos.js"></script>
<?php

// Don't write below this line ------------------

include 'src/templates/common/footer.php';
?>