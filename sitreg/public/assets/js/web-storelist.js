$(document).ready(function () {
  let currentPage = 1;
  let itemsPerPage = 12;

  let module_id = localStorage.getItem('module_id');
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');
  var category_id = sessionStorage.getItem('category_id');
  var sub_category_id = sessionStorage.getItem('sub_category_id');

  // Function to fetch product list
  window.getModuleStoreList = function (page) {
    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/get-storeonweb',
      data: {
        page: page,
        per_page: itemsPerPage,
        module_id: module_id,
        category_id: category_id,
        sub_category_id: sub_category_id
      },
      dataType: 'json',
      success: function (response) {
        // Check if products data exists and has a length greater than 0
        if (response && response.data && response.data.length > 0) {
          console.log('Products found:', response.data);
          displayInnerModuleStores(response.data);
          updatePaginationOnStore(response);
        } else {
          console.error('No products found');
          displayEmptyStoreMessage();
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
         displayEmptyStoreMessage();
      }
    });
  };

  getModuleStoreList(currentPage);

  // Store Dynamic
  function displayInnerModuleStores(stores) {
    const container = document.getElementById('getmodulestoreinnerdata');
    container.innerHTML = ''; // Clear any existing content

    stores.forEach(store => {
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

      // Select the first image URL from the product_image array
      const firstImageUrl = store.store_images.length > 0 ? store.store_images[0] : 'assets/images/shop-default.jpg';
      const storeLogoUrl = store.store_logo ? store.store_logo : 'assets/images/storelogo-default.jpg';

      // Create query string with product details
      let queryParams = $.param({
        store_id: store.id,
      });

      // Construct the product card HTML content with the first image
      const content = `
        <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-12 mb-4">
          <div class="store-card store-card-custom position-relative">
            <div class="position-relative">
             <a href="store-detail/${stringToSlug(store.store_name)}?${queryParams}"
   onClick="sessionStorage.setItem('store_id', ${store.id})">
  <img class="store-card-img-top" src="${firstImageUrl}" alt="Store Image">
  <img class="product-logo" src="${storeLogoUrl}" alt="Store Logo">
</a>

            </div>
            <div class="store-card-body store-card-body-custom">
              <h5 class="store-card-title bold">${store.store_name}</h5>
              <p class="store-card-text">
                <div class="cr-star" style="color: #FFD428">
                    ${filledStars}
                  ${emptyStars}
                </div>
                <span class="d-block mt-2">${store.totalReviewCount} Reviews</span>
              </p>
              <div style="position: absolute; right: 1rem; bottom: 1rem;">
                 <a href="store-detail/${stringToSlug(
                   store.store_name
                 )}?${queryParams}" class="cr-button" onClick="sessionStorage.setItem('store_id', ${
        store.id
      })";>View Store</a>
              </div>
            </div>
          </div>
        </div>
      `;
      container.innerHTML += content;
    });
  }

  function stringToSlug(inputString) {
    // Remove leading and trailing whitespace and convert to lowercase
    const cleanedString = inputString.trim().toLowerCase();

    // Replace spaces with hyphens
    const slug = cleanedString.replace(/\s+/g, '-');

    return slug;
  }

  // Function to update pagination links
  function updatePaginationOnStore(paginationData) {
    var paginationLinks = $('.pagination');
    paginationLinks.empty();

    // Generate Previous button
    var prevClass = paginationData.current_page == 1 ? 'disabled' : '';
    var prevLink = `
      <li class="page-item ${prevClass}">
        <a class="page-link" href="#" data-page="${paginationData.current_page - 1}">Previous</a>
      </li>`;
    paginationLinks.append(prevLink);

    // Loop through each pagination link and generate the corresponding HTML
    for (let i = 1; i <= paginationData.last_page; i++) {
      var activeClass = paginationData.current_page == i ? 'active' : '';
      var pageLink = `
        <li class="page-item ${activeClass}">
          <a class="page-link" href="#" data-page="${i}">${i}</a>
        </li>`;
      paginationLinks.append(pageLink);
    }

    // Generate Next button
    var nextClass = paginationData.current_page == paginationData.last_page ? 'disabled' : '';
    var nextLink = `
      <li class="page-item ${nextClass}">
        <a class="page-link" href="#" data-page="${paginationData.current_page + 1}">Next</a>
      </li>`;
    paginationLinks.append(nextLink);

    // Add click event listener to pagination links
    $('.page-link').on('click', function (e) {
      e.preventDefault();
      var page = $(this).data('page');
      if (page) {
        getModuleStoreList(page);
      }
    });
  }
  // Function to display "No store found" message
  function displayEmptyStoreMessage() {
    const container = document.getElementById('getmodulestoreinnerdata');
    container.innerHTML = ''; // Clear any existing content

    const emptyMessage = `
      <div style="text-align: center; margin-top: -72px;">
        <div id="lottie-no-product-store" style="width: 300px; height: 300px; margin: 0 auto;"></div>
        <div style="font-size: 24px; color: #00438f !important;" class="text-center py-4">No store found</div>
      </div>
    `;
    container.innerHTML = emptyMessage;

    // Load the Lottie animation
    lottie.loadAnimation({
      container: document.getElementById('lottie-no-product-store'), // The container for the animation
      renderer: 'svg', // The renderer to use
      loop: true, // Whether the animation should loop
      autoplay: true, // Whether the animation should start automatically
      path: 'assets/json/lottie.json' // The path to the Lottie file (you can also use a URL)
    });
  }
});
