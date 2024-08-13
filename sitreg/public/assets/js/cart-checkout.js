// Fetch cart items and display them
function getCartCheckoutList() {
  var user_id = localStorage.getItem('user_id');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/get-checkout',
    dataType: 'json',
    data: { user_id: user_id }, // Pass user_id here
    success: function (response) {
      if (response && response.data && response.data.length > 0) {
        console.log('Cart Checkout found:', response.data);
        displayCartCheckoutList(response.data, response.totalAmount);
        displayCartAddress(response.Address);
        displayCouponCode(response.data[0].coupon_code);
        displayOrderSummary(response.bagTotal, response.coupons, response.totalAmount);
        getCartInnerCheckoutList();
      } else {
        console.error('No cart items found');
        displayEmptyViewCartMessage();
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
      displayEmptyViewCartMessage();
    }
  });
}

function displayCartCheckoutList(cartItems, totalAmount) {
  var cartContent = $('#cartCheckoutList');
  cartContent.empty(); // Clear previous content

  var subTotalAmount = 0;

  // Iterate through each item in the cart
  $.each(cartItems, function (index, item) {
    // Calculate subtotal for each item
    var itemSubTotal = item.product_sale_price * item.quantity;
    subTotalAmount += itemSubTotal;

    let productDetails = '';
    if (item.product_color && item.product_size) {
      productDetails = `Color : ${item.product_color} | Size : ${item.product_size}`;
    } else if (item.product_color) {
      productDetails = `Color : ${item.product_color}`;
    } else if (item.product_size) {
      productDetails = `Size : ${item.product_size}`;
    }

    // Construct HTML for the current item
    var cartItemHTML = `
      <div class="flex gap-3 items-center shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] rounded-lg p-3 relative">
        <img class="h-32 w-24 rounded-lg object-cover" src="${item.productImage[0]}" alt="${item.product_name}" />
        <div class="flex flex-col justify-between gap-[6px] ">
          <div>
           <button class="text-gray-500 hover:text-red-500 absolute right-6 remove-cart-icon" data-item-id="${item.id}">
        <i class="fas fa-trash text-lg"></i>
      </button>
            <div class="capitalize font-semibold" style="font-family: 'Poppins', sans-serif; font-weight: 100;">${
              item.product_name
            }</div>
            <div class="line-clamp-1 text-sm" style="color:#00438F;">by ${item.store_name}</div>
            <div class="!mt-0" style="font-family: 'Poppins', sans-serif;">${productDetails}</div>
          </div>
          <div>
            <span>Qty</span>
            <select class="ml-3 bg-gray-100 rounded-lg p-1 px-2 outline-none" name="Qty" id="Qty-${
              item.id
            }" data-item-id="${item.id}">
              ${[...Array(10).keys()]
                .map(i => `<option value="${i + 1}" ${item.quantity == i + 1 ? 'selected' : ''}>${i + 1}</option>`)
                .join('')}
            </select>
          </div>
          <div class="font-semibold text-secondary text-lg mb-2">
            <span class="text-[#00438F]">$${item.product_sale_price}</span>
            ${
              item.product_sale_price < item.product_price
                ? `<span class="line-through text-black">$${item.product_price}</span>`
                : ''
            }
          </div>
        </div>
      </div>
    `;

    cartContent.append(cartItemHTML); // Append the current item HTML to the cart content

    // Add event listener to quantity select element
    $(`#Qty-${item.id}`).on('change', function () {
      var newQuantity = parseInt($(this).val()); // Get the new quantity
      var cartId = $(this).data('item-id'); // Get the cart_id
      var baseUrlLive = sessionStorage.getItem('baseUrlLive');
      var user_id = localStorage.getItem('user_id');

      // Call the API to update the cart
      $.ajax({
        url: baseUrlLive + '/api/cart-update',
        type: 'POST',
        data: {
          cart_id: cartId,
          quantity: newQuantity,
          user_id: user_id
        },
        success: function (response) {
          // Handle success response if needed
          console.log('Cart updated successfully');
          getCartCheckoutList();
        },
        error: function (xhr, status, error) {
          // Handle error if needed
          console.error('Error updating cart:', error);
        }
      });
    });
    // Add event listener to remove button
    $(`.remove-cart-icon[data-item-id="${item.id}"]`).on('click', function () {
      var cartId = $(this).data('item-id');
      removeCartItemCheckout(cartId);
    });
  });

  // Update the subtotal and total amount in the summary section
  $('.cr-checkout-summary .text-right').text('$' + subTotalAmount.toFixed(2));
  $('.cr-checkout-summary-total .text-right').text('$' + totalAmount.toFixed(2));
}

// Display message when cart is empty
function displayEmptyViewCartMessage() {
  $('#cartCheckoutList').empty().append('<p>No items in the cart.</p>');
}

// Initial call to fetch cart items
getCartCheckoutList();

// Function to display cart address dynamically
// Function to display cart address dynamically
function displayCartAddress(address) {
  if (
    address &&
    address.first_name &&
    address.last_name &&
    address.pincode &&
    address.address &&
    address.locality &&
    address.city &&
    address.state
  ) {
    $('#customerName').text(`${address.first_name} ${address.last_name}`);
    $('#postalCode').text(address.pincode);
    $('#customerAddress').text(`${address.address}, ${address.locality}, ${address.city}, ${address.state}`);
    $('#changeAddressButton').text('Change');
  } else {
    $('#customerAddress').html('<p>No address found. Please add an address.</p>');
    $('#changeAddressButton').text('Add');
  }
}
document.getElementById('goto_payment').addEventListener('click', function (event) {
  if (
    !document.getElementById('customerAddress').textContent.trim() ||
    document.getElementById('customerAddress').textContent.trim() === 'No address found. Please add an address.'
  ) {
    event.preventDefault();
    Swal.fire({
      text: 'Please Add Your Address to proceed to checkout.',
      icon: 'info',
      confirmButtonText: 'OK'
    }).then(result => {
      if (result.isConfirmed) {
        location.reload();
      }
    });
  } else {
  }
});
function displayCouponCode(couponCode) {
  if (couponCode) {
    $('#showCouponbar').show();
    $('#ShowapplyCoupon').hide();
    $('#coupon-applied').html(`
        <div class="-rotate-45 w-fit">
          <i class="ri-price-tag-line text-xl"></i>
        </div>
        <div>
          <div class="text-lg font-medium !text-[#00438F]">${couponCode}</div>
          <div>Coupon Applied</div>
        </div>
    `);
  } else {
    $('#showCouponbar').hide();
    $('#ShowapplyCoupon').show();
  }
}

function displayOrderSummary(bagTotal, coupons, totalAmount) {
  // Check if each value is empty or undefined, and set to 0 if so
  bagTotal = bagTotal || 0;
  coupons = coupons || 0;
  totalAmount = totalAmount || 0;

  $('#bagTotal').text(`$${bagTotal}`);
  $('#coupons').text(`- $${coupons}`);
  $('#totalAmount').text(`$${totalAmount}`);
}

// Function to remove cart item
function removeCartItemCheckout(cartId) {
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
      var token = localStorage.getItem('token');
      var baseUrlLive = sessionStorage.getItem('baseUrlLive');

      $.ajax({
        type: 'POST',
        url: baseUrlLive + '/api/cart-remove',
        dataType: 'json',
        headers: { Authorization: `Bearer ${token}` },
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
          getCartCheckoutList();
          displayOrderSummary();
          getCartInnerCheckoutList();
          displayInnerOrderSummary();
        },
        error: function (xhr, status, error) {
          // Handle error response
          console.error('AJAX error: ' + error);
        }
      });
    }
  });
}
