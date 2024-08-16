$(document).ready(function() {
    $(document).on('click', '.remove-item', function() {
        if (confirm('Are you sure you want to remove this?')) {
            let productId = $(this).data('product-id');

            $.ajax({
                url: window.baseUrl+"src/pos/remove-item.php",
                method: "POST",
                data: { del_confirm: true, product_id: productId },
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        let cartItemsList = $('#cart-items-list');
                        let cartTotal = 0;
                        cartItemsList.empty();
                        if (typeof data.cart_items !== 'undefined' && Object.keys(data.cart_items).length > 0) {
                            $.each(data.cart_items, function(index, cartItem) {
                                let rowTotal = cartItem.product_price * cartItem.qty;
                                cartTotal += rowTotal;
                                cartItemsList.append('<li class="list-group-item d-flex justify-content-between">'
                                    + '<span>'
                                    + '<span>' + cartItem.product_name + ' (@ Rs. '+ cartItem.product_price + ')</span>'
                                    + '<span> X ' + cartItem.qty + '</span>'
                                    + '</span>'
                                    + '<span>'
                                    + '<span>Rs. ' + rowTotal + '</span>'
                                    + '<button class="remove-item ms-2 btn btn-danger" type="button" data-product-id="' + cartItem.product_id + '"> X </button>'
                                    + '</span>'
                                    + '</li>');
                            });
                        }
                        let totalDisplay = $('#total-display');
                        totalDisplay.html(cartTotal);
                        alert(data.message);
                    } else {
                        alert(data.message);
                    }
                }
            });
        }
    })

    $('#place-order-btn').on('click', function() {
        if (confirm('Are you sure you want to proceed?')) {
            let grandTotal = $('#total-display').text();
            debugger
            $.ajax({
                url: window.baseUrl+"src/pos/place-order.php",
                method: "POST",
                data: {
                    place_order: true,
                    grand_total: grandTotal
                },
                dataType: "json",
                success: function (data) {
                    if (data.status == 'success') {
                        alert(data.message);
                        window.location.href = window.baseUrl + 'src/pos/bill-print.php?o_id=' + data.orderId;
                    } else {
                        alert(data.message);
                    }
                }
            });
        }
    })

    $('#clear-cart').on('click', function() {
        if (confirm('Are you sure you want to clear the cart?')) {
            $.ajax({
                url: window.baseUrl+"src/pos/clear-cart.php",
                method: "POST",
                data: { del_confirm: true },
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        let cartItemsList = $('#cart-items-list');
                        cartItemsList.empty();
                        alert(data.message)
                    } else {
                        alert(data.message);
                    }
                }
            });
        }
    })

    $('#add-to-order-btn').on('click', function() {
        let prodField = $('#selected-product');
        let prodPriceField = $('#selected-product-price');
        let searchField = $('#product-search');
        let qtyField = $('#product-qty');
        let productId = prodField.val();
        let productPrice = prodPriceField.val();
        let productName = searchField.val();
        let qty = qtyField.val();

        if (productId && qty) {
            $.ajax({
                url: window.baseUrl+"src/pos/add-to-order.php",
                method: "POST",
                data: {
                    product_id: productId,
                    product_price: productPrice,
                    qty: qty,
                    product_name: productName
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == 'success') {
                        let cartItemsList = $('#cart-items-list');
                        let cartTotal = 0;
                        cartItemsList.empty();
                        if (typeof data.cart_items !== 'undefined' && Object.keys(data.cart_items).length > 0) {
                            $.each(data.cart_items, function(index, cartItem) {
                                let rowTotal = cartItem.product_price * cartItem.qty;
                                cartTotal += rowTotal;
                                cartItemsList.append('<li class="list-group-item d-flex justify-content-between">'
                                    + '<span>'
                                    + '<span>' + cartItem.product_name + ' (@ Rs. '+ cartItem.product_price + ')</span>'
                                    + '<span> X ' + cartItem.qty + '</span>'
                                    + '</span>'
                                    + '<span>'
                                    + '<span>Rs. ' + rowTotal + '</span>'
                                    + '<button class="remove-item ms-2 btn btn-danger" type="button" data-product-id="' + cartItem.product_id + '"> X </button>'
                                    + '</span>'
                                    + '</li>');
                            });
                        }
                        let totalDisplay = $('#total-display');
                        totalDisplay.html(cartTotal);
                        searchField.val('');
                        prodField.val('');
                        prodPriceField.val('');
                        qtyField.val('')
                    } else {
                        alert(data.message);
                    }
                }
            });
        } else {
            alert('Please select a product or add a valid quantity value');
        }
    })

    $('#product-search').on('keyup', function() {
        $('#selected-product').val('');

        let query = $(this).val();

        if (query !== '') {
            $.ajax({
                url: window.baseUrl+"src/pos/search-products.php",
                method: "GET",
                data: { query: query },
                dataType: "json",
                success: function(data) {
                    let productList = $('#product-list');
                    productList.empty();

                    if (data.length > 0) {
                        $.each(data, function(index, product) {
                            productList.append('<li class="list-group-item search-suggestions" data-product-id="' + product.id + '" data-product-price="' + product.product_price + '">' + product.product_name + '</li>');
                        });
                    } else {
                        productList.append('<li class="list-group-item">No products found</li>');
                    }
                }
            });
        } else {
            $('#product-list').empty();
        }
    });

    // Optional: Handle item selection (e.g., clicking on a suggestion)
    $(document).on('click', '.search-suggestions', function() {
        $('#product-search').val($(this).text());
        $('#selected-product').val($(this).data('product-id'));
        $('#selected-product-price').val($(this).data('product-price'));
        $('#product-list').empty();
    });
});
