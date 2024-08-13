  <!-- Header -->
  <style>
      #suggestions-box {
          position: absolute;
          background: white;
          border: 1px solid #ccc;
          width: calc(100% - 30px);
          /* Adjust based on your input padding/margin */
          max-height: 200px;
          overflow-y: auto;
          z-index: 1000;
          display: none;
      }

      .suggestion-item {
          padding: 10px;
          cursor: pointer;
      }

      .suggestion-item:hover,
      .suggestion-active {
          background-color: #f0f0f0;
      }

      .no-results {
          color: #999;
      }

      .nav-link {
          text-decoration: none;
          padding: 10px;
      }

      .nav-link.active {

          font-weight: bold !important;
          color: #00438f !important;
          border-bottom: 2px solid #00438f !important;
          /* Optional: change color to see effect */
      }
  </style>
  <header>
      <div class="container">
          <div class="row">
              <div class="col-lg-12">
                  <div class="top-header">
                      <div class="cr-logo">
                          <a href="/">
                              <img src="/assets/images/sitreg.png" alt="logo" class="logo"
                                  style="height: 46px; object-fit: contain;">
                              <img src="/assets/images/sitreg.png" alt="logo" class="dark-logo"
                                  style="height: 46px; object-fit: contain;">
                          </a>
                      </div>
                      <form class="cr-search">
                          <input class="search-input" type="text" placeholder="Search For items...">
                          <div id="suggestions-box"></div>
                          {{-- <select class="form-select" aria-label="Default select example">
                              <option selected>All Categories</option>
                              <option value="1">Mens</option>
                              <option value="2">Womens</option>
                              <option value="3">Electronics</option>
                          </select> --}}
                          <a href="javascript:void(0)" class="search-btn">
                              <i class="ri-search-line"></i>
                          </a>
                      </form>
                      <div class="cr-right-bar">
                          <ul class="navbar-nav">
                              <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle cr-right-bar-item account-link"
                                      href="javascript:void(0)">
                                      <i class="ri-user-3-line"></i>
                                      <span>Account</span>
                                  </a>
                                  <ul class="dropdown-menu">
                                      <li>
                                          <a class="dropdown-item profile-data" href="javascript:void(0)">
                                              <i class="ri-settings-3-line"></i> <!-- Icon for Profile Settings -->
                                              Profile Settings
                                          </a>
                                      </li>
                                      <li>
                                          <a class="dropdown-item order-data" href="javascript:void(0)">
                                              <i class="ri-file-list-3-line"></i> <!-- Icon for My Orders -->
                                              My Orders
                                          </a>
                                      </li>
                                      <li>
                                          <a class="dropdown-item wallet-data" href="javascript:void(0)">
                                              <i class="ri-wallet-3-line"></i> <!-- Icon for Wallet -->
                                              Wallet
                                          </a>
                                      </li>
                                      <li>
                                          <a class="dropdown-item coupon-data" href="javascript:void(0)">
                                              <i class="ri-coupon-3-line"></i> <!-- Icon for Coupons -->
                                              Coupons
                                          </a>
                                      </li>
                                      {{-- <li>
                                          <a class="dropdown-item" href="/loyalty-points">
                                              <i class="ri-medal-line"></i>
                                              Loyalty Points
                                          </a>
                                      </li> --}}
                                      <li>
                                          <a class="dropdown-item refer-data" href="javascript:void(0)">
                                              <i class="ri-user-shared-line"></i> <!-- Icon for Referral Code -->
                                              Referral Code
                                          </a>
                                      </li>
                                      <li>
                                          <a class="dropdown-item" href="#" id="logout-link">
                                              <i class="ri-logout-box-r-line"></i> <!-- Icon for Logout -->
                                              Logout
                                          </a>
                                      </li>
                                      <li>
                                          <a class="dropdown-item login-data" href="javascript:void(0)" id="login-link">
                                              <i class="ri-logout-box-r-line"></i> <!-- Icon for Logout -->
                                              Login
                                          </a>
                                      </li>
                                  </ul>
                              </li>
                          </ul>
                          <a href="javascript:void(0)" class="cr-right-bar-item wishlist-link">
                              <i class="ri-heart-3-line"></i>
                              <span>Wishlist</span>
                          </a>
                          <a href="javascript:void(0)" class="cr-right-bar-item Shopping-toggle"
                              style="position: relative">
                              <span class="cart-icon-container">
                                  <i class="ri-shopping-cart-line" onclick="getCartList();"></i>
                              </span>
                              <!-- Dynamic cart item count -->
                              <span id="cartItemCount"
                                  style="position: absolute; top: 0px; right: -2px; border-radius: 50%; background-color: #000; color: #fff; padding: 0.10em 0.4em; font-size: 0.8em;"></span>
                          </a>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      <div class="cr-fix" id="cr-main-menu-desk">
          <div class="container">
              <div class="cr-menu-list">
                  <div class="cr-category-icon-block">
                      <div class="cr-category-menu">
                          {{-- <div class="cr-category-toggle">
                              <i class="ri-menu-2-line"></i>
                          </div> --}}
                      </div>
                      <div class="cr-cat-dropdown">
                          <div class="cr-cat-block">
                              <div class="cr-cat-tab">
                                  <div class="cr-tab-list nav flex-column nav-pills" id="v-pills-tab" role="tablist"
                                      aria-orientation="vertical">
                                      <button class="nav-link active" id="v-pills-home-tab" data-bs-toggle="pill"
                                          data-bs-target="#v-pills-home" type="button" role="tab"
                                          aria-controls="v-pills-home" aria-selected="true">
                                          Dairy &amp; Bakery</button>
                                      <button class="nav-link" id="v-pills-profile-tab" data-bs-toggle="pill"
                                          data-bs-target="#v-pills-profile" type="button" role="tab"
                                          aria-controls="v-pills-profile" aria-selected="false" tabindex="-1">
                                          Fruits &amp; Vegetable</button>
                                      <button class="nav-link" id="v-pills-messages-tab" data-bs-toggle="pill"
                                          data-bs-target="#v-pills-messages" type="button" role="tab"
                                          aria-controls="v-pills-messages" aria-selected="false" tabindex="-1">
                                          Snack &amp; Spice</button>
                                      <button class="nav-link" id="v-pills-settings-tab" data-bs-toggle="pill"
                                          data-bs-target="#v-pills-settings" type="button" role="tab"
                                          aria-controls="v-pills-settings" aria-selected="false" tabindex="-1">
                                          Juice &amp; Drinks </button>
                                      <a class="nav-link" href="shop-left-sidebar.html">
                                          View All </a>
                                  </div>
                                  <div class="tab-content" id="v-pills-tabContent">
                                      <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                                          aria-labelledby="v-pills-home-tab">
                                          <div class="tab-list row">
                                              <div class="col">
                                                  <h6 class="cr-col-title">Dairy</h6>
                                                  <ul class="cat-list">
                                                      <li><a href="shop-left-sidebar.html">Milk</a></li>
                                                      <li><a href="shop-left-sidebar.html">Ice cream</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Cheese</a></li>
                                                      <li><a href="shop-left-sidebar.html">Frozen
                                                              custard</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Frozen
                                                              yogurt</a>
                                                      </li>
                                                  </ul>
                                              </div>
                                              <div class="col">
                                                  <h6 class="cr-col-title">Bakery</h6>
                                                  <ul class="cat-list">
                                                      <li><a href="shop-left-sidebar.html">Cake and
                                                              Pastry</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Rusk Toast</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Bread &amp;
                                                              Buns</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Chocolate
                                                              Brownie</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Cream Roll</a>
                                                      </li>
                                                  </ul>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="tab-pane fade" id="v-pills-profile" role="tabpanel"
                                          aria-labelledby="v-pills-profile-tab">
                                          <div class="tab-list row">
                                              <div class="col">
                                                  <h6 class="cr-col-title">Fruits</h6>
                                                  <ul class="cat-list">
                                                      <li><a href="shop-left-sidebar.html">Cauliflower</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Bell
                                                              Peppers</a></li>
                                                      <li><a href="shop-left-sidebar.html">Broccoli</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Cabbage</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Tomato</a></li>
                                                  </ul>
                                              </div>
                                              <div class="col">
                                                  <h6 class="cr-col-title">Vegetable</h6>
                                                  <ul class="cat-list">
                                                      <li><a href="shop-left-sidebar.html">Cauliflower</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Bell
                                                              Peppers</a></li>
                                                      <li><a href="shop-left-sidebar.html">Broccoli</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Cabbage</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Tomato</a></li>
                                                  </ul>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="tab-pane fade" id="v-pills-messages" role="tabpanel"
                                          aria-labelledby="v-pills-messages-tab">
                                          <div class="tab-list row">
                                              <div class="col">
                                                  <h6 class="cr-col-title">Snacks</h6>
                                                  <ul class="cat-list">
                                                      <li><a href="shop-left-sidebar.html">French
                                                              fries</a></li>
                                                      <li><a href="shop-left-sidebar.html">potato
                                                              chips</a></li>
                                                      <li><a href="shop-left-sidebar.html">Biscuits &amp;
                                                              Cookies</a></li>
                                                      <li><a href="shop-left-sidebar.html">Popcorn</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Rice Cakes</a>
                                                      </li>
                                                  </ul>
                                              </div>
                                              <div class="col">
                                                  <h6 class="cr-col-title">Spice</h6>
                                                  <ul class="cat-list">
                                                      <li><a href="shop-left-sidebar.html">Cinnamon
                                                              Powder</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Cumin
                                                              Powder</a></li>
                                                      <li><a href="shop-left-sidebar.html">Fenugreek
                                                              Powder</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Pepper
                                                              Powder</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Long Pepper</a>
                                                      </li>
                                                  </ul>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="tab-pane fade" id="v-pills-settings" role="tabpanel"
                                          aria-labelledby="v-pills-settings-tab">
                                          <div class="tab-list row">
                                              <div class="col">
                                                  <h6 class="cr-col-title">Juice</h6>
                                                  <ul class="cat-list">
                                                      <li><a href="shop-left-sidebar.html">Mango Juice</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Coconut
                                                              Water</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Tetra Pack</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Apple
                                                              Juices</a></li>
                                                      <li><a href="shop-left-sidebar.html">Lychee
                                                              Juice</a></li>
                                                  </ul>
                                              </div>
                                              <div class="col">
                                                  <h6 class="cr-col-title">soft drink</h6>
                                                  <ul class="cat-list">
                                                      <li><a href="shop-left-sidebar.html">Breizh Cola</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Green Cola</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Jolt Cola</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Mecca Cola</a>
                                                      </li>
                                                      <li><a href="shop-left-sidebar.html">Topsia Cola</a>
                                                      </li>
                                                  </ul>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
                  <nav class="navbar navbar-expand-lg">
                      <a href="javascript:void(0)" class="navbar-toggler shadow-none">
                          <i class="ri-menu-3-line"></i>
                      </a>
                      <div class="cr-header-buttons">
                          <ul class="navbar-nav">
                              <li class="nav-item dropdown">
                                  <a class="nav-link show-account" href="javascript:void(0)">
                                      <i class="ri-user-3-line"></i>
                                  </a>
                                  <ul class="dropdown-menu">
                                      <li>
                                          <a class="dropdown-item profile-data-phone">
                                              <i class="ri-settings-3-line"></i> <!-- Icon for Profile Settings -->
                                              Profile Settings
                                          </a>
                                      </li>
                                      <li>
                                          <a class="dropdown-item order-data-phone">
                                              <i class="ri-file-list-3-line"></i> <!-- Icon for My Orders -->
                                              My Orders
                                          </a>
                                      </li>
                                      <li>
                                          <a class="dropdown-item wallet-data-phone">
                                              <i class="ri-wallet-3-line"></i> <!-- Icon for Wallet -->
                                              Wallet
                                          </a>
                                      </li>
                                      <li>
                                          <a class="dropdown-item coupon-data-phone">
                                              <i class="ri-coupon-3-line"></i> <!-- Icon for Coupons -->
                                              Coupons
                                          </a>
                                      </li>
                                      {{-- <li>
                                          <a class="dropdown-item" href="/loyalty-points">
                                              <i class="ri-medal-line"></i> <!-- Icon for Loyalty Points -->
                                              Loyalty Points
                                          </a>
                                      </li> --}}
                                      <li>
                                          <a class="dropdown-item refer-data-phone">
                                              <i class="ri-user-shared-line"></i> <!-- Icon for Referral Code -->
                                              Referral Code
                                          </a>
                                      </li>
                                      <li>
                                          <a class="dropdown-item" href="#" id="logout-phone">
                                              <i class="ri-logout-box-r-line"></i> <!-- Icon for Logout -->
                                              Logout
                                          </a>
                                      </li>
                                  </ul>
                              </li>
                          </ul>
                          <a href="javascript:void(0)" class="cr-right-bar-item show-wishlist">
                              <i class="ri-heart-line"></i>
                          </a>
                          <a href="javascript:void(0)" class="cr-right-bar-item Shopping-toggle">
                              <i class="ri-shopping-cart-line" onclick="getCartList();"></i>
                          </a>
                      </div>
                      <div class="navbar-collapse" id="navbarSupportedContent">
                          <ul class="navbar-nav">
                              <li class="nav-item">
                                  <a class="nav-link home-link" href="javascript:void(0)" data-link="home" id="translatehome">Home</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link category-link" href="javascript:void(0)"
                                      data-link="category" id="translatecategory">Category</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link products-link" href="javascript:void(0)"
                                      data-link="products" id="translateproducts">Products</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link stores-link" href="javascript:void(0)"
                                      data-link="stores" id="translatestores">Stores</a>
                              </li>
                              <li class="nav-item dropdown">
                                  <a class="nav-link dropdown-toggle" href="javascript:void(0)">Module</a>
                                  <ul class="dropdown-menu" id="moduleDropdown">
                                      {{-- Dynamic Module List --}}
                                  </ul>
                              </li>
                          </ul>
                      </div>
                  </nav>
                  <div class="cr-calling">
                      {{-- <i class="ri-phone-line"></i>
                      <a href="javascript:void(0)">+123 ( 456 ) ( 7890 )</a> --}}
                  </div>
              </div>
          </div>
      </div>
  </header>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  {{-- Cart Count --}}
  <script>
      // Function to update the cart count dynamically
      function updateCartCount(cartCount) {
          document.getElementById('cartItemCount').textContent = cartCount;
      }

      // Function to fetch product list and update cart count
      function fetchProductList() {
          var baseUrlLive = sessionStorage.getItem('baseUrlLive');
          var userId = localStorage.getItem('user_id');
          var module_id = localStorage.getItem('module_id');

          if (!module_id) {
              console.log('No module_id found. Hiding cart count icon.');
              document.getElementById('cartItemCount').style.display = 'none';
              return;
          }

          $.ajax({
              type: 'POST',
              url: baseUrlLive + '/api/web-home',
              data: {
                  user_id: userId,
                  module_id: module_id
              },
              dataType: 'json',
              success: function(response) {
                  // Check if cartCount data exists in the response
                  if (response.cartCount !== undefined) {
                      console.log('Cart count found:', response.cartCount);
                      updateCartCount(response.cartCount);
                  } else {
                      console.error('No cart count found');
                  }
              },
              error: function(xhr, status, error) {
                  console.error('AJAX error: ' + error);
              }
          });
      }

      // Call fetchProductList function when the page is loaded
      $(document).ready(function() {
          fetchProductList();
      });
  </script>

  <script>
      function ShowFirstName() {
          var token = localStorage.getItem('token');
          let baseUrlLive = sessionStorage.getItem('baseUrlLive');
          $.ajax({
              type: 'GET',
              url: baseUrlLive + '/api/edit-profile',
              dataType: 'json',
              headers: {
                  Authorization: `Bearer ${token}`
              },
              success: function(response) {
                  if (response && response.user) {
                      console.log('First name found:', response.user.first_name);
                      DisplayFirstName(response.user);
                  } else {
                      console.error('No user found');
                      DisplayFirstName(null); // Display default message if no user found
                  }
              },
              error: function(xhr, status, error) {
                  console.error('AJAX error: ' + error);
                  DisplayFirstName(null); // Display default message in case of error
              }
          });
      }

      function DisplayFirstName(user) {
          // Extracting the user's first name or setting default if not present
          var firstName = user && user.first_name ? user.first_name : 'Guest';

          // Displaying the greeting message with the user's first name
          var greetingMessage = "Hi, " + firstName;

          // Updating the HTML content to show the greeting message
          $(".cr-right-bar .navbar-nav .dropdown-toggle span").text(greetingMessage);
      }

      // Call ShowFirstName function when the page is loaded
      $(document).ready(function() {
          ShowFirstName();
      });
  </script>

  {{-- Logout --}}
  <script>
      // Wait for the DOM to be fully loaded before attaching event listeners
      document.addEventListener('DOMContentLoaded', function() {
          // Get the logout link by its ID
          var logoutLink = document.getElementById('logout-link');

          // Check if the logout link exists
          if (logoutLink) {
              // Attach click event listener to the logout link
              logoutLink.addEventListener('click', function(event) {
                  // Prevent the default action of the link
                  event.preventDefault();

                  // Remove user data from localStorage
                  localStorage.removeItem('user_id');
                  localStorage.removeItem('token');
                  localStorage.removeItem('module_id');

                  // Redirect the user to the home page
                  window.location.href = "/";
              });
          }
      });
  </script>

  {{-- Logout for Phone --}}
  <script>
      // Wait for the DOM to be fully loaded before attaching event listeners
      document.addEventListener('DOMContentLoaded', function() {
          // Get the logout link by its ID
          var logoutLink = document.getElementById('logout-phone');

          // Check if the logout link exists
          if (logoutLink) {
              // Attach click event listener to the logout link
              logoutLink.addEventListener('click', function(event) {
                  // Prevent the default action of the link
                  event.preventDefault();

                  // Remove user data from localStorage
                  localStorage.removeItem('user_id');
                  localStorage.removeItem('token');
                  localStorage.removeItem('module_id');

                  // Redirect the user to the home page
                  window.location.href = "/";
              });
          }
      });
  </script>


  {{-- Search Suggestions --}}
  <script>
      let searchTimeout;
      let currentFocus = -1;

      function fetchSuggestions(query) {
          var user_id = localStorage.getItem('user_id');
          let baseUrlLive = sessionStorage.getItem('baseUrlLive');

          $.ajax({
              type: 'POST',
              url: baseUrlLive + '/api/product-search',
              data: JSON.stringify({
                  user_id: user_id,
                  product_name: query
              }),
              contentType: 'application/json',
              dataType: 'json',
              success: function(response) {
                  if (response && response.product && response.product.length > 0) {
                      // Filter products whose names start with the query
                      let filteredProducts = response.product.filter(product =>
                          product.product_name.toLowerCase().startsWith(query.toLowerCase())
                      );
                      if (filteredProducts.length > 0) {
                          displaySuggestions(filteredProducts);
                      } else {
                          displayNoResults();
                      }
                  } else {
                      displayNoResults();
                  }
              },
              error: function(xhr, status, error) {
                  console.error('AJAX error: ' + error);
                  displayNoResults();
              }
          });
      }

      function SearchItems() {
          var user_id = localStorage.getItem('user_id');
          var product_name = document.querySelector('.search-input').value.trim();
          var baseUrlLive = sessionStorage.getItem('baseUrlLive');

          // Ensure product_name is not empty
          if (!product_name) {
              console.error('Please enter a product name.');
              return;
          }

          var module_id = localStorage.getItem('module_id');
          if (!module_id) {
              Swal.fire({
                  icon: 'info',
                  title: 'Module Selection Required',
                  text: 'Please select a module to continue.',
                  confirmButtonText: 'OK'
              });
              return;
          }

          $.ajax({
              type: 'POST',
              url: baseUrlLive + '/api/product-search',
              data: JSON.stringify({
                  user_id: user_id,
                  product_name: product_name
              }),
              contentType: 'application/json',
              dataType: 'json',
              success: function(response) {
                  if (response && response.product && response.product.length > 0) {
                      console.log('Products found:', response.product);
                      hidePagination();

                      var product = response.product[0]; // First product in the response

                      // Store product details in sessionStorage
                      sessionStorage.setItem('product_id', product.id);
                      sessionStorage.setItem('store_id', product.store_id);
                      sessionStorage.setItem('is_product_size', product.product_size.length != 0);
                      sessionStorage.setItem('is_color', product.product_color.length != 0);

                      // Redirect to product detail page
                      var productName = product
                          .product_name; // Assuming product_name is used to construct the route
                      var productDetailUrl = `product-detail/${stringToSlug(productName)}`;
                      productDetailUrl = productDetailUrl.replace(':name', encodeURIComponent(productName));
                      window.location.href = productDetailUrl;
                  } else {
                      console.error('No products found');
                      // Handle case where no products are found
                  }
              },
              error: function(xhr, status, error) {
                  console.error('AJAX error:', error);
                  // Handle AJAX errors appropriately
              }
          });
      }

      function stringToSlug(inputString) {
          // Remove leading and trailing whitespace and convert to lowercase
          const cleanedString = inputString.trim().toLowerCase();

          // Replace spaces with hyphens
          const slug = cleanedString.replace(/\s+/g, '-');

          return slug;
      }

      function displaySuggestions(products) {
          var suggestionsBox = $('#suggestions-box');
          suggestionsBox.empty();
          products.forEach((product, index) => {
              suggestionsBox.append('<div class="suggestion-item" onclick="selectSuggestion(\'' + product
                  .product_name + '\')" data-index="' + index + '">' + product.product_name + '</div>');
          });
          suggestionsBox.show();
      }

      function displayNoResults() {
          var suggestionsBox = $('#suggestions-box');
          suggestionsBox.empty();
          suggestionsBox.append('<div class="suggestion-item no-results">No results found</div>');
          suggestionsBox.show();
      }

      function clearSuggestions() {
          $('#suggestions-box').empty().hide();
      }

      function selectSuggestion(productName) {
          $('.search-input').val(productName);
          clearSuggestions();
          SearchItems();
      }

      function addActive(items) {
          if (!items) return false;
          removeActive(items);
          if (currentFocus >= items.length) currentFocus = 0;
          if (currentFocus < 0) currentFocus = (items.length - 1);
          $(items[currentFocus]).addClass("suggestion-active");
      }

      function removeActive(items) {
          for (var i = 0; i < items.length; i++) {
              $(items[i]).removeClass("suggestion-active");
          }
      }

      document.querySelector('.search-input').addEventListener('keyup', function(event) {
          const query = event.target.value;
          if (query.length >= 2) {
              clearTimeout(searchTimeout);
              searchTimeout = setTimeout(() => fetchSuggestions(query), 100);
          } else {
              clearSuggestions();
          }

          const suggestionItems = document.querySelectorAll('.suggestion-item');

          if (event.key === 'ArrowDown') {
              currentFocus++;
              addActive(suggestionItems);
          } else if (event.key === 'ArrowUp') {
              currentFocus--;
              addActive(suggestionItems);
          } else if (event.key === 'Enter') {
              event.preventDefault();
              if (currentFocus > -1) {
                  if (suggestionItems) suggestionItems[currentFocus].click();
              } else {
                  clearSuggestions();
                  SearchItems();
              }
          }
      });

      document.querySelector('.search-btn').addEventListener('click', function(event) {
          event.preventDefault();
          clearSuggestions();
          SearchItems();
      });

      function hidePagination() {
          $('#pagelist').hide();
      }
  </script>





  {{-- <script>
      document.addEventListener('DOMContentLoaded', function() {
          function checkModuleId() {
              if (!localStorage.getItem('module_id')) {
                  Swal.fire({
                      icon: 'info',
                      title: 'Module Selection Required',
                      text: 'Please select a module to continue.',
                      confirmButtonText: 'OK'
                  });
                  return false;
              }
              return true;
          }

            function checkuserID() {
              if (!localStorage.getItem('user_id')) {
                  Swal.fire({
                      icon: 'info',
                      title: 'kichi kichi',
                      text: 'Please select a module to continue.',
                      confirmButtonText: 'OK'
                  });
                  return false;
              }
              return true;
          }

          document.querySelector('.account-link').addEventListener('click', function() {
              if (checkModuleId()) {
                  window.location.href = '/profile';
              }
          });

          document.querySelector('.wishlist-link').addEventListener('click', function() {
              if (checkModuleId()) {
                  window.location.href = '/wishlist';
              }
          });
          document.querySelector('.home-link').addEventListener('click', function() {
              if (checkModuleId()) {
                  window.location.href = '/shop';
              }
          });

          document.querySelector('.category-link').addEventListener('click', function() {
              if (checkModuleId()) {
                  window.location.href = '/categories';
              }
          });

          document.querySelector('.products-link').addEventListener('click', function() {
              if (checkModuleId()) {
                  window.location.href = '/productlist';
              }
          });

          document.querySelector('.stores-link').addEventListener('click', function() {
              if (checkModuleId()) {
                  window.location.href = '/storelist';
              }
          });
          document.querySelector('.show-account').addEventListener('click', function() {
              if (checkModuleId()) {
                  window.location.href = '/profile';
              }
          });
          document.querySelector('.show-wishlist').addEventListener('click', function() {
              if (checkModuleId()) {
                  window.location.href = '/wishlist';
              }
          });
      });
  </script> --}}




  <script>
      document.addEventListener('DOMContentLoaded', function() {
          // Function to check if the module_id exists in localStorage
          function checkModuleId() {
              if (!localStorage.getItem('module_id')) {
                  Swal.fire({
                      icon: 'info',
                      title: 'Module Selection Required',
                      text: 'Please select a module to continue.',
                      confirmButtonText: 'OK'
                  });
                  return false;
              }
              return true;
          }

          // Function to check if the user_id exists in localStorage
          function checkUserId() {
              if (!localStorage.getItem('token')) {
                  Swal.fire({
                      text: 'You need to log in first.',
                      icon: 'info',
                      showCloseButton: true,
                      confirmButtonText: 'OK'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          window.location.href = '/register';
                      }
                  });
                  return false;
              }
              return true;
          }


          // List of links and their corresponding URLs
          const links = [{
                  selector: '.account-link',
                  url: '/profile',
                  checkUser: true
              },
              {
                  selector: '.wishlist-link',
                  url: '/wishlist',
                  checkUser: true
              },
              {
                  selector: '.profile-data',
                  url: '/profile',
                  checkUser: true
              },
              {
                  selector: '.order-data',
                  url: '/orders',
                  checkUser: true
              },
              {
                  selector: '.wallet-data',
                  url: '/wallet',
                  checkUser: true
              },
              {
                  selector: '.coupon-data',
                  url: '/coupons',
                  checkUser: true
              },
              {
                  selector: '.refer-data',
                  url: '/referralCode',
                  checkUser: true
              },
              {
                  selector: '.profile-data-phone',
                  url: '/profile',
                  checkUser: true
              },
              {
                  selector: '.order-data-phone',
                  url: '/orders',
                  checkUser: true
              },
              {
                  selector: '.wallet-data-phone',
                  url: '/wallet',
                  checkUser: true
              },
              {
                  selector: '.coupon-data-phone',
                  url: '/coupons',
                  checkUser: true
              },
              {
                  selector: '.refer-data-phone',
                  url: '/referralCode',
                  checkUser: true
              },
              {
                  selector: '.home-link',
                  url: '/shop'
              },
              {
                  selector: '.category-link',
                  url: '/categories'
              },
              {
                  selector: '.products-link',
                  url: '/productlist'
              },
              {
                  selector: '.stores-link',
                  url: '/storelist'
              },
              {
                  selector: '.show-account',
                  url: '/profile',
                  checkUser: true
              },
              {
                  selector: '.show-wishlist',
                  url: '/wishlist'
              },
              {
                  selector: '.login-data',
                  url: '/register'
              }
          ];

          // Add event listeners to the links
          links.forEach(link => {
              const element = document.querySelector(link.selector);
              if (element) {
                  element.addEventListener('click', function() {
                      if (checkModuleId()) {
                          if (link.checkUser && !checkUserId()) {
                              return;
                          }
                          window.location.href = link.url;
                      }
                  });
              }
          });
      });
  </script>


  <script>
      document.addEventListener('DOMContentLoaded', function() {
          var logoutLink = document.getElementById('logout-link');
          var userId = localStorage.getItem('token');

          if (!userId) {
              logoutLink.style.display = 'none';
          } else {
              logoutLink.style.display = 'block';
          }
      });
  </script>


  <script>
      document.addEventListener('DOMContentLoaded', function() {
          var logoutLink = document.getElementById('login-link');
          var userId = localStorage.getItem('token');

          if (userId) {
              logoutLink.style.display = 'none';
          } else {
              logoutLink.style.display = 'block';
          }
      });
  </script>

  {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
  <script>
      function getModulelist() {
          var baseUrlLive = sessionStorage.getItem('baseUrlLive');

          $.ajax({
              type: 'GET',
              url: baseUrlLive + '/api/get-module',
              data: {},
              dataType: 'json',
              success: function(response) {
                  if (response.success && response.data.length > 0) {
                      var moduleDropdown = $('#moduleDropdown');
                      moduleDropdown.empty(); // Clear existing items

                      response.data.forEach(function(module) {
                          var listItem = $('<li>');
                          var link = $('<a>')
                              .addClass('dropdown-item')
                              .attr('href', 'javascript:void(0);') // Prevent default navigation
                              .text(module.name)
                              .attr('data-module-id', module.id) // Use attr to set the data attribute
                              .on('click', function(event) {
                                  event.preventDefault(); // Prevent default action of the link
                                  // Store the module_id in local storage when the item is clicked
                                  var moduleId = $(this).attr(
                                      'data-module-id'); // Use attr to get the data attribute
                                  localStorage.setItem('module_id', moduleId);
                                  console.log('Module ID stored:', moduleId);

                                  // Redirect to /shop
                                  window.location.href = '/shop';
                                  // location.reload();
                              });

                          listItem.append(link);
                          moduleDropdown.append(listItem);
                      });
                  }
              },
              error: function(xhr, status, error) {
                  console.error('AJAX error: ' + error);
              }
          });
      }

      // Call the function to fetch and display the module list
      getModulelist();
  </script>


  {{-- Base Url --}}
  <script>
      let baseUrlLive = '{{ env('BASE_URL_LIVE') }}';
      sessionStorage.setItem('baseUrlLive', baseUrlLive);
  </script>


  <script>
      document.addEventListener('DOMContentLoaded', function() {
          const navLinks = document.querySelectorAll('.nav-link');
          const activeLink = sessionStorage.getItem('activeNavLink');
          const moduleId = localStorage.getItem('module_id');

          // Remove active class from all links
          navLinks.forEach(link => link.classList.remove('active'));

          // If there's an active link in localStorage and module_id exists, add the active class to it
          if (activeLink && moduleId) {
              const activeNavLink = Array.from(navLinks).find(link => link.getAttribute('data-link') ===
                  activeLink);
              if (activeNavLink) {
                  activeNavLink.classList.add('active');
              }
          }

          // Add click event listener to all links
          navLinks.forEach(link => {
              link.addEventListener('click', function() {
                  // Check if module_id is available
                  if (localStorage.getItem('module_id')) {
                      // Save the clicked link to localStorage
                      sessionStorage.setItem('activeNavLink', this.getAttribute('data-link'));

                      // Remove active class from all links
                      navLinks.forEach(nav => nav.classList.remove('active'));
                      // Add active class to the clicked link
                      this.classList.add('active');
                  } else {
                      // Optionally, remove the active class if module_id is not available
                      navLinks.forEach(nav => nav.classList.remove('active'));
                  }
              });
          });
      });
  </script>

  <script>
      document.addEventListener('DOMContentLoaded', function() {

          fetch('/assets/json/translations_ar.json')
              .then(response => {
                  console.log(response, "response");

                  return response.json();
              })
              .then(translations => {
                  document.querySelector('.home-link').textContent = translations.home;
                  document.querySelector('.category-link').textContent = translations.category;
                  document.querySelector('.products-link').textContent = translations.products;
                  document.querySelector('.stores-link').textContent = translations.stores;
                  document.querySelector('.nav-link.dropdown-toggle').textContent = translations.module;
              })
              .catch(error => console.error('Error loading translation file:', error));
      });
  </script>
