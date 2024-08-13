$(document).ready(function () {
  let currentPage = 1;
  let itemsPerPage = 12;

  let userId = localStorage.getItem('user_id');
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');
  let module_id = localStorage.getItem('module_id');
  let category_id = sessionStorage.getItem('category_id');
  let sub_category_id = sessionStorage.getItem('sub_category_id');

  // Function to fetch product list
  window.getInnerProductList = function (page) {
    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/product-list',
      data: {
        page: page,
        per_page: itemsPerPage,
        user_id: userId,
        module_id: module_id,
        category_id: category_id,
        sub_category_id: sub_category_id
      },
      dataType: 'json',
      success: function (response) {
        // Check if products data exists and has a length greater than 0
        if (response && response.products && response.products.data.length > 0) {
          console.log('Products found:', response.products.data);

          displayInnerProducts(response.products.data);
          updatePagination(response.products);
        } else {
          console.error('No products found');
          displayNoproductFoundDataMain();
          hidePagination();
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
        displayNoproductFoundDataMain();
        hidePagination();
      }
    });
  };

  getInnerProductList(currentPage, userId, module_id);

  // Clear Filter
  $(document).on('click', '.cr-shop-sub-title-name[style*="cursor: pointer"]', function () {
    // Clear all checkboxes
    $('.cr-checkbox input[type="checkbox"]').prop('checked', false);
    $('.category-checkbox').prop('checked', false);

    sessionStorage.removeItem('category_id');
    sessionStorage.removeItem('sub_category_id');
    category_id = '';
    sub_category_id = '';

    getInnerProductList();
    showPagination();
  });

  // Function to display product list
  window.displayInnerProducts = function (products) {
    var productContent = $('#productlist');
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
                  <a href="product-detail/${stringToSlug(
                    product.product_name
                  )}?${queryParams}" onclick="sessionStorage.setItem('product_id', '${
        product.id
      }'); sessionStorage.setItem('store_id', ${product.store_id}); sessionStorage.setItem('is_product_size', '${
        product.product_size.length != 0
      }'); sessionStorage.setItem('is_color', '${product.product_color.length != 0}'); getSingleProductDetails('${
        product.id
      }')" class="cr-image-inner">
                  <img src="${
                    product.product_image ? product.product_image : 'assets/images/product-default.png'
                  }" alt="${product.product_name}" style="height:200px; width:200px;">
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
                }" style="bottom: -35px !important; left: 0px;" href="javascript:void(0)" onclick="addToCart(${
        product.id
      })">
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
              )}?${queryParams}"  onClick="sessionStorage.setItem('product_id', ${
        product.id
      }); sessionStorage.setItem('store_id', ${product.store_id}); sessionStorage.setItem('is_product_size', '${
        product.product_size.length != 0 ? true : false
      }'); sessionStorage.setItem('is_color', '${
        product.product_color.length != 0 ? true : false
      }'); getSingleProductDetails(${product.id})" class="title">${product.product_name}
              </a>
               <p class="text">${truncateText(product.product_about, 100)}</p>
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

  function displayNoproductFoundDataMain() {
    const productContent = $('#productlist');
    productContent.empty(); // Clear previous content

    // Create the container for the Lottie animation and the message
    const noProductHTML = `
    <div style="text-align: center; margin-top: 50px;">
      <div id="lottie-no-product" style="width: 300px; height: 300px; margin: 0 auto;"></div>
      <div style="font-size: 24px; color: #00438f !important;" class="text-center py-4">No product found</div>
    </div>`;

    // Append the container to the product content
    productContent.append(noProductHTML);

    // Load the Lottie animation
    lottie.loadAnimation({
      container: document.getElementById('lottie-no-product'), // The container for the animation
      renderer: 'svg', // The renderer to use
      loop: true, // Whether the animation should loop
      autoplay: true, // Whether the animation should start automatically
      path: 'assets/json/lottie.json' // The path to the Lottie file (you can also use a URL)
    });
  }

  function truncateText(text, maxLength) {
    if (text.length <= maxLength) {
      return text;
    }
    return text.substr(0, maxLength) + '...';
  }

  function stringToSlug(inputString) {
    // Remove leading and trailing whitespace and convert to lowercase
    const cleanedString = inputString.trim().toLowerCase();

    // Replace spaces with hyphens
    const slug = cleanedString.replace(/\s+/g, '-');

    return slug;
  }

  // Function to update pagination links
  function updatePagination(paginationData) {
    var paginationLinks = $('.pagination');
    paginationLinks.empty();

    // Loop through each pagination link and generate the corresponding HTML
    $.each(paginationData.links, function (_, link) {
      var pageLink = $('<li class="page-item"></li>');
      var linkClass = link.active ? 'active' : '';
      var linkContent = `<a class="page-link ${linkClass}" href="${link.url}">${link.label}</a>`;
      pageLink.append(linkContent);
      paginationLinks.append(pageLink);
    });
  }

  // Pagination click event handler
  $(document).on('click', '.pagination .page-link', function (e) {
    e.preventDefault();
    var page = parseInt($(this).attr('href').split('page=')[1]);
    if (!isNaN(page)) {
      currentPage = page;
      getInnerProductList(currentPage);
    }
  });

  // Function to sort products dynamically
  function ProductSorting(sortOrder) {
    let category_id = sessionStorage.getItem('category_id');
    let sub_category_id = sessionStorage.getItem('sub_category_id');

    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/sort-product',
      data: {
        page: currentPage,
        per_page: itemsPerPage,
        user_id: userId,
        sort_order: sortOrder,
        module_id: module_id,
        category_id: category_id,
        sub_category_id: sub_category_id
      },
      dataType: 'json',
      success: function (response) {
        // Check if products data exists and has a length greater than 0
        if (response && response.products && response.products.data.length > 0) {
          console.log('Products found:', response.products.data);

          displayInnerProducts(response.products.data);
          updatePagination(response.products);
        } else {
          console.error('No products found');
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
      }
    });
  }

  // Event listener for sorting dropdown
  $('#SortingProducts select').on('change', function () {
    var sortOrder = $(this).val();
    ProductSorting(sortOrder);
  });
  // Function to fetch product details and add to cart
  window.addToCart = function (productId) {
    let userId = localStorage.getItem('user_id'); // Retrieve user_id from localStorage

    if (!userId) {
      // If user_id is not present, call guest-user API
      $.ajax({
        type: 'POST',
        url: baseUrlLive + '/api/guest-user',
        dataType: 'json',
        success: function (response) {
          if (response.status === 'success') {
            // Store the new user_id in localStorage
            localStorage.setItem('user_id', response.user.id);
            // Proceed to fetch product details and add to cart
            fetchProductAndAddToCart(productId);
          } else {
            console.error('Error creating guest user:', response);
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX error: ' + xhr.status + ' ' + error);
          // Optionally handle errors related to guest user creation
        }
      });
    } else {
      // If user_id is present, proceed to fetch product details and add to cart
      fetchProductAndAddToCart(productId);
    }
  };

  // Function to fetch product details and add to cart
  function fetchProductAndAddToCart(productId) {
    let userId = localStorage.getItem('user_id'); // Retrieve user_id from localStorage
    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/productdetail',
      data: { product_id: productId },
      dataType: 'json',
      success: function (response) {
        if (response && response.product) {
          var defaultSize = response.product.product_size.length > 0 ? response.product.product_size[0] : null;
          var defaultColor = response.product.product_color.length > 0 ? response.product.product_color[0] : null;
          addProductToCart(userId, productId, defaultSize, defaultColor, 1);
        } else {
          console.error('No product found or error in response:', response);
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + xhr.status + ' ' + error);
      }
    });
  }

  // Function to add product to cart
  window.addProductToCart = function (user_id, productId, product_size, product_color, quantity) {
    var baseUrlLive = sessionStorage.getItem('baseUrlLive');

    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/add-cart',
      data: {
        user_id: user_id,
        product_id: productId,
        product_size: product_size,
        product_color: product_color,
        quantity: quantity
      },
      dataType: 'json',
      success: function (response) {
        if (
          response &&
          response.message === 'Product added to cart successfully ...!' &&
          response.response_code === 1
        ) {
          // Cart addition successful, show toast notification
          Toastify({
            text: 'Product added to cart successfully!',
            theme: 'light',
            duration: 1000,
            close: true,
            gravity: 'top',
            position: 'right',
            backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
            stopOnFocus: true
          }).showToast();
          // Change text to 'Added'
          $('.cr-add-button button').text('Added');

          // Change text to 'Add to Cart' after 1 second
          setTimeout(function () {
            $('.cr-add-button button').text('Add to Cart');
          }, 1000); // 1000 milliseconds = 1 second
          // Hide the shopping bag icon
          $(`#shoppingBagIcon${productId}`).hide();
          $(`#shoppingBagIconData${productId}`).hide();
          fetchProductList();
        } else {
          console.error('Error adding product to cart:', response);
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + xhr.status + ' ' + error);
        // Show SweetAlert confirmation
        Swal.fire({
          title: 'Are you sure?',
          text: 'You have items from another store in the cart. If you continue, all previous items from the cart will be removed and this one will be added.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Clear Cart',
          cancelButtonText: 'Cancel',
          showCloseButton: true // Adding close button
        }).then(result => {
          if (result.isConfirmed) {
            // Clear cart and add product
            clearCartAndAddProduct(productId, product_size, product_color, quantity);
          }
        });
      }
    });
  };

  // Function to clear cart and add product after confirmation
  function clearCartAndAddProduct(productId, product_size, product_color, quantity) {
    var baseUrlLive = sessionStorage.getItem('baseUrlLive');
    var userId = localStorage.getItem('user_id');

    // AJAX call to clear cart
    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/cart-clear',
      data: {
        user_id: userId // Replace with actual user_id
      },
      dataType: 'json',
      success: function (response) {
        // Log success message or handle accordingly
        console.log('Cart cleared successfully:', response);

        // Add product to cart after clearing
        addProductToCart(userId, productId, product_size, product_color, quantity);
      },
      error: function (xhr, status, error) {
        console.error('Error clearing cart:', xhr.status, error);

        // Handle error (e.g., show error message)
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to clear cart. Please try again later.'
        });
      }
    });
  }
});

function hidePagination() {
  $('#pagelist').hide();
}
