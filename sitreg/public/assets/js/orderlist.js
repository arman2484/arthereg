$(document).ready(function () {
  function GetOrderList() {
    const token = localStorage.getItem('token');
    const baseUrlLive = sessionStorage.getItem('baseUrlLive');
    $.ajax({
      type: 'GET',
      url: baseUrlLive + '/api/get-order',
      dataType: 'json',
      headers: { Authorization: `Bearer ${token}` },
      success: function (response) {
        if (response && response.status === 'success' && response.data && response.data.length > 0) {
          console.log('Order List Found:', response.data);
          displayOrderListData(response.data);
        } else {
          console.error('No orders found');
          displayNoOrderMessage();
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
      }
    });
  }

  GetOrderList();

  function getStatusText(statusCode) {
    switch (statusCode) {
      case 0:
        return { text: 'Pending', color: '#DEDE44' };
      case 1:
        return { text: 'Confirmed', color: '#00438F' };
      case 2:
        return { text: 'Delivered', color: '#6EB99D' };
      case 3:
        return { text: 'Cancelled', color: '#FB5555' };
      default:
        return { text: 'Unknown', color: '#000' };
    }
  }

  function displayOrderListData(orders) {
    const description = document.getElementById('description');
    description.innerHTML = '';

    if (orders.length === 0) {
      displayNoOrderMessage();
      return;
    }

    orders.forEach(order => {
      let orderItems = order.itemsList
        .map(item => {
          let productDetails = '';
          if (item.product_color && item.product_size) {
            productDetails = `${item.product_color} | ${item.product_size}`;
          } else if (item.product_color) {
            productDetails = `${item.product_color}`;
          } else if (item.product_size) {
            productDetails = `${item.product_size}`;
          }

          const reviewButton =
            item.is_status === 2
              ? `<button class="btn-review bg-[#00438f] text-white py-1 px-3 rounded mt-2" data-product-id="${item.product_id}">Add Review</button>`
              : '';

          return `
                    <div class="mt-3 w-full bg-[#F9F9F9] p-3 rounded-lg flex justify-between">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <img class="h-24 w-24 rounded-lg object-cover" src="${item.productImage[0]}" alt="">
                            <div class="space-y-2 flex flex-col justify-center">
                                <div class="font-semibold text-base">${item.product_name}</div>
                                <div class="!mt-0">${productDetails}</div>
                                     ${reviewButton}
                            </div>
                        </div>
                        <div class="space-y-2 text-right mt-auto mb-auto">
                            <div class="font-bold text-base">$${item.product_sale_price}</div>
                            <div class="text-[#00438F]">Qty: ${item.quantity}</div>
                        </div>
                    </div>
                `;
        })
        .join('');

      const { text: statusText, color: statusColor } = getStatusText(order.order_status);
      let orderHTML = `
                <div class="accordion" id="accordion${order.order_id}">
                    <div class="accordion-item border !rounded-xl">
                        <h2 class="accordion-header" id="heading${order.order_id}">
                            <div class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse${
                                  order.order_id
                                }" aria-expanded="true" aria-controls="collapse${order.order_id}">
                                <div class="flex flex-col w-full">
                                    <div class="flex gap-3 items-center">
                                        <div class="space-y-1">
                                            <span class="font-bold text-sm">Order ID: #${order.order_id}</span>
                                            <span class="font-semibold px-3 rounded-lg text-sm" style="background-color: ${statusColor}; color: ${
        statusColor === '#fff' ? '#000' : '#fff'
      }; padding-bottom: 0.3rem;">${statusText}</span>
                                            <div class=" text-[#A2A2A2] font-medium">
                                                <span>${order.order_date}</span>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </h2>
                        <div id="collapse${
                          order.order_id
                        }" class="accordion-collapse collapse" aria-labelledby="heading${
        order.order_id
      }" data-bs-parent="#accordion${order.order_id}">
                            <div class="accordion-body">
                               ${orderItems}
                                <div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-3 ">
                                    <div class="lg:col-span-2 w-full">
                                        <div class="font-semibold text-base pb-3">Seller Info</div>
                                        <div class="flex gap-3">
                                            <img class="h-20 w-20 rounded-xl object-cover " src="${
                                              order.store_logo
                                            }" alt="">
                                            <div class="space-y-1 ">
                                                <div class="font-medium text-base">${order.store_name}</div>
                                                <div class="">${order.store_address}</div>
                                            </div>
                                        </div>
                                        <div class="flex w-full  items-center justify-between p-3 px-4 mt-3 border rounded-xl ">
                                            <div class="text-[#00438F] flex items-center gap-3">
                                                <img class="h-6 w-6 object-contain" src="assets/images/cards.png" alt="">
                                                <span class="text-base font-semibold">Payment Method</span>
                                            </div>
                                            <div class="font-medium text-base ">${order.payment_mode}</div>
                                        </div>
                                    <div class="flex w-full  justify-between p-3 px-4 mt-3 border rounded-xl ">
                                       <div class="text-[#00438F] flex gap-3">
                                            <img class="h-6 w-6 object-contain" src="assets/images/location.png"
                                               alt="">
                                            <div class="flex flex-col gap-1">
                                              <span class="text-base font-semibold">Address</span>
                                              <span class="text-black font-normal">${order.user_address} ${
        order.locality
      } ${order.user_city} ${order.user_state} ${order.user_zip_code}</span>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                     <div class="col-span-1 mt-auto mb-1">
                                        <div class="bg-[#F1F1F1] rounded-lg p-4">
                                            <h3 class="text-lg font-semibold text-darkTextColor">
                                                Payment Summary
                                            </h3>
                                            <div class="space-y-4">
                                                <div class="flex justify-between mt-2">
                                                    <span class="">Price</span>
                                                    <span class="font-bold ">$${order.totalSaleProductPrice}</span>
                                                </div>
                                                <div class="flex justify-between mt-2">
                                                    <span class="">Coupon Discount</span>
                                                    <span class="font-bold  text-red-500">$${
                                                      order.totalDiscountAmount
                                                    }</span>
                                                </div>

                                                <hr class="w-full border-borderColor" />
                                                <div class="flex justify-between mt-2">
                                                    <span class="">Total</span>
                                                    <span class="font-bold ">$${order.totalAmount}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

      description.innerHTML += orderHTML;
    });
    attachReviewButtonListeners();
  }

  function displayNoOrderMessage(message = 'No orders found.') {
    const description = document.getElementById('description');
    description.innerHTML = `<p>${message}</p>`;
  }

  function attachReviewButtonListeners() {
    const reviewButtons = document.querySelectorAll('.btn-review');
    reviewButtons.forEach(button => {
      button.addEventListener('click', function (event) {
        const productId = event.currentTarget.getAttribute('data-product-id');
        sessionStorage.setItem('reviewProductId', productId); // Store productId in sessionStorage
        $('#addReviewModal').modal('show');
      });
    });
  }

  // Rating stars click event handler
  $('#ratingStars label').click(function () {
    const rating = $(this).data('value');
    $('#ratingStars label').removeClass('text-red-500').addClass('text-gray-400');
    $(this).prevAll('label').addBack().removeClass('text-gray-400').addClass('text-red-500');
    $('#reviewForm input[name="rating"][value="' + rating + '"]').prop('checked', true);
  });

  // Submit review form
  $('#reviewForm').submit(function (event) {
    event.preventDefault();
    const productId = sessionStorage.getItem('reviewProductId'); // Retrieve productId from sessionStorag
    const rating = $('input[name="rating"]:checked').val();
    const reviewMessage = $('#reviewMessage').val();
    const token = localStorage.getItem('token');
    const baseUrlLive = sessionStorage.getItem('baseUrlLive');

    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/review-post',
      dataType: 'json',
      headers: { Authorization: `Bearer ${token}` },
      data: {
        product_id: productId,
        review_star: rating,
        review_message: reviewMessage
      },
      success: function (response) {
        console.log('Review submitted successfully:', response);
        $('#addReviewModal').modal('hide');
        sessionStorage.removeItem('reviewProductId'); // Clean up after submitting

        // Show success message with SweetAlert2
        Swal.fire({
          title: 'Success!',
          text: 'Your review has been submitted.',
          icon: 'success',
          confirmButtonText: 'OK'
        }).then(() => {
          // Clear the review form data
          $('input[name="rating"]').prop('checked', false); // Uncheck all star ratings
          $('#reviewMessage').val(''); // Clear review message
        });
      },
      error: function (xhr, status, error) {
        console.error('Error submitting review:', error);
      }
    });
  });
});
