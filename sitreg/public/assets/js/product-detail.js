// window.getSingleProductDetails = function () {
//   var productId = sessionStorage.getItem('product_id');
//   var baseUrlLive = sessionStorage.getItem('baseUrlLive');

//   $.ajax({
//     type: 'POST',
//     url: baseUrlLive + '/api/productdetail',
//     data: { product_id: productId },
//     dataType: 'json',
//     success: function (response) {
//       if (response && response.message === 'Product Found') {
//         response.product.totalAvgReview = parseFloat(response.totalAvgReview);
//         response.product.totalUserCount = parseFloat(response.totalUserCount);

//         // Update the h2 tag with the truncated product name
//         $('#product-name').text(response.product.product_name);
//         displaySingleProductDetails(response.product);
//         displayProductInformation(response.product, response.productReviews);
//         // Display dynamic images
//         displayDynamicImages(response.product);
//         // Call the function to initialize and populate the carousel
//         // initCarousel(response.product.product_image);
//       } else {
//         console.error('No product found or error in response:', response);
//       }
//     },
//     error: function (xhr, status, error) {
//       console.error('AJAX error: ' + xhr.status + ' ' + error);
//     }
//   });
// };

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
var productId = queryParams['product_id'];
sessionStorage.setItem('product_id', productId); // Optional: store in sessionStorage

// Updated getSingleProductDetails function
window.getSingleProductDetails = function () {
  var productId = sessionStorage.getItem('product_id');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/productdetail',
    data: { product_id: productId },
    dataType: 'json',
    success: function (response) {
      if (response && response.message === 'Product Found') {
        response.product.totalAvgReview = parseFloat(response.totalAvgReview);
        response.product.totalUserCount = parseFloat(response.totalUserCount);

        // Update the h2 tag with the truncated product name
        $('#product-name').text(response.product.product_name);

        displaySingleProductDetails(response.product);
        displayProductInformation(response.product, response.productReviews);
        // Display dynamic images
        displayDynamicImages(response.product);
        // Call the function to initialize and populate the carousel
        // initCarousel(response.product.product_image);
      } else {
        console.error('No product found or error in response:', response);
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + xhr.status + ' ' + error);
    }
  });
};

// // Call the function to fetch product details
// getSingleProductDetails();

// function displayDynamicImages(product) {
//   // Check if product_image is defined and not empty
//   if (product.product_image && product.product_image.length > 0) {
//     var sliderFor = $('.slider-for');
//     var sliderNav = $('.slider-nav');

//     // Clear existing slides (if any)
//     sliderFor.slick('unslick').empty();
//     sliderNav.slick('unslick').empty();

//     // Loop through each image URL and add to sliders
//     product.product_image.forEach(function (imageUrl) {
//       // Update main slider
//       sliderFor.append('<div><img src="' + imageUrl + '" alt="product-image"></div>');

//       // Update thumbnail slider
//       sliderNav.append('<div><img src="' + imageUrl + '" alt="product-thumb"></div>');
//     });

//     // Initialize sliders
//     sliderFor.slick({
//       slidesToShow: 1,
//       slidesToScroll: 1,
//       arrows: false,
//       fade: true,
//       asNavFor: '.slider-nav'
//     });

//     sliderNav.slick({
//       slidesToShow: 3,
//       slidesToScroll: 1,
//       asNavFor: '.slider-for',
//       dots: false,
//       centerMode: true,
//       focusOnSelect: true
//     });

//     // Set dimensions for images
//     $('.slider-for img').css({
//       'max-width': '100%',
//       height: 'auto'
//     });

//     $('.slider-nav img').css({
//       'max-width': '100%',
//       height: 'auto'
//     });

//     // Initialize jQuery Zoom
//     $('.zoom-image-hover').zoom();
//   } else {
//     console.error('No product images found or empty array:', product.product_image);
//     // Handle the case where no images are found, maybe display a default image or message
//   }
// }

// function displayDynamicImages(product) {
//   // Check if product_image is defined and not empty
//   if (product.product_image && product.product_image.length > 0) {
//     var sliderFor = $('.slider-for');
//     var sliderNav = $('.slider-nav');

//     // Clear existing slides (if any)
//     if (sliderFor.hasClass('slick-initialized')) {
//       sliderFor.slick('unslick');
//     }
//     if (sliderNav.hasClass('slick-initialized')) {
//       sliderNav.slick('unslick');
//     }

//     sliderFor.empty();
//     sliderNav.empty();

//     // Loop through each image URL and add to sliders
//     product.product_image.forEach(function (imageUrl) {
//       // Update main slider
//       sliderFor.append('<div><img src="' + imageUrl + '" alt="product-image"></div>');

//       // Update thumbnail slider
//       sliderNav.append('<div><img src="' + imageUrl + '" alt="product-thumb"></div>');
//     });

//     // Initialize sliders
//     sliderFor.slick({
//       slidesToShow: 1,
//       slidesToScroll: 1,
//       arrows: false,
//       fade: true,
//       asNavFor: '.slider-nav'
//     });

//     if (product.product_image.length > 1) {
//       sliderNav.slick({
//         slidesToShow: 6,
//         slidesToScroll: 1,
//         asNavFor: '.slider-for',
//         dots: false,
//         centerMode: false,
//         focusOnSelect: true
//       });
//     } else {
//       sliderNav.hide();
//     }

//     // Set dimensions for images
//     $('.slider-for img').css({
//       'max-width': '100%',
//       height: 'auto'
//     });

//     $('.slider-nav img').css({
//       'max-width': '100%',
//       height: 'auto'
//     });

//     // Initialize jQuery Zoom
//     $('.zoom-image-hover').zoom();
//   } else {
//     console.error('No product images found or empty array:', product.product_image);
//     // Handle the case where no images are found, maybe display a default image or message
//   }
// }
function displayDynamicImages(product) {
  var defaultImageUrl = 'http://127.0.0.1:8000/assets/images/product-default.png'; // Default image URL

  // Check if product_image is defined and not empty
  if (product.product_image && product.product_image.length > 0) {
    var sliderFor = $('.slider-for');
    var sliderNav = $('.slider-nav');

    // Clear existing slides (if any)
    if (sliderFor.hasClass('slick-initialized')) {
      sliderFor.slick('unslick');
    }
    if (sliderNav.hasClass('slick-initialized')) {
      sliderNav.slick('unslick');
    }

    sliderFor.empty();
    sliderNav.empty();

    // Loop through each image URL and add to sliders
    product.product_image.forEach(function (imageUrl) {
      // Update main slider
      sliderFor.append('<div><img src="' + imageUrl + '" alt="product-image"></div>');

      // Update thumbnail slider
      sliderNav.append('<div><img src="' + imageUrl + '" alt="product-thumb"></div>');
    });

    // Initialize sliders
    sliderFor.slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      fade: true,
      asNavFor: '.slider-nav'
    });

    if (product.product_image.length > 1) {
      sliderNav.slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        asNavFor: '.slider-for',
        dots: false,
        centerMode: false,
        focusOnSelect: true
      });
    } else {
      sliderNav.hide();
    }
  } else {
    console.error('No product images found or empty array:', product.product_image);
    // Display default image when no product images are available
    var sliderFor = $('.slider-for');
    var sliderNav = $('.slider-nav');

    // Clear existing slides (if any)
    if (sliderFor.hasClass('slick-initialized')) {
      sliderFor.slick('unslick');
    }
    if (sliderNav.hasClass('slick-initialized')) {
      sliderNav.slick('unslick');
    }

    sliderFor.empty();
    sliderNav.empty();

    // Display default image
    sliderFor.append('<div><img src="' + defaultImageUrl + '" alt="product-image"></div>');

    // Hide thumbnail slider since there's only one image
    sliderNav.hide();
  }

  // Set dimensions for images
  $('.slider-for img').css({
    'max-width': '100%',
    height: 'auto'
  });

  $('.slider-nav img').css({
    'max-width': '100%',
    height: 'auto'
  });

  // Initialize jQuery Zoom
  $('.zoom-image-hover').zoom();
}

// Helper function to truncate the product name
function truncateProductName(productName, wordLimit) {
  var words = productName.split(' ');
  if (words.length > wordLimit) {
    return words.slice(0, wordLimit).join(' ') + '...';
  }
  return productName;
}

function displaySingleProductDetails(product) {
  var productContent = $('#singleproductdetail');
  productContent.empty(); // Clear previous content

  var filledStars = '';
  var emptyStars = '';

  for (var i = 0; i < 5; i++) {
    if (i < product.totalAvgReview) {
      filledStars += '<i class="ri-star-fill"></i>';
    } else {
      emptyStars += '<i class="ri-star-line"></i>';
    }
  }

  var selectedSize = '';
  product.product_size.forEach(function (size, index) {
    selectedSize += `<li class="${index === 0 ? 'selected' : ''}" style="${
      index === 0 ? 'background-color: #00438F; color: white;' : ''
    }">${capitalizeFirstLetter(size)}</li>`;
  });

  var selectedColor = '';
  product.product_color.forEach(function (color, index) {
    selectedColor += `<li class="${index === 0 ? 'selected' : ''}" style="${
      index === 0 ? 'background-color: #00438F; color: white;' : ''
    }">${capitalizeFirstLetter(color)}</li>`;
  });

  var productHTML = `
                    <div class="cr-size-and-weight-contain">
                        <h2 class="heading">${product.product_name}</h2>
                        <h5 class="heading" style="font-size: 1em;
                        color: #555;">by ${product.store_name}</h5>
                        <p class="product-about">${truncateText(product.product_about, 3)}</p>
                       <a href="javascript:void(0)" id="seeMoreLink" class="see-more-link">See more</a>
                    </div>
                    <div class="cr-size-and-weight">
                        <div class="cr-review-star">
                            <div class="cr-star">
                               ${filledStars}
                               ${emptyStars}
                            </div>
                            <p>(${product.totalUserCount} Reviews)</p>
                        </div>
                        <div class="cr-product-price">
                             <span id="new-price-update" class="new-price">$${product.product_sale_price}</span>
                             <span class="old-price">$${product.product_price}</span>
                        </div>
                        <div class="cr-size-weight" id="ShowSize">
                            <h5><span>Size</span></h5>
                                <div class="cr-kg size">
                                      <ul>
                                        ${selectedSize}
                                      </ul>
                                </div>
                        </div>
                        <div class="cr-size-weight" id="ShowColor">
                              <h5><span>Color</span></h5>
                              <div class="cr-kg color">
                                      <ul>
                                      ${selectedColor}
                                      </ul>
                              </div>
                        </div>

                             <div class="cr-size-weight" id="ShowSKU">
        <h5><span>SKU</span></h5>
        <div class="cr-kg sku">
          <p>${product.product_sku}</p>
        </div>
      </div>
      <div class="cr-size-weight" id="ShowTags">
        <h5><span>Tags</span></h5>
        <div class="cr-kg tags">
          <p>${product.tag}</p>
        </div>
      </div>

                        <div id="variantErrorMessagedetail" style="color: red; display: none; padding: 5px;">The size and color you selected are unavailable. Please select an alternative.</div>
                        <div class="cr-add-card">
                            <div class="cr-qty-main">
                                <input type="number" placeholder="Quantity" value="1" min="1" max="20" class="quantity">
                                <button type="button" id="add" class="plus">+</button>
                                <button type="button" id="sub" class="minus">-</button>
                            </div>
                            <div class="cr-add-button">
                                <button type="button" class="cr-button cr-shopping-bag">Add to cart</button>
                            </div>

                            <div class="cr-card-icon">
                                <a href="javascript:void(0)" class="wishlist" onclick="sessionStorage.setItem('product_id', ${
                                  product.id
                                }); sessionStorage.setItem('store_id', ${product.store_id}); likeProduct(${
    product.id
  }, this)">
                                    <i class="ri-heart-line"></i>
                                </a>
                            </div>
                        </div>
                       <div class="cr-buy-now-button" style="padding-top: 30px;">
    <button type="button" class="cr-button cr-buy-now" style="background-color: #ff0000; color: #ffffff; border: none; padding: 10px 20px; font-size: 16px; cursor: pointer; box-shadow: 0 0 10px rgba(255, 0, 0, 0.5); transition: all 0.3s ease;" onclick="callTheBuy()">
        Buy Now
    </button>
</div>
 <div class="cr-share-section" style="margin-top: 20px;">
        <span style="font-weight: bold;">Share with:</span>
        <div class="cr-share-icons" style="display: flex; gap: 10px; margin-top: 10px;">
            <a href="https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(
              window.location.href
            )}" target="_blank" class="share-circle" style="background-color: #3b5998;"><i class="ri-facebook-fill"></i></a>
            <a href="https://twitter.com/intent/tweet?url=${encodeURIComponent(
              window.location.href
            )}" target="_blank" class="share-circle" style="background-color: #1da1f2;"><i class="ri-twitter-fill"></i></a>
            <a href="https://www.instagram.com" target="_blank" class="share-circle" style="background-color: #e1306c;"><i class="ri-instagram-line"></i></a>
            <a href="https://api.whatsapp.com/send?text=${encodeURIComponent(
              window.location.href
            )}" target="_blank" class="share-circle" style="background-color: #25d366;"><i class="ri-whatsapp-fill"></i></a>
            <a href="javascript:void(0)" onclick="copyToClipboard('${
              window.location.href
            }')" class="share-circle" style="background-color: #333;"><i class="ri-clipboard-line"></i></a>
        </div>
    </div>
                    </div>`;

  productContent.append(productHTML);
  Display();
  document.querySelector('.cr-buy-now').addEventListener('mouseover', function () {
    this.style.backgroundColor = '#cc0000';
    this.style.boxShadow = '0 0 20px rgba(255, 0, 0, 0.7)';
  });

  document.querySelector('.cr-buy-now').addEventListener('mouseout', function () {
    this.style.backgroundColor = '#ff0000';
    this.style.boxShadow = '0 0 10px rgba(255, 0, 0, 0.5)';
  });

  // Scroll to description and activate the tab when "See more" is clicked
  $('#seeMoreLink').on('click', function () {
    $('html, body').animate(
      {
        scrollTop: $('#description').offset().top
      },
      500
    );

    // Activate the description tab
    $('#description-tab').tab('show');
  });
}

function copyToClipboard(text) {
  navigator.clipboard.writeText(text).then(
    function () {
      Toastify({
        text: 'Link copied to clipboard!',
        theme: 'light',
        duration: 3000,
        close: true,
        gravity: 'top',
        position: 'right',
        backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
        stopOnFocus: true
      }).showToast();
    },
    function (err) {
      console.error('Could not copy text: ', err);
    }
  );
}

// Helper function to truncate text to a specified number of lines
function truncateText(text, lineLimit) {
  var lines = text.split('\n');
  if (lines.length > lineLimit) {
    return lines.slice(0, lineLimit).join('\n') + '...';
  }
  return text;
}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function callTheBuy() {
  let userId = localStorage.getItem('user_id');

  // Get selected color, size, and quantity
  var selectedColor = $('.cr-kg.color .selected').text();
  var selectedSize = $('.cr-kg.size .selected').text();
  var quantity = parseInt($('.quantity').val());

  // Retrieve selected size and color from sessionStorage
  let is_product_size = sessionStorage.getItem('is_product_size');
  let is_color = sessionStorage.getItem('is_color');

  // Check if color, size, and quantity are selected
  if (is_product_size == 'true' && selectedSize.length == 0) {
    $('#sizeErrorMessage').text('Please select a size').css('color', 'red');
    return;
  } else {
    $('#sizeErrorMessage').text('').css('color', 'inherit');
  }

  if (is_color == 'true' && !selectedColor) {
    $('#colorErrorMessage').text('Please select a color').css('color', 'red');
    return;
  } else {
    $('#colorErrorMessage').text('').css('color', 'inherit');
  }

  // Retrieve product details
  var productId = sessionStorage.getItem('product_id');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

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
          // Proceed to add the product to the cart
          addDataToCart();
        } else {
          console.error('Error creating guest user:', response);
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + xhr.status + ' ' + error);
      }
    });
  } else {
    // If user_id is present, proceed to add the product to the cart
    addDataToCart();
  }

  function addDataToCart() {
    var user_id = localStorage.getItem('user_id');
    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/add-cart',
      data: {
        user_id: user_id,
        product_id: productId,
        product_color: selectedColor,
        product_size: selectedSize,
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
            duration: 3000,
            close: true,
            gravity: 'top',
            position: 'right',
            backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
            stopOnFocus: true
          }).showToast();
          window.location.href = baseUrlLive + '/cart-checkout';
        } else {
          console.error('Error adding product to cart:', response);
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + xhr.status + ' ' + error);
        Swal.fire({
          title: 'Are you sure?',
          text: 'You have items from another store in the cart. If you continue, all previous items from the cart will be removed and this one will be added.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Clear Cart',
          cancelButtonText: 'Cancel',
          showCloseButton: true
        }).then(result => {
          if (result.isConfirmed) {
            clearCartAndAddProductData(productId, selectedSize, selectedColor, quantity);
          }
        });
      }
    });
  }

  function clearCartAndAddProductData(productId, productSize, productColor, quantity) {
    var baseUrlLive = sessionStorage.getItem('baseUrlLive');
    var userId = localStorage.getItem('user_id');

    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/cart-clear',
      data: { user_id: userId },
      dataType: 'json',
      success: function (response) {
        console.log('Cart cleared successfully:', response);
        $.ajax({
          type: 'POST',
          url: baseUrlLive + '/api/add-cart',
          data: {
            user_id: userId,
            product_id: productId,
            product_color: productColor,
            product_size: productSize,
            quantity: quantity
          },
          dataType: 'json',
          success: function (response) {
            if (
              response &&
              response.message === 'Product added to cart successfully ...!' &&
              response.response_code === 1
            ) {
              Toastify({
                text: 'Product added to cart successfully!',
                theme: 'light',
                duration: 3000,
                close: true,
                gravity: 'top',
                position: 'right',
                backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
                stopOnFocus: true
              }).showToast();
              window.location.href = baseUrlLive + '/cart-checkout';
              // Adjust delay as needed
            } else {
              console.error('Error adding product to cart:', response);
            }
          },
          error: function (xhr, status, error) {
            console.error('AJAX error: ' + xhr.status + ' ' + error);
          }
        });
      },
      error: function (xhr, status, error) {
        console.error('Error clearing cart:', xhr.status, error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Failed to clear cart. Please try again later.'
        });
      }
    });
  }
}

// Call the function to get single product details
getSingleProductDetails();
// Function to handle size option selection
$(document).on('click', '.cr-kg.size li', function () {
  // Remove selected class from all size options
  $('.cr-kg.size li').removeAttr('style').removeClass('selected');

  // Add selected class to the clicked size option and set inline CSS
  $(this).addClass('selected').css({
    'background-color': '#00438F',
    color: 'white'
  });

  showVariantsPriceDetail(); // Update the variant price when size is selected
});

// Function to handle color option selection
$(document).on('click', '.cr-kg.color li', function () {
  // Remove selected class from all color options
  $('.cr-kg.color li').removeAttr('style').removeClass('selected');

  // Add selected class to the clicked color option and set inline CSS
  $(this).addClass('selected').css({
    'background-color': '#00438F',
    color: 'white'
  });

  showVariantsPriceDetail(); // Update the variant price when color is selected
});

// Event handler for the plus button
$(document).on('click', '.plus', function () {
  var quantityInput = $(this).siblings('.quantity');
  var currentQuantity = parseInt(quantityInput.val());
  var maxQuantity = parseInt(quantityInput.attr('max'));
  if (!isNaN(currentQuantity) && currentQuantity < maxQuantity) {
    quantityInput.val(currentQuantity + 1);
  }
});

// Event handler for the minus button
$(document).on('click', '.minus', function () {
  var quantityInput = $(this).siblings('.quantity');
  var currentQuantity = parseInt(quantityInput.val());
  if (!isNaN(currentQuantity) && currentQuantity > 1) {
    quantityInput.val(currentQuantity - 1);
  }
});

// Function to handle the click event on the "Add to cart" button
$(document).on('click', '.cr-shopping-bag', function () {
  let userId = localStorage.getItem('user_id');

  // Get selected color, size, and quantity
  var selectedColor = $('.cr-kg.color .selected').text();
  var selectedSize = $('.cr-kg.size .selected').text();
  var quantity = parseInt($('.quantity').val());

  // Retrieve selected size and color from sessionStorage
  let is_product_size = sessionStorage.getItem('is_product_size');
  let is_color = sessionStorage.getItem('is_color');

  // Check if color, size, and quantity are selected
  if (is_product_size == 'true' && selectedSize.length == 0) {
    $('#sizeErrorMessage').text('Please select a size').css('color', 'red');
    return;
  } else {
    $('#sizeErrorMessage').text('').css('color', 'inherit');
  }

  if (is_color == 'true' && !selectedColor) {
    $('#colorErrorMessage').text('Please select a color').css('color', 'red');
    return;
  } else {
    $('#colorErrorMessage').text('').css('color', 'inherit');
  }

  // Retrieve product details
  var productId = sessionStorage.getItem('product_id');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

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
          // Proceed to add the product to the cart
          addToCart();
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
    // If user_id is present, proceed to add the product to the cart
    addToCart();
  }

  function addToCart() {
    var user_id = localStorage.getItem('user_id');
    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/add-cart',
      data: {
        user_id: user_id,
        product_id: productId,
        product_color: selectedColor,
        product_size: selectedSize,
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
            duration: 3000,
            close: true,
            gravity: 'top',
            position: 'right',
            backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
            stopOnFocus: true
          }).showToast();
          $('.cr-add-button button').text('Added');
          // Hide the shopping bag icon
          fetchProductList();
        } else {
          console.error('Error adding product to cart:', response);
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + xhr.status + ' ' + error);
        // Show SweetAlert confirmation if there are items from another store in the cart
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
            clearCartAndAddProductData(productId, selectedSize, selectedColor, quantity);
          }
        });
      }
    });
  }
});

// Function to clear cart and add product after confirmation
function clearCartAndAddProductData(productId, productSize, productColor, quantity) {
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

      // After clearing cart, add the new product
      $.ajax({
        type: 'POST',
        url: baseUrlLive + '/api/add-cart',
        data: {
          user_id: userId,
          product_id: productId,
          product_color: productColor,
          product_size: productSize,
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
              duration: 3000,
              close: true,
              gravity: 'top',
              position: 'right',
              backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
              stopOnFocus: true
            }).showToast();
            $('.cr-add-button button').text('Added'); // Assuming you have a class for the add to cart button
            // Change text to 'Add to Cart' after 1 second
            setTimeout(function () {
              $('.cr-add-button button').text('Add to Cart');
            }, 1000);
            fetchProductList(); // Function to fetch updated product list if needed
          } else {
            console.error('Error adding product to cart:', response);
          }
        },
        error: function (xhr, status, error) {
          console.error('AJAX error: ' + xhr.status + ' ' + error);
        }
      });
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

function displayProductInformation(product, productReviews) {
  var productContent = $('#myTabContentData');
  productContent.empty(); // Clear previous content

  // Calculate filled and empty stars for product rating
  var filledStars = '';
  var emptyStars = '';
  var roundedRating = Math.round(parseFloat(product.review_star));

  for (var i = 0; i < 5; i++) {
    if (i < roundedRating) {
      filledStars += '<i class="ri-star-s-fill"></i>';
    } else {
      emptyStars += '<i class="ri-star-s-line"></i>';
    }
  }

  // Check if product color and size arrays are available
  var productColorHTML = '';
  if (product.product_color && product.product_color.length > 0) {
    productColorHTML = `<li><label>Product Color <span>:</span></label> ${product.product_color.join(', ')}</li>`;
  }

  var productSizeHTML = '';
  if (product.product_size && product.product_size.length > 0) {
    productSizeHTML = `<li><label>Product Size <span>:</span></label> ${product.product_size.join(', ')}</li>`;
  }

  // Generate HTML for product information
  var productHTML = `
    <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
      <div class="cr-tab-content">
        <div class="cr-description">
          <p>${product.product_about}</p>
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="additional" role="tabpanel" aria-labelledby="additional-tab">
      <div class="cr-tab-content">
        <div class="list">
          <ul>
            <li><label>Product Name <span>:</span></label> ${product.product_name}</li>
            <li><label>Product Discount <span>:</span></label> <span style="color: red">${Math.round(
              Math.abs(product.producDiscount)
            )}%</span></li>
            <li><label>Product Price <span>:</span></label> <span style="color: #00438F">$${
              product.product_sale_price
            }</span></li>
            ${productColorHTML}
            ${productSizeHTML}
          </ul>
        </div>
      </div>
    </div>
    <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
    <div class="cr-tab-content-from">
      ${generateProductReviewsHTML(productReviews)}
    </div>
    </div>`;

  productContent.append(productHTML);
}

function generateProductReviewsHTML(productReviews, review_stars = 0) {
  if (!productReviews || productReviews.length === 0) {
    return '<p>No reviews yet.</p>' + '';
  }

  var reviewsHTML = '';

  productReviews.forEach(function (review) {
    var reviewStars = generateStarRating(review.review_star);
    var reviewDate = review.created_at ? new Date(review.created_at) : null;
    var formattedDate = reviewDate
      ? reviewDate.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: '2-digit' })
      : '';

    var firstLetter = review.first_name.charAt(0).toUpperCase();
    var reviewImage = review.image
      ? `<img src="${review.image}" alt="review">`
      : `<img src="http://192.168.0.10:8011/assets/images/user-default.png" alt="default user image">`;

    var firstName = review.first_name || 'User';
    var lastName = review.last_name || '';
    reviewsHTML += `
      <div class="post">
        <div class="content">
          ${reviewImage}
          <div class="details">
            <span class="date">${formattedDate}</span>
 <span class="name">${firstName} ${lastName}</span>
          </div>
          <div class="cr-t-review-rating">
            ${reviewStars}
          </div>
        </div>
        <p>${review.review_message}</p>
      </div>`;
  });

  return reviewsHTML;
}

function generateDynamicStars(selectedStars) {
  let starsHTML = '';
  for (let i = 1; i <= 5; i++) {
    starsHTML += `<i class="ri-star-s-line ${i <= selectedStars ? 'selected' : ''}" data-value="${i}"></i>`;
  }
  return starsHTML;
}

// Function to generate star rating HTML dynamically
function generateStarRating(rating) {
  var filledStars = '';
  var emptyStars = '';

  var roundedRating = Math.round(parseFloat(rating));

  for (var i = 0; i < 5; i++) {
    if (i < roundedRating) {
      filledStars += '<i class="ri-star-s-fill"></i>';
    } else {
      emptyStars += '<i class="ri-star-s-line"></i>';
    }
  }

  return filledStars + emptyStars;
}

function hideSizeWritten() {
  $('#ShowSize').hide();
}

function showSizeWritten() {
  $('#ShowSize').show();
}

function hideColorWritten() {
  $('#ShowColor').hide();
}

function showColorWritten() {
  $('#ShowColor').show();
}

function Display() {
  let is_product_size = sessionStorage.getItem('is_product_size');
  let is_color = sessionStorage.getItem('is_color');

  // Check if color, size, and quantity are selected
  if (is_product_size === 'true') {
    showSizeWritten();
  } else {
    hideSizeWritten();
  }

  if (is_color === 'true') {
    showColorWritten();
  } else {
    hideColorWritten();
  }
}

// Event handler for rating star selection
$(document).on('click', '.cr-t-review-rating-e i', function () {
  var ratingValue = $(this).data('value');
  $('.cr-t-review-rating-e i').removeClass('selected').css('color', ''); // Remove previous selection and reset color
  for (var i = 0; i < ratingValue; i++) {
    $('.cr-t-review-rating-e i').eq(i).addClass('selected').css('color', '#f5885f'); // Add selected class and set color to black
  }
});

$(document).on('submit', '#reviewForm', function (e) {
  e.preventDefault();

  var reviewStar = $('.cr-t-review-rating-e i.selected').length; // Count selected stars
  var reviewMessage = $('textarea[name="your-comment"]').val().trim();

  if (reviewStar === 0) {
    alert('Please select a rating');
    return;
  }

  if (!reviewMessage) {
    alert('Please enter your review');
    return;
  }

  var productId = sessionStorage.getItem('product_id');
  var token = localStorage.getItem('token');
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/review-post',
    headers: { Authorization: `Bearer ${token}` },
    data: {
      product_id: productId,
      review_star: reviewStar, // Use selected stars count
      review_message: reviewMessage
    },
    dataType: 'json',
    success: function (response) {
      if (response.success && response.message === 'Your review added successfully') {
        // Display success toast message
        Toastify({
          text: 'Review added successfully!',
          theme: 'light',
          duration: 3000,
          close: true,
          gravity: 'top',
          position: 'right',
          backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
          stopOnFocus: true
        }).showToast();

        displayAddedReviews(response.reviews);
      } else {
        // Display error message for unsuccessful review addition
        var errorMessage = 'You need to buy this product to give a review.';
        Toastify({
          text: errorMessage,
          theme: 'light',
          duration: 3000,
          close: true,
          gravity: 'top',
          position: 'right',
          backgroundColor: 'linear-gradient(to right, #ff4c4c, #ff9068)',
          stopOnFocus: true
        }).showToast();
      }
    }
  });
});

function displayAddedReviews(productReviews) {
  var addedReviewsContainer = $('#addedReviews');
  var reviewsHTML = '';

  productReviews.forEach(function (review) {
    var reviewStars = generateStarRating(review.review_star);
    var reviewDate = new Date(review.created_at);
    var formattedDate = reviewDate.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: '2-digit' });

    reviewsHTML += `
      <div class="post">
        <div class="content">
          <img src="${review.image}" alt="review">
          <div class="details">
            <span class="date">${formattedDate}</span>
            <span class="name">${review.first_name} ${review.last_name}</span>
          </div>
          <div class="cr-t-review-rating">
            ${reviewStars}
          </div>
        </div>
        <p>${review.review_message}</p>
      </div>`;
  });

  addedReviewsContainer.append(reviewsHTML);
}
function showVariantsPriceDetail() {
  let selectedColor = $('.cr-kg.color .selected').text(); // Get selected color
  let selectedSize = $('.cr-kg.size .selected').text(); // Get selected size
  let productId = sessionStorage.getItem('product_id');
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/show-variantsprice',
    data: {
      product_id: productId,
      product_color: selectedColor,
      product_size: selectedSize
    },
    dataType: 'json',
    success: function (response) {
      if (response && response.message === 'Variant prices found' && response.data.length > 0) {
        let variant = response.data[0]; // Assuming there's only one variant for simplicity
        let variantPrice = parseFloat(variant.price);
        $('#new-price-update').text('$' + variantPrice.toFixed(0)); // Display the variant price without decimal places
        $('#variantErrorMessagedetail').hide(); // Hide the error message if the variant is found

        // Enable the Add to Cart button and remove disabled class
        $('.cr-shopping-bag').prop('disabled', false).removeClass('disabled-button');
      } else {
        console.error('No variant prices found or error in response:', response);
        $('#variantErrorMessagedetail').show(); // Show the error message
        // Disable the Add to Cart button
        // Disable the Add to Cart button and add disabled class
        $('.cr-shopping-bag').prop('disabled', true).addClass('disabled-button');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + xhr.status + ' ' + error);
      $('#variantErrorMessagedetail').show(); // Show the error message in case of AJAX error
    }
  });
}

// Ensure the error message is hidden by default
$('#variantErrorMessagedetail').hide();
