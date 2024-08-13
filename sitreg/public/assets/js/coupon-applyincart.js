function applyCouponInCart() {
  var user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/coupons-list',
    dataType: 'json',
    data: { user_id: user_id }, // Pass user_id here
    success: function (response) {
      if (response && response.data && response.data.length > 0) {
        // Filter the coupons to only include those with status 1
        var activeCoupons = response.data.filter(coupon => coupon.status === 1);
        if (activeCoupons.length > 0) {
          console.log('Active coupons found:', activeCoupons);
          displayCoupons(activeCoupons);
          getCartCheckoutList();
          getCartInnerCheckoutList();
        } else {
          console.error('No active coupons found');
          displayEmptyCouponMessage();
        }
      } else {
        console.error('No coupons found');
        displayEmptyCouponMessage();
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
      displayEmptyCouponMessage();
    }
  });
}
function displayCoupons(coupons) {
  var couponsContainer = document.getElementById('coupons-container');
  couponsContainer.innerHTML = ''; // Clear previous coupons

  coupons.forEach(function (coupon) {
    var couponHtml = `
      <div class="shadow-[rgba(17,_17,_26,_0.1)_0px_0px_16px] rounded-lg p-4 !h-40 col-span-2 bg-white flex justify-between cursor-pointer">
        <div class="space-y-2">
          <div class="text-lg font-semibold">$${coupon.discount_amount} off</div>
          <div class="text-sm">${coupon.description}</div>
          <div class="flex gap-3 rounded-lg bg-gray-100 px-4 py-2">
            <span>${coupon.coupon_code}</span>
            <span class="text-gray-400"><i class="ri-clipboard-line"></i></span>
          </div>
        </div>
        <div class="flex flex-col justify-between">
          <button data-bs-dismiss="modal" aria-label="Close"
            onclick="applyCoupon('${coupon.coupon_code}')"
            class="border-[#FFD428] w-20 lg:w-24 border bg-[#FFD42840] rounded-lg py-1 ml-auto mr-4">
            Apply
          </button>
        </div>
      </div>`;
    couponsContainer.insertAdjacentHTML('beforeend', couponHtml);
  });
}

function displayEmptyCouponMessage() {
  var couponsContainer = document.getElementById('coupons-container');
  couponsContainer.innerHTML = '<p>No coupons available at the moment.</p>';
}

function applyCoupon(couponCode) {
  const user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  // AJAX call to apply coupon
  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/applied-coupon',
    dataType: 'json',
    data: { coupon_code: couponCode, user_id: user_id },
    success: function (response) {
      console.log('Coupon applied successfully:', response);
      displayAppliedCoupon(couponCode);
      applyCouponInCart();
      getCartCheckoutList();
      // Show the coupon bar on successful application
      document.getElementById('showCouponbar').style.display = 'flex';
      document.getElementById('ShowapplyCoupon').style.display = 'none';
      sessionStorage.setItem('coupon_code', couponCode);
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
    }
  });
}

function displayAppliedCoupon(couponCode) {
  var appliedCouponsContainer = document.getElementById('coupon-applied');
  var couponHtml = `


        <div class="-rotate-45 w-fit">
          <i class="ri-price-tag-line text-xl"></i>
        </div>
        <div>
          <div class="text-lg font-medium !text-[#00438F]">${couponCode}</div>
          <div>Coupon Applied</div>
        </div>


    `;
  appliedCouponsContainer.innerHTML = couponHtml;
}

applyCouponInCart();

function RemoveCoupon() {
  const user_id = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');
  var couponCode = sessionStorage.getItem('coupon_code'); // Fetch coupon code from session storage

  // Check if coupon code exists
  if (!couponCode) {
    console.error('Coupon code not found in session storage');
    return;
  }

  // AJAX call to remove coupon
  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/remove-coupon',
    dataType: 'json',
    data: { coupon_code: couponCode, user_id: user_id },
    success: function (response) {
      if (response.success) {
        console.log('Coupon removed successfully:', response);
        // Function to update UI after coupon removal
        displayRemovedCoupon(couponCode);
        applyCouponInCart();
      } else {
        console.error('Failed to remove coupon:', response.message);
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
    }
  });
}

// Function to update the UI after the coupon is removed
function displayRemovedCoupon(couponCode) {
  // Hide the coupon bar
  document.getElementById('showCouponbar').style.display = 'none';

  // Assuming you have an element to display messages to the user
  var messageElement = document.getElementById('message');
  if (messageElement) {
    messageElement.textContent = 'Coupon removed successfully!';
  }

  // Remove the coupon from the UI list
  var couponElement = document.getElementById('coupon-' + couponCode);
  if (couponElement) {
    couponElement.remove();
  }
}

document.addEventListener('DOMContentLoaded', function () {
  // Attach event listeners to all remove buttons
  document.querySelectorAll('.remove-coupon').forEach(button => {
    button.addEventListener('click', function () {
      var couponCode = this.getAttribute('data-coupon-code');

      // Display SweetAlert confirmation dialog
      Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to remove this coupon!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'No, keep it'
      }).then(result => {
        if (result.isConfirmed) {
          RemoveCoupon(couponCode);
          document.getElementById('ShowapplyCoupon').style.display = 'flex';
        }
      });
    });
  });
});
