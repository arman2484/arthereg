window.getProductDetails = function (productId) {
  var baseUrlLive = sessionStorage.getItem('baseUrlLive');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/productdetail',
    data: { product_id: productId },
    dataType: 'json',
    success: function (response) {
      // Define a success callback
      if (response && response.message === 'Product Found') {
        response.totalAvgReviewCount = parseFloat(Number(response.totalAvgReviewCount));
        response.totalUserCount = parseFloat(Number(response.totalUserCount));
        displayProductDetails(response);
      } else {
        console.error('No product found or error in response:', response);
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + xhr.status + ' ' + error);
    }
  });
};

// Function to display product details on the page
function displayProductDetails(response) {
  clearCarousel();

  let product = response.product;

  // Add product images to the carousel
  product.product_image.forEach(function (imageSrc, index) {
    let activeClass = index === 0 ? 'active' : '';
    addCarouselImage(imageSrc, activeClass);
  });

  // Display product details
  $('#modalproductname').text(product.product_name);
  $('#modalstorename').text('by ' + product.store_name);
  $('#modal_about').text(product.product_about);
  $('#newprice').text('$' + product.product_sale_price);
  $('#oldprice').text('$' + product.product_price);

  // Calculate and display star ratings if totalAvgReviewCount is a valid number
  let totalAvgReviewCount = parseFloat(Number(response.totalAvgReviewCount));
  let totalUserCount = parseFloat(Number(response.totalUserCount));
  if (!isNaN(totalAvgReviewCount)) {
    displayStarRatings(totalAvgReviewCount, totalUserCount);
  } else {
    console.error('Invalid totalAvgReviewCount:', totalAvgReviewCount);
  }

  // Display size and color options
  displayOptions(product.product_size, '.cr-size-weight.size ul');
  displayOptions(product.product_color, '.cr-size-weight.color ul');
}

// Function to clear the carousel
function clearCarousel() {
  $('#imageCarousel .carousel-inner').empty();
}

// Function to add an image to the carousel
function addCarouselImage(imageSrc, activeClass) {
  let imageHTML =
    '<div class="carousel-item ' + activeClass + '"><img src="' + imageSrc + '" class="d-block w-100"></div>';
  $('#imageCarousel .carousel-inner').append(imageHTML);
}

// Function to display options (size, color, etc.)
function displayOptions(options, selector) {
  let optionsHTML = '';
  options.forEach(function (option, index) {
    let capitalizedOption = option.charAt(0).toUpperCase() + option.slice(1);
    let activeClass = index === 0 ? 'selected' : '';
    let style = index === 0 ? ' style="background-color: #00438F; color: white;"' : '';
    optionsHTML += `<li class="${activeClass}"${style}>${capitalizedOption}</li>`;
  });
  $(selector).html(optionsHTML);
  // Retrieve selected size and color from sessionStorage
  let is_product_size = sessionStorage.getItem('is_product_size');
  let is_color = sessionStorage.getItem('is_color');

  // Check if color, size, and quantity are selected
  if (is_product_size == 'true') {
    showSize();
  } else {
    hideSize();
  }

  if (is_color == 'true') {
    showColor();
  } else {
    hideColor();
  }
}

// Function to display star ratings
function displayStarRatings(totalAvgReviewCount, totalUserCount) {
  totalAvgReviewCount = Number(totalAvgReviewCount);
  totalUserCount = Number(totalUserCount);
  let filledStars = '';
  let emptyStars = '';
  let reviewText = '';

  // Calculate number of filled and empty stars based on review rating
  for (let i = 0; i < 5; i++) {
    if (i < totalAvgReviewCount) {
      filledStars += '<i class="ri-star-fill"></i>';
    } else {
      emptyStars += '<i class="ri-star-line"></i>';
    }
  }

  // Determine the review text based on the review count
  if (totalUserCount === 0) {
    reviewText = '(0 Review)';
  } else if (totalUserCount === 1) {
    reviewText = '(1 Review)';
  } else {
    reviewText = '(' + totalUserCount + ' Reviews)';
  }

  // Display filled and empty stars along with the review count
  $('#reviewstars').html(filledStars + emptyStars + '&nbsp;' + reviewText);
}

$(document).ready(function () {
  let productId = sessionStorage.getItem('product_id');

  if (productId) {
    getProductDetails(productId);
  } else {
    console.error('Product ID is missing in session storage');
  }

  // Event listener to handle color selection
  $(document).on('click', '.cr-size-weight.color ul li', function () {
    $('.cr-size-weight.color ul li').removeClass('selected').css({
      'background-color': '',
      color: ''
    });
    $(this).addClass('selected').css({
      'background-color': '#00438F',
      color: 'white'
    });
    showVariantsPrice();
  });

  $(document).on('click', '.cr-size-weight.size ul li', function () {
    $('.cr-size-weight.size ul li').removeClass('selected').css({
      'background-color': '',
      color: ''
    });
    $(this).addClass('selected').css({
      'background-color': '#00438F',
      color: 'white'
    });
    showVariantsPrice();
  });

  // Event listener to handle the "Add to cart" button click
  $('.cr-add-button button').click(function () {
    // Check if user_id is present in local storage
    let userId = localStorage.getItem('user_id');

    // Get selected color, size, and quantity
    let selectedColor = $('.cr-size-weight.color ul li.selected').text();
    let selectedSize = $('.cr-size-weight.size ul li.selected').text();
    let quantity = parseInt($('.quantity').val());

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
    let productId = sessionStorage.getItem('product_id');
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
            addModalProductToCart();
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
      addModalProductToCart();
    }

    // Check if color, size, and quantity are selected
    // if (selectedColor && selectedSize && !isNaN(quantity) && productId) {

  function addModalProductToCart() {
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
            clearCartAndAddProductDataModal(productId, selectedSize, selectedColor, quantity);
          }
        });
      }
    });
    }
  });
});

// Function to clear cart and add product after confirmation
function clearCartAndAddProductDataModal(productId, productSize, productColor, quantity) {
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

// Function to handle liking/disliking a product
window.likeProduct = function () {
  let productId = sessionStorage.getItem('product_id');
  let userId = localStorage.getItem('user_id');
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');

  // Check if user ID is undefined
  if (!userId) {
    Swal.fire({
      text: 'You need to log in first.',
      icon: 'info',
      showCloseButton: true, // Adding close button
      confirmButtonText: 'OK'
    }).then(result => {
      if (result.isConfirmed) {
        window.location.href = '/register'; // Redirect to register page
      }
    });
    return;
  }

  // Check if both product ID and user ID are available
  if (productId && userId) {
    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/product-like',
      data: {
        user_id: userId,
        product_id: productId
      },
      dataType: 'json',
      success: function (response) {
        if (response && response.message === 'product liked') {
          // Product liked successfully, show toast notification
          Toastify({
            text: 'Product liked!',
            theme: 'light',
            duration: 1000,
            close: true,
            gravity: 'top',
            position: 'right',
            backgroundColor: 'linear-gradient(to right, #00438F, #B2CBDD)',
            stopOnFocus: true
          }).showToast();
          let heartIcon = document.querySelector(`#heartIcon${productId}`);
          heartIcon.classList.remove('ri-heart-2-line');
          heartIcon.classList.add('ri-heart-2-fill');
          heartIcon.classList.toggle('icon_color');
        } else if (response && response.message === 'product disliked') {
          // Product disliked successfully, show toast notification
          Toastify({
            text: 'Product Unlike!',
            theme: 'light',
            duration: 1000,
            close: true,
            gravity: 'top',
            position: 'right',
            backgroundColor: 'linear-gradient(to right, #ff4c4c, #ff9068)',
            stopOnFocus: true
          }).showToast();
          let heartIcon = document.querySelector(`#heartIcon${productId}`);
          heartIcon.classList.add('ri-heart-2-line');
          heartIcon.classList.remove('ri-heart-2-fill');
          heartIcon.classList.toggle('icon_color');
          getWishlistProductList();
        } else {
          console.error('Error toggling product like:', response);
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + xhr.status + ' ' + error);
      }
    });
  } else {
    console.error('Product ID or User ID missing');
  }
};

function hideSize() {
  $('#SizeSeen').hide();
}

function showSize() {
  $('#SizeSeen').show();
}

function hideColor() {
  $('#Colorseen').hide();
}

function showColor() {
  $('#Colorseen').show();
}

// Function to fetch and display variant price based on selected color and size
// Function to fetch and display variant price based on selected color and size
function showVariantsPrice() {
  let selectedColor = $('.cr-size-weight.color ul li.selected').text();
  let selectedSize = $('.cr-size-weight.size ul li.selected').text();
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
        $('#newprice').text('$' + variantPrice.toFixed(0)); // Display the variant price without decimal places
        $('#variantErrorMessage').hide(); // Hide the error message if the variant is found
        getProductDetails();

        // Enable the Add to Cart button and remove disabled class
        $('.cr-add-button button').prop('disabled', false).removeClass('disabled-button');
      } else {
        console.error('No variant prices found or error in response:', response);
        $('#variantErrorMessage').show(); // Show the error message

        $('.cr-add-button button').prop('disabled', true).addClass('disabled-button');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + xhr.status + ' ' + error);
      $('#variantErrorMessage').show(); // Show the error message in case of AJAX error
    }
  });
}

// Ensure the error message is hidden by default
$('#variantErrorMessage').hide();
