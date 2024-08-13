function GetIndexMainPopularProductData() {
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');
  let module_id = localStorage.getItem('module_id');
  let user_id = localStorage.getItem('user_id');

  // Make AJAX request
  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/get-store',
    dataType: 'json',
    data: { module_id, user_id },
    success: function (response) {
      if (response && response.success && response.data && response.data.length > 0) {
        DisplayPopularProductDataIndex(response.data);
      } else {
        console.error('No popular product data found');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
    }
  });
}

function DisplayPopularProductDataIndex(stores) {
  const container = document.getElementById('displayindexpopulardatastorerelated');
  container.innerHTML = ''; // Clear any existing content

  stores.slice(0, 6).forEach(store => {
    const StoreImageUrl = store.store_images.length > 0 ? store.store_images[0] : 'assets/images/shop-default.jpg';

    var filledStars = '';
    var emptyStars = '';

    // Calculate number of filled and empty stars based on review rating
    for (var i = 0; i < 5; i++) {
      if (i < store.totalAvgReview) {
        filledStars += '<i class="ri-star-fill"></i>';
      } else {
        emptyStars += '<i class="ri-star-line"></i>';
      }
    }

    // Create query string with product details
    let queryParams = $.param({
      store_id: store.id
    });

    // Create the store card HTML content
    let content = `
      <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12 mb-4 px-4 pt-4">
        <div class="row position-relative shadow rounded trending-card">
          <div class="col-4 position-relative px-0 trending-left">
            <img class="trending-product-logo" src="${StoreImageUrl}" alt="Product Logo">
            <div class="trending-card-body trending-card-body-custom">
              <h5 class="trending-card-title bold">${store.store_name}</h5>
              <div class="cr-star" style="color: #FFD428">
                ${filledStars}
                  ${emptyStars}
              </div>
              <div class="mt-2">
                                       <a href="store-detail/${stringToSlug(
                                         store.store_name
                                       )}?${queryParams}" class="cr-button" onClick="sessionStorage.setItem('store_id', ${
      store.id
    })";>View</a>
              </div>
            </div>
          </div>
          <div class="col-8 trending-card-body trending-card-body-custom">
            <div class="right-box">`;

    // Loop through the first 3 products in the store
    store.products.slice(0, 3).forEach(product => {
      // Truncate product name to three words followed by ellipsis
      const truncatedProductName = truncateProductName(product.product_name, 3);

      // Determine product image or default image
      const productImage =
        product.product_images.length > 0 ? product.product_images[0] : 'assets/images/product-default.png';

      content += `
        <div class="inner-right-box">
          <img src="${productImage}" alt="${product.product_name}">
          <div>
            <div class="text-truncate" style="font-family: 'Poppins', sans-serif;">${truncatedProductName}</div>
            <b style=" color: #00438f; font-family: 'Poppins', sans-serif;">$${product.product_sale_price}</b>
          </div>
        </div>`;
    });

    content += `
            </div>
          </div>
        </div>
      </div>`;

    container.innerHTML += content;
  });
}

// Function to truncate product names to a specified number of words
function truncateProductName(name, wordLimit) {
  const words = name.split(' ');
  if (words.length > wordLimit) {
    return words.slice(0, wordLimit).join(' ') + '';
  }
  return name;
}

function stringToSlug(inputString) {
  // Remove leading and trailing whitespace and convert to lowercase
  const cleanedString = inputString.trim().toLowerCase();

  // Replace spaces with hyphens
  const slug = cleanedString.replace(/\s+/g, '-');

  return slug;
}

// Call the function to load and display the data
GetIndexMainPopularProductData();
