$(document).ready(function () {
  var userId = localStorage.getItem('user_id');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  // Function to fetch product list
  window.getWishlistProductList = function () {
    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/product-wishlist',
      data: { user_id: userId },
      dataType: 'json',
      success: function (response) {
        if (response && response.data && response.data.length > 0) {
          console.log('Products found:', response.data);
          displayWishlistProducts(response.data);
        } else {
          console.error('No products found');
          displayNoWishlistFoundMessage();
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
        displayNoWishlistFoundMessage();
      }
    });
  };

  getWishlistProductList();

  // Function to display product list
  function displayWishlistProducts(products) {
    var productContent = $('#wishlist');
    productContent.empty(); // Clear previous content

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

      var heartColorClass = product.isLike == 1 ? 'ri-heart-2-fill icon_color' : 'ri-heart-2-line';
      // Check if product image is available, otherwise use default image
      var productImage = product.product_image ? product.product_image : 'assets/images/product-default.png';

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
             <a href="product-detail/${stringToSlug(
               product.product_name
             )}?${queryParams}" onclick="sessionStorage.setItem('product_id', '${
        product.id
      }'); sessionStorage.setItem('store_id', ${product.store_id});  sessionStorage.setItem('is_product_size', '${
        product.product_size.length != 0 ? true : false
      }'); sessionStorage.setItem('is_color', '${
        product.product_color.length != 0 ? true : false
      }');  getSingleProductDetails('${product.id}')" class="cr-image-inner">
                  <img src="${productImage}" alt="${product.product_name}" style="height:200px; width:200px;">
              </a>

              <div class="cr-side-view">
                <a href="javascript:void(0)" class="wishlist" onclick="sessionStorage.setItem('product_id', ${
                  product.id
                }); sessionStorage.setItem('store_id', ${product.store_id}); likeProduct(${product.id})">
                  <i id="heartIcon${product.id}" class="${heartColorClass}"></i> <!-- Apply inline style here -->
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
             <a href="product-detail/${stringToSlug(
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

      productContent.append(productHTML);
    });
  }
  function displayNoWishlistFoundMessage() {
    const productContent = $('#wishlist');
    productContent.empty(); // Clear previous content

    // Create the container for the Lottie animation and the message
    const noProductHTML = `
    <div style="text-align: center; margin-top: 50px;">
      <div id="lottie-no-product-wishlist" style="width: 300px; height: 300px; margin: 0 auto;"></div>
      <div style="font-size: 24px; color: #00438f !important;" class="text-center py-4">No wishlist found</div>
    </div>`;

    // Append the container to the product content
    productContent.append(noProductHTML);

    // Load the Lottie animation
    lottie.loadAnimation({
      container: document.getElementById('lottie-no-product-wishlist'), // The container for the animation
      renderer: 'svg', // The renderer to use
      loop: true, // Whether the animation should loop
      autoplay: true, // Whether the animation should start automatically
      path: 'assets/json/lottie.json' // The path to the Lottie file (you can also use a URL)
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
