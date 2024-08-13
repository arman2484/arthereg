$(document).ready(function () {
  function GetCouponList() {
    var token = localStorage.getItem('token');
    let baseUrlLive = sessionStorage.getItem('baseUrlLive');
    $.ajax({
      type: 'GET',
      url: baseUrlLive + '/api/coupons-list',
      dataType: 'json',
      headers: { Authorization: `Bearer ${token}` },
      success: function (response) {
        if (response && response.success && response.data && response.data.length > 0) {
          console.log('Coupon List Found:', response.data);
          displayCouponData(response.data);
        } else {
          console.error('No coupons found or response was not successful');
          displayNoCouponFoundMessage();
        }
      },

      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
        displayNoCouponFoundMessage();
      }
    });
  }

  GetCouponList();

  function displayCouponData(data) {
    const description = document.getElementById('couponlistdata');
    description.innerHTML = '';

    data.forEach(coupon => {
      // Parse the created_at date string
      const createdAtDate = new Date(coupon.created_at);

      // Format the date
      const formattedDate = `${createdAtDate.getDate()}${getOrdinalSuffix(createdAtDate.getDate())} ${getMonthName(
        createdAtDate.getMonth()
      )} ${createdAtDate.getFullYear()}`;

      let orderHTML = `
      <div class="w-full grid grid-cols-3 place-content-center p-1 shadow-xl rounded-xl">
        <div class="col-span-1 flex flex-col gap-2 items-center py-4 h-full bg-gray-100 rounded-l-xl">
          <img class="h-12 w-12" src="/assets/images/coupons_image.png" alt="">
          <div class="text-2xl font-bold text-center">$${coupon.discount_amount} OFF</div>
        </div>
        <div class="col-span-2 flex flex-col justify-evenly gap-2 items-center h-full bg-white rounded-r-xl">
          <div onclick="copyToClipBoard('${coupon.coupon_code}')" class="cursor-pointer font-medium rounded-lg text-center bg-[#F1F2F6] px-2 py-1">
            <span id="">${coupon.coupon_code}</span>
            <i class="ri-file-copy-line"></i>
          </div>
          <div class="font-medium text-center">Valid Till ${formattedDate}</div>
          <div class="text-base text-[#A9A9A9] text-center">${coupon.description}</div>
        </div>
      </div>
    `;
      description.innerHTML += orderHTML;
    });
  }

  function displayNoCouponFoundMessage() {
    const description = document.getElementById('couponlistdata');
    description.innerHTML = '<div class="text-center text-gray-500 py-4">No coupons found</div>';
  }

  // Function to get the ordinal suffix for a given number
  function getOrdinalSuffix(number) {
    const suffixes = ['th', 'st', 'nd', 'rd'];
    const v = number % 100;
    return suffixes[(v - 20) % 10] || suffixes[v] || suffixes[0];
  }

  // Function to get the name of the month for a given month index
  function getMonthName(monthIndex) {
    const months = [
      'January',
      'February',
      'March',
      'April',
      'May',
      'June',
      'July',
      'August',
      'September',
      'October',
      'November',
      'December'
    ];
    return months[monthIndex];
  }
});
