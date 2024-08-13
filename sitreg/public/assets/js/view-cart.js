$(document).ready(function () {
  // Function to fetch product list
  function getCartView() {
    var baseUrlLive = sessionStorage.getItem('baseUrlLive');
    var user_id = localStorage.getItem('user_id');

    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/cart-list',
      dataType: 'json',
      data: { user_id: user_id }, // Pass user_id here
      success: function (response) {
        if (response && response.data && response.data.length > 0) {
          console.log('Cart items found:', response.data);
          displayCartView(response.data, response.totalAmount);
        } else {
          console.error('No cart items found');
          displayEmptyViewCartMessage();
        }
      },

      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
      }
    });
  }

  // Call the function to fetch cart items
  getCartView();

  // Function to display cart items
  function displayCartView(cartItems, totalAmount) {
    var cartContent = $('#cartview'); // Corrected selector
    cartContent.empty(); // Clear previous content

    $.each(cartItems, function (index, item) {
      var cartItemHTML = `
                <tr>
                    <td class="cr-cart-name">
                        <a href="javascript:void(0)">
                            <img src="${item.productImage[0]}" alt="${item.product_name}" class="cr-cart-img">
                            ${item.product_name}
                        </a>
                    </td>
                    <td class="cr-cart-price">
                        <span class="amount">
                            ${
                              item.product_sale_price
                                ? `<strike>$${item.product_price}</strike> $${item.product_sale_price}`
                                : `$${item.product_sale_price}`
                            }
                        </span>
                    </td>
                    <td class="cr-cart-qty">
                        <span class="amount">${item.quantity}</span>
                    </td>
                    <td class="cr-cart-size">
                        <span class="amount">${item.product_size}</span>
                    </td>
                    <td class="cr-cart-color">
                        <span class="amount">${item.product_color}</span>
                    </td>
                    <td class="cr-cart-subtotal">$${(item.product_sale_price * item.quantity).toFixed(2)}</td>
                    <td class="cr-cart-remove">
                      <a href="javascript:void(0)" class="remove" data-cart-id="${item.id}">
                            <i class="ri-delete-bin-line"></i>
                        </a>
                    </td>
                </tr>`;
      cartContent.append(cartItemHTML);
    });
  }

  // Function to display message when cart is empty
  function displayEmptyViewCartMessage() {
    var cartContent = $('#cartview'); // Corrected selector
    cartContent.empty(); // Clear previous content

    var emptyCartMessage = `
            <tr>
                <td colspan="7" class="text-center">
                    <p>Offo, Your cart is empty</p>
                </td>
            </tr>`;
    cartContent.append(emptyCartMessage);
  }

  // Event listener for remove button click
  $(document).on('click', '.remove', function () {
    var cartId = $(this).data('cart-id');
    removeCartViewItem(cartId);
  });

  // Function to remove cart item
  function removeCartViewItem(cartId) {
    var baseUrlLive = sessionStorage.getItem('baseUrlLive');

    // Display confirmation dialog using SweetAlert
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
        // If user confirms, proceed with deletion
        $.ajax({
          type: 'POST',
          url: baseUrlLive + '/api/cart-remove',
          dataType: 'json',
          data: { cart_id: cartId },
          success: function (response) {
            console.log('Item removed from cart:', response);
            // Show success message using Toastify
            Toastify({
              text: 'Item removed from cart',
              backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
              close: true,
              duration: 3000
            }).showToast();
            getCartView();
            fetchProductList();
          },
          error: function (xhr, status, error) {
            console.error('AJAX error: ' + error);
          }
        });
      }
    });
  }
});
