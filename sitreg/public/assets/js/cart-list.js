$(document).ready(function () {
  displayEmptyCartMessage();
  // Function to fetch product list
  window.getCartList = function () {
    var user_id = localStorage.getItem('user_id');
    var baseUrlLive = sessionStorage.getItem('baseUrlLive');

    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/cart-list',
      dataType: 'json',
      data: { user_id: user_id }, // Pass user_id here
      success: function (response) {
        if (response && response.data && response.data.length > 0) {
          console.log('Cart items found:', response.data);
          displayCartItem(response.data, response.totalAmount);
          hideShoppingBagIcons(response.data);
          hideShoppingBagIconsData(response.data);
          $('.cart_btn').show();
          // Hide total row and its contents
          $('.cart-table').show();
        } else {
          console.error('No cart items found');
          displayEmptyCartMessage();
          $('.cart_btn').hide();
        }
      },

      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
      }
    });
  };

  // Call the function to fetch cart items
  getCartList();

  // Function to display cart items
  function displayCartItem(cartItems, totalAmount) {
    var cartContent = $('.cr-cart-view .crcart-pro-items');
    cartContent.empty(); // Clear previous content

    $.each(cartItems, function (index, item) {
      var sizeHTML = item.product_size ? `<p>Size :<strong> ${item.product_size}</strong></p>` : '';
      var colorHTML = item.product_color ? `<p>Color :<strong> ${item.product_color}</strong></p>` : '';

      var cartItemHTML = `
            <li>
                <div class="crside_pro_img">
                    <img src="${item.productImage[0]}" alt="${item.product_name}">
                </div>
                <div class="cr-pro-content">
                       <span class="cart_pro_title">${item.product_name}</span>
                    <span class="cart-price"><span>$${item.product_sale_price}</span> x ${item.quantity}</span>
                    ${sizeHTML}
                    ${colorHTML}
                     <a href="javascript:void(0)" class="remove" data-cart-id="${item.id}">×</a>
                </div>
            </li>`;
      cartContent.append(cartItemHTML);
    });

    // Display total amount
    var cartTotal = $('.cr-cart-view .cart-table .text-right.primary-color');
    cartTotal.text('$' + totalAmount.toFixed(2)); // Use totalAmount from the parameter
  }

  // Function to display message when cart is empty
  function displayEmptyCartMessage() {
    var cartContent = $('.cr-cart-view .crcart-pro-items');
    cartContent.empty(); // Clear previous content

    var emptyCartMessage = `
        <li style="margin-bottom: 0px; padding-bottom: 400px;">
            <div style="text-align: center; margin-top: 180px;">
                <div id="lottie-no-product-cart" style="width: 300px; height: 300px; margin: 0 auto;"></div>
                <div style="font-size: 24px; color: #00438f !important;" class="text-center py-4">No product found</div>
            </div>
        </li>`;
    cartContent.append(emptyCartMessage);

    // Load the Lottie animation
    lottie.loadAnimation({
      container: document.getElementById('lottie-no-product-cart'), // The container for the animation
      renderer: 'svg', // The renderer to use
      loop: true, // Whether the animation should loop
      autoplay: true, // Whether the animation should start automatically
      path: '/assets/json/lottie.json' // The path to the Lottie file (you can also use a URL)
    });

    // Hide total row and its contents
    $('.cart-table').hide();

    // Hide cart-sub-total elements
    $('.cart-sub-total').hide();
  }

  // Event listener for remove button click
  $('.cr-cart-view').on('click', '.remove', function () {
    var cartId = $(this).data('cart-id');
    removeCartItem(cartId);
  });

  // Function to remove cart item
  function removeCartItem(cartId) {
    // Display SweetAlert confirmation dialog
    Swal.fire({
      title: 'Are you sure?',
      text: 'You are about to remove this item from your cart',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, remove it!'
    }).then(result => {
      if (result.isConfirmed) {
        var baseUrlLive = sessionStorage.getItem('baseUrlLive');

        $.ajax({
          type: 'POST',
          url: baseUrlLive + '/api/cart-remove',
          dataType: 'json',
          data: { cart_id: cartId },
          success: function (response) {
            // Handle success response
            console.log('Item removed from cart:', response);
            // Show success message using Toastify
            Toastify({
              text: 'Item removed from cart',
              backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
              close: true,
              duration: 3000
            }).showToast();
            fetchProductList();
            getCartList();
          },
          error: function (xhr, status, error) {
            // Handle error response
            console.error('AJAX error: ' + error);
          }
        });
      }
    });
  }
});

// Function to hide shopping bag icons for items in the cart
function hideShoppingBagIcons(cartItems) {
  $.each(cartItems, function (index, item) {
    $(`#shoppingBagIcon${item.product_id}`).hide();
  });
}

// Function to hide shopping bag icons for items in the cart
function hideShoppingBagIconsData(cartItems) {
  $.each(cartItems, function (index, item) {
    $(`#shoppingBagIconData${item.product_id}`).hide();
  });
}
