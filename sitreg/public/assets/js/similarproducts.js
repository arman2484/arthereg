$(document).ready(function () {
  var store_id = sessionStorage.getItem('store_id');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  // Function to fetch product list
  window.getSimilarProductList = function () {
    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/product-listonstore',
      data: {
        store_id: store_id
      },
      dataType: 'json',
      success: function (response) {
        // Check if products data exists and has a length greater than 0
        if (response && response.products && response.products.length > 0) {
          console.log('Products found:', response.products);

          displaySimilarProducts(response.products);
        } else {
          console.error('No products found');
          displayNoProductsFoundData();
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
        displayNoProductsFoundData();
      }
    });
  };

  getSimilarProductList();

  // Function to display product list
  window.displaySimilarProducts = function (products) {
    var productContent = $('#sliderproducts');
    productContent.empty(); // Clear previous content

    // Loop through each product and generate the corresponding HTML
    $.each(products, function (index, product) {
      var filledStars = '';
      var emptyStars = '';

      // Calculate number of filled and empty stars based on review rating
      for (var i = 0; i < 5; i++) {
        if (i < product.totalAvgReview) {
          filledStars += '<i class="ri-star-fill"></i>';
        } else {
          emptyStars += '<i class="ri-star-line"></i>';
        }
      }

      // Initialize variables for heart icon color and style
      var heartColorClass = product.is_Like == 1 ? 'ri-heart-2-fill icon_color' : 'ri-heart-2-line';

      // Select the first image URL from the product_image array
      const firstImageUrl =
        product.product_image.length > 0
          ? product.product_image[0]
          : 'http://127.0.0.1:8000/assets/images/product-default.png';

      // Create query string with product details
      let queryParams = $.param({
        product_id: product.id,
        store_id: product.store_id,
        is_product_size: product.product_size.length != 0,
        is_color: product.product_color.length != 0,
        module_id: product.module_id // Include module_id in the query parameters
      });

      // Generate the product HTML using template literals
      var productHTML = `
        <div class="col-lg-3 col-6 cr-product-box mb-24">
          <div class="cr-product-card">
            <div class="cr-product-image">
           <a href="${stringToSlug(
                    product.product_name
                  )}?${queryParams}" onclick="sessionStorage.setItem('product_id', '${
        product.id
      }'); sessionStorage.setItem('store_id', ${product.store_id});  sessionStorage.setItem('is_product_size', '${
        product.product_size.length != 0 ? true : false
      }'); sessionStorage.setItem('is_color', '${
        product.product_color.length != 0 ? true : false
      }');  getSingleProductDetails('${product.id}')" class="cr-image-inner">
                <img src="${firstImageUrl}" alt="${product.product_name}" style="height:200px; width:200px;">
              </a>
              <div class="cr-side-view">
                <a href="javascript:void(0)" class="wishlist" onclick="sessionStorage.setItem('product_id', ${
                  product.id
                }); sessionStorage.setItem('store_id', ${product.store_id}); likeProduct(${product.id})">
                  <i id="heartIcon${product.id}" class="${heartColorClass}"></i>
                </a>
                <a class="model-oraganic-product" data-bs-toggle="modal" onClick="sessionStorage.setItem('product_id', ${
                  product.id
                }); sessionStorage.setItem('store_id', ${
        product.store_id
      }); sessionStorage.setItem('is_product_size', '${
        product.product_size.length != 0 ? true : false
      }'); sessionStorage.setItem('is_color', '${
        product.product_color.length != 0 ? true : false
      }');  getProductDetails(${product.id})" href="#quickview" role="button">
                  <i class="ri-eye-line"></i>
                </a>
                <a class="cr-shopping-bag" id="shoppingBagIcon${
                  product.id
                }" style="bottom: -35px !important;" href="javascript:void(0)" onclick="addToCart(${product.id})">
                  <i class="ri-shopping-bag-line"></i>
                </a>
              </div>
            </div>

            <div class="cr-product-details">
              <div class="cr-brand">
                <div class="cr-star">
                  ${filledStars}
                  ${emptyStars}
                  <p>(${product.totalReviewCount})</p>
                </div>
              </div>
            <a href="${stringToSlug(
                    product.product_name
                  )}?${queryParams}" onClick="sessionStorage.setItem('product_id', ${
        product.id
      }); sessionStorage.setItem('store_id', ${product.store_id}); sessionStorage.setItem('is_product_size', '${
        product.product_size.length != 0 ? true : false
      }'); sessionStorage.setItem('is_color', '${
        product.product_color.length != 0 ? true : false
      }'); getSingleProductDetails(${product.id})" class="title">${product.product_name}
              </a>
              <p class="text">${product.product_about}</p>
              <p class="cr-price">
                <span class="new-price">$${product.product_sale_price}</span>
                <span class="old-price">$${product.product_price}</span>
              </p>
            </div>
          </div>
        </div>`;

      // Append the generated product HTML to the product content container
      productContent.append(productHTML);
    });
  };

  // Function to display "No products found" message
  function displayNoProductsFoundData() {
    const container = document.getElementById('sliderproducts');
    container.innerHTML = ''; // Clear any existing content

    const emptyMessage = `
      <div style="text-align: center; margin-top: 20px;">
        <div id="lottie-no-product-store" style="width: 300px; height: 300px; margin: 0 auto;"></div>
        <div style="font-size: 24px; color: #00438f !important;" class="text-center py-4">No product found</div>
      </div>
    `;
    container.innerHTML = emptyMessage;

    // Load the Lottie animation
    lottie.loadAnimation({
      container: document.getElementById('lottie-no-product-store'), // The container for the animation
      renderer: 'svg', // The renderer to use
      loop: true, // Whether the animation should loop
      autoplay: true, // Whether the animation should start automatically
      path: '/assets/json/lottie.json' // The path to the Lottie file (you can also use a URL)
    });
  }
});

function stringToSlug(inputString) {
  // Remove leading and trailing whitespace and convert to lowercase
  const cleanedString = inputString.trim().toLowerCase();

  // Replace spaces with hyphens
  const slug = cleanedString.replace(/\s+/g, '-');

  return slug;
}
