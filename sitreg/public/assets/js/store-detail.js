// Function to get query parameters from the URL
function getQueryParams(url) {
  var params = {};
  var parser = document.createElement('a');
  parser.href = url;
  var query = parser.search.substring(1);
  var vars = query.split('&');
  for (var i = 0; i < vars.length; i++) {
    var pair = vars[i].split('=');
    params[pair[0]] = decodeURIComponent(pair[1]);
  }
  return params;
}

// Extract product_id from the current URL
var currentUrl = window.location.href;
var queryParams = getQueryParams(currentUrl);
var store_id = queryParams['store_id'];
sessionStorage.setItem('store_id', store_id); // Optional: store in sessionStorage

// Updated getSingleProductDetails function
window.FetchSingleStoreDetail = function () {
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');
  let store_id = sessionStorage.getItem('store_id');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/storedetail',
    dataType: 'json',
    data: { store_id }, // Assuming 'store_id' is the correct parameter
    success: function (response) {
      if (response && response.message === 'Store Found' && response.store) {
        console.log('Store Data Found:', response.store);
        DisplayStoreDetailData(response.store, response.totalUserCount, response.totalAvgReview);
        showStorereviewDynamic(
          response.store.store_name,
          response.totalUserCount,
          response.totalAvgReview,
          response.storeReviews
        );
      } else {
        console.error('No store data found');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
    }
  });
};

function DisplayStoreDetailData(store, totalUserCount, totalAvgReview) {
  const container = document.getElementById('displaystoredetaildata');
  container.innerHTML = ''; // Clear any existing content

  var filledStars = '';
  var emptyStars = '';

  // Calculate number of filled and empty stars based on review rating
  for (var i = 0; i < 5; i++) {
    if (i < totalAvgReview) {
      filledStars += '<i class="ri-star-fill"></i>';
    } else {
      emptyStars += '<i class="ri-star-line"></i>';
    }
  }
  // Define default image URLs
  const defaultFirstImageUrl = 'http://127.0.0.1:8000/assets/images/shop-default.jpg';
  const defaultStoreLogoUrl = 'http://127.0.0.1:8000/assets/images/storelogo-default.jpg';

  const firstImageUrl = store.store_images.length > 0 ? store.store_images[0] : defaultFirstImageUrl;
  const storeLogoUrl = store.store_logo ? store.store_logo : defaultStoreLogoUrl;
  const content = `
    <div class="store-details-other" style="
            background: url('${firstImageUrl}') no-repeat center center;
            background-size: cover;
            height: 20rem;
            border-radius: 1rem;
            margin-top: 2rem;
            position: relative;">
      <div class="store-details-header">
        <div class="store-details-header-left" style="display: flex; flex-direction: column; gap: 1rem;">
          <h5 class="selling-card-title bold">${store.store_name}</h5>
          <p class="selling-card-text">
            <div class="cr-star" style="color: #FFD428">
                   ${filledStars}
                  ${emptyStars}
              <span style="color: black;"> (${totalUserCount}) </span>
              <span style="color: black; cursor: pointer;" onclick="openQuickViewModal('${
                store.store_name
              }', ${totalUserCount}, ${totalAvgReview})"> View Review </span>
            </div>
            <div style="font-weight: 600; font-size: 14px;">
              <i class="ri-map-pin-line"></i>
              <span>${store.store_address}</span>
            </div>
            <div style="font-weight: 600; font-size: 14px;">
              <i class="ri-time-line"></i>
              <span>${store.min_time || '0:00'} - ${store.max_time || '0:00'} ${
    store.time_type || 'secs'
  } Delivery Time</span>
            </div>
          </div>
          <div class="store-details-header-right">
            <img style="object-fit: contain; height: 100%; width: 100%;"
                src="${storeLogoUrl}"
                alt="${store.store_name}">
          </div>
        </div>
      </div>
    </div>
  `;

  container.innerHTML = content;
}

function openQuickViewModal(storeName, totalReviews, totalAvgReview) {
  const starElements = generateStars(totalAvgReview); // Generate stars based on totalAvgReview
  $('#storereview .modal-title').html(`
    ${storeName}<br>
     ${starElements}<br>
    <small>Total Reviews: ${totalReviews}</small>

  `); // Set the modal title to the store name, total reviews, and stars
  $('#storereview').modal('show'); // Assuming you are using Bootstrap modal
}

function showStorereviewDynamic(storeName, totalReviews, totalAvgReview, storeReviews) {
  const modalBody = document.querySelector('#storereview .modal-body .cr-tab-content-from');
  modalBody.innerHTML = ''; // Clear existing reviews

  if (storeReviews.length === 0) {
    modalBody.innerHTML = '<p>No reviews found</p>';
    return;
  }

  storeReviews.forEach((review, index) => {
    const reviewStars = generateStars(review.review_star);
    const formattedDate = formatDate(review.createdAt);

    const reviewContent = `
      <div class="post">
        <div class="content">
          <img src="${review.image}" alt="review" style="width: 50px; height: 50px; border-radius: 50%;">
          <div class="details">
            <span class="date">${formattedDate}</span>
            <span class="name">${review.first_name} ${review.last_name}</span>
          </div>
          <div class="cr-t-review-rating">
            ${reviewStars}
          </div>
        </div>
        <p>${review.review_message}</p>
      </div>
    `;

    modalBody.innerHTML += reviewContent;

    // Add a line separator between reviews
    if (index < storeReviews.length - 1) {
      modalBody.innerHTML += '<hr style="border-top: 1px solid #ccc; margin: 1rem 0;">';
    }
  });
}

function generateStars(rating) {
  let filledStars = '';
  let emptyStars = '';
  for (let i = 0; i < 5; i++) {
    if (i < rating) {
      filledStars += '<i class="ri-star-s-fill"></i>';
    } else {
      emptyStars += '<i class="ri-star-s-line"></i>';
    }
  }
  return filledStars + emptyStars;
}

function formatDate(dateString) {
  const options = { year: 'numeric', month: 'long', day: 'numeric' };
  const date = new Date(dateString);
  return date.toLocaleDateString(undefined, options);
}

// Call the function to fetch and display store details
FetchSingleStoreDetail();
