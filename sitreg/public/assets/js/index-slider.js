function GetIndexMainData() {
  let token = localStorage.getItem('token');
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');
  let module_id = localStorage.getItem('module_id');
  let user_id = localStorage.getItem('user_id');

  // Make AJAX request
  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/web-home',
    dataType: 'json',
    headers: { Authorization: `Bearer ${token}` },
    data: { module_id, user_id },
    success: function (response) {
      if (response && response.message === 'product found' && response.slider && response.slider.length > 0) {
        console.log('Slider Data Found:', response.slider);
        DisplaySliderData(response.slider);
        DisplayIndexCategoryData(response.category);
        DisplayIndexPopularProductData(response.popular_product);
        DisplayIndexSpecialOfferProductData(response.special_offer);
        DisplayIndexProductBanner(response.product_banner);
        DisplayIndexStoreData(response.stores);
        DisplayIndexBestSellingProductData(response.latest_product);
        initializeSlider();
      } else {
        console.error('No slider data found');
        displayEmptyProductMessage();
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
      displayEmptyProductMessage();
    }
  });
}

window.getcategorylistforindex = function () {
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');
  let module_id = localStorage.getItem('module_id');

  // Make AJAX request
  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/get-category',
    dataType: 'json',
    data: { module_id },
    success: function (response) {
      if (response && response.message === 'Category found' && response.data && response.data.length > 0) {
        showCategoryNames(response.data);
      } else {
        console.error('No category data found');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
    }
  });
};

function showCategoryNames(categories) {
  const container = document.getElementById('MixItUpDA2FB7');
  container.innerHTML = ''; // Clear any existing content
  container.innerHTML += `
    <li class="active" data-filter="all">All</li>
  `;

  // Limit to displaying only the first 10 categories
  categories.slice(0, 10).forEach(category => {
    const content = `
      <li data-filter=".${category.slug}" data-category-id="${category.id}">
        ${category.category_name}
      </li>
    `;
    container.innerHTML += content;
  });

  // Add event listener to each category <li> element
  container.querySelectorAll('li').forEach(li => {
    li.addEventListener('click', function () {
      const categoryId = this.getAttribute('data-category-id');

      // Store category ID in session storage
      if (categoryId) {
        sessionStorage.setItem('selected_category', categoryId);
      } else {
        sessionStorage.removeItem('selected_category');
      }

      // Optionally, you can add/remove active class for styling purposes
      container.querySelectorAll('li').forEach(li => {
        li.classList.remove('active');
      });
      this.classList.add('active');

      // Filter products based on the selected category
      filterProducts(categoryId);
    });
  });
}

function filterProducts(categoryId) {
  const productsContainer = document.getElementById('popular_products');
  const products = productsContainer.getElementsByClassName('cr-product-box');
  let visibleProductCount = 0;

  Array.from(products).forEach(product => {
    const productCategoryId = product.getAttribute('data-category-id');

    // Check if categoryId is empty or matches the product's category ID
    if (!categoryId || categoryId === 'all' || productCategoryId === categoryId) {
      product.style.display = 'block'; // Show the product
      visibleProductCount++;
    } else {
      product.style.display = 'none'; // Hide the product
    }
  });

  // Display 'No product found' message with Font Awesome icon if no products are visible
  let noProductMessage = document.getElementById('no-product-message');
  if (!noProductMessage) {
    noProductMessage = document.createElement('div');
    noProductMessage.id = 'no-product-message';
    noProductMessage.style.display = 'none';
    noProductMessage.style.textAlign = 'center'; // Center the content
    noProductMessage.style.padding = '20px'; // Add some padding

    // Create and add Font Awesome icon
    const icon = document.createElement('i');
    icon.className = 'fas fa-box-open'; // Use a Font Awesome icon class
    icon.style.fontSize = '100px'; // Adjust the size of the icon as needed
    noProductMessage.appendChild(icon);

    // Add the "No product found" text
    const message = document.createElement('p');
    message.textContent = 'No product found';
    message.style.fontSize = '18px'; // Adjust the font size of the message
    noProductMessage.appendChild(message);

    productsContainer.appendChild(noProductMessage);
  }

  if (visibleProductCount === 0) {
    noProductMessage.style.display = 'block';
  } else {
    noProductMessage.style.display = 'none';
  }
}

function DisplayIndexPopularProductData(popuarproducts) {
  const container = document.getElementById('popular_products');
  container.innerHTML = ''; // Clear any existing content

  if (popuarproducts.length === 0) {
    displayEmptyProductMessage();
    return;
  }

  // Limit to displaying only the first 8 products
  popuarproducts.slice(0, 8).forEach(popular_product => {
    var filledStars = '';
    var emptyStars = '';

    // Calculate number of filled and empty stars based on review rating
    for (var i = 0; i < 5; i++) {
      if (i < popular_product.totalAvgReview) {
        filledStars += '<i class="ri-star-fill"></i>';
      } else {
        emptyStars += '<i class="ri-star-line"></i>';
      }
    }

    // Initialize variables for heart icon color and style
    var heartColorClass = popular_product.is_Like == 1 ? 'ri-heart-2-fill icon_color' : 'ri-heart-2-line';

    // Select the first image URL from the product_image array
    const firstImageUrl =
      popular_product.product_image.length > 0 ? popular_product.product_image[0] : 'assets/images/product-default.png';

    let queryParams = $.param({
      product_id: popular_product.id,
      store_id: popular_product.store_id,
      is_product_size: popular_product.product_size.length != 0,
      is_color: popular_product.product_color.length != 0,
      module_id: popular_product.module_id // Include module_id in the query parameters
    });

    // Create query string with product details
    let querysttoreParams = $.param({
      store_id: popular_product.store_id
    });

    // Construct the product card HTML content with the first image
    const content = `
       <div class="mix vegetable col-xxl-3 col-xl-4 col-6 cr-product-box mb-24" data-category-id="${
         popular_product.category_id
       }">
        <!-- Product HTML content here -->
        <div class="cr-product-card">
          <div class="cr-product-image">
            <div class="cr-image-inner">
              <a href="product-detail/${stringToSlug(
                popular_product.product_name
              )}?${queryParams}" onclick="sessionStorage.setItem('product_id', '${
      popular_product.id
    }'); sessionStorage.setItem('store_id', ${popular_product.store_id});  sessionStorage.setItem('is_product_size', '${
      popular_product.product_size.length != 0 ? true : false
    }'); sessionStorage.setItem('is_color', '${
      popular_product.product_color.length != 0 ? true : false
    }');  getSingleProductDetails('${popular_product.id}')" class="cr-image-inner">
                <img src="${firstImageUrl}" alt="${popular_product.product_name}">
              </a>
            </div>
            <div class="cr-side-view">
                <a href="javascript:void(0)" class="wishlist" onclick="sessionStorage.setItem('product_id', ${
                  popular_product.id
                }); sessionStorage.setItem('store_id', ${popular_product.store_id}); likeProduct(${
      popular_product.id
    })">
                  <i id="heartIcon${
                    popular_product.id
                  }" class="${heartColorClass}"></i> <!-- Apply inline style here -->
                </a>
                <a class="model-oraganic-product" data-bs-toggle="modal" onClick="sessionStorage.setItem('product_id', ${
                  popular_product.id
                }); sessionStorage.setItem('store_id', ${
      popular_product.store_id
    }); sessionStorage.setItem('is_product_size', '${
      popular_product.product_size.length != 0 ? true : false
    }'); sessionStorage.setItem('is_color', '${
      popular_product.product_color.length != 0 ? true : false
    }');  getProductDetails(${popular_product.id})" href="#quickview" role="button">
                  <i class="ri-eye-line"></i>
                </a>
                <a class="cr-shopping-bag" id="shoppingBagIcon${
                  popular_product.id
                }" style="bottom: -35px !important;" href="javascript:void(0)" onclick="addToCart(${
      popular_product.id
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
                <p>(${popular_product.totalReviewCount})</p>
              </div>
            </div>
            <a href="product-detail/${stringToSlug(
              popular_product.product_name
            )}?${queryParams}" onClick="sessionStorage.setItem('product_id', ${
      popular_product.id
    }); sessionStorage.setItem('store_id', ${popular_product.store_id}); sessionStorage.setItem('is_product_size', '${
      popular_product.product_size.length != 0 ? true : false
    }'); sessionStorage.setItem('is_color', '${
      popular_product.product_color.length != 0 ? true : false
    }'); getSingleProductDetails(${popular_product.id})" class="title">${popular_product.product_name}</a>
       <a href="store-detail/${stringToSlug(popular_product.store_name)}?${querysttoreParams}"
   onClick="sessionStorage.setItem('store_id', ${popular_product.store_id})">
   <p style="color:#00438F;" class="selling-card-sub-title bold">by ${popular_product.store_name}</p>
</a>
            <p class="cr-price">
              <span class="new-price">$${popular_product.product_sale_price}</span>
              <span class="old-price">$${popular_product.product_price}</span>
            </p>
          </div>
        </div>
      </div>
    `;
    container.innerHTML += content;
  });
}

function displayEmptyProductMessage() {
  var cartContent = $('#popular_products');
  cartContent.empty(); // Clear previous content

  var emptyCartMessage = `

            <div style="text-align: center; margin-top: 180px;">
                <div id="lottie-no-product-cart" style="width: 300px; height: 300px; margin: 0 auto;"></div>
                <div style="font-size: 24px; color: #00438f !important;" class="text-center py-4">No product found</div>
            </div>
`;
  cartContent.append(emptyCartMessage);

  // Load the Lottie animation
  lottie.loadAnimation({
    container: document.getElementById('lottie-no-product-cart'), // The container for the animation
    renderer: 'svg', // The renderer to use
    loop: true, // Whether the animation should loop
    autoplay: true, // Whether the animation should start automatically
    path: 'assets/json/lottie.json' // The path to the Lottie file (you can also use a URL)
  });
}

// Slider Dynamic
function DisplaySliderData(sliderData) {
  const container = document.getElementById('indexsliderdata');
  container.innerHTML = ''; // Clear any existing content

  // Use forEach loop to iterate over sliderData
  sliderData.forEach(imageUrl => {
    const content = `
            <div class="swiper-slide">
                <div class="cr-hero-banner cr-banner-image-two">
                    <img class="h-100 w-100 object-fit-cover" src="${imageUrl}" alt="Slider Image">
                </div>
            </div>`;
    container.innerHTML += content;
  });
}

// Category Dynamic
function DisplayIndexCategoryData(categories) {
  const container = document.getElementById('indexcategorydata');
  container.innerHTML = ''; // Clear any existing content

  categories.forEach(category => {
    const content = `
            <div class="slick-slide">
                 <div class="cr-product-card" style="height: 10rem;">
                    <div class="cr-product-image">
                     <a href="productlist" onClick="sessionStorage.setItem('category_id', ${
                       category.id
                     }); sessionStorage.setItem('sub_category_id', '${category.subcategory_ids.join(',')}');">
                        <div class="cr-image-inner">
                            <img style="height: 5rem !important;width: 5rem !important; object-fit: contain !important;"
                                src="${category.category_image}" alt="${category.category_name}">
                        </div>
                        </a>
                    </div>
                    <div class="cr-product-details">
                        <a href="productlist" onClick="sessionStorage.setItem('category_id', ${
                          category.id
                        }); sessionStorage.setItem('sub_category_id', '${category.subcategory_ids.join(
      ','
    )}');" class="title">${category.category_name}</a>
                    </div>
                </div>
            </div>
        `;
    container.innerHTML += content;
  });

  // Initialize category slider after data is loaded
  setTimeout(() => {
    $('.cr-twocolumns-category').slick({
      slidesToShow: 8,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 2000,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 6,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 4,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 576,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    });
  }, 1000);
}

// Sliding for slider
function initializeSlider() {
  // Initialize the swiper slider after data is loaded
  new Swiper('.swiper-container-slides', {
    pagination: {
      el: '.swiper-pagination',
      clickable: true
    },
    autoplay: {
      delay: 2500,
      disableOnInteraction: false
    },
    loop: true
  });
}

// Special Offer Product Dynamic
function DisplayIndexSpecialOfferProductData(specialofferproducts) {
  const container = document.getElementById('indexspecialofferproductdata');
  container.innerHTML = ''; // Clear any existing content

  specialofferproducts.forEach(special_offer => {
    // Calculate number of filled and empty stars based on review rating
    var filledStars = '';
    var emptyStars = '';

    for (var i = 0; i < 5; i++) {
      if (i < special_offer.totalAvgReview) {
        filledStars += '<i class="ri-star-fill"></i>';
      } else {
        emptyStars += '<i class="ri-star-line"></i>';
      }
    }

    // Initialize variables for heart icon color and style
    var heartColorClass = special_offer.is_Like == 1 ? 'ri-heart-2-fill icon_color' : 'ri-heart-2-line';

    // Select the first image URL from the product_image array
    const firstImageUrl =
      special_offer.product_image.length > 0 ? special_offer.product_image[0] : 'assets/images/product-default.png';

    // Create query string with product details
    let queryParams = $.param({
      product_id: special_offer.id,
      store_id: special_offer.store_id,
      is_product_size: special_offer.product_size.length != 0,
      is_color: special_offer.product_color.length != 0,
      module_id: special_offer.module_id // Include module_id in the query parameters
    });

    // Create query string with product details
    let querysttoreParams = $.param({
      store_id: special_offer.store_id
    });

    // Construct the product card HTML content with the first image
    const content = `
      <div class="slick-slide">
        <div class="cr-product-card">
          <div class="cr-product-image">
            <div class="cr-image-inner">
               <a href="product-detail/${stringToSlug(
                 special_offer.product_name
               )}?${queryParams}" onclick="sessionStorage.setItem('product_id', '${
      special_offer.id
    }'); sessionStorage.setItem('store_id', ${special_offer.store_id});  sessionStorage.setItem('is_product_size', '${
      special_offer.product_size.length != 0 ? true : false
    }'); sessionStorage.setItem('is_color', '${
      special_offer.product_color.length != 0 ? true : false
    }');  getSingleProductDetails('${special_offer.id}')" class="cr-image-inner">
                <img style="height:16rem !important;" src="${firstImageUrl}" alt="${special_offer.product_name}">
              </a>
            </div>
            <div class="cr-side-view">
              <a href="javascript:void(0)" class="wishlist" onclick="sessionStorage.setItem('product_id', ${
                special_offer.id
              }); sessionStorage.setItem('store_id', ${special_offer.store_id}); likeProduct(${special_offer.id})">
                <i id="heartIcon${special_offer.id}" class="${heartColorClass}"></i> <!-- Apply inline style here -->
              </a>
              <a class="model-oraganic-product" data-bs-toggle="modal" onClick="sessionStorage.setItem('product_id', ${
                special_offer.id
              }); sessionStorage.setItem('store_id', ${
      special_offer.store_id
    }); sessionStorage.setItem('is_product_size', '${
      special_offer.product_size.length != 0 ? true : false
    }'); sessionStorage.setItem('is_color', '${
      special_offer.product_color.length != 0 ? true : false
    }');  getProductDetails(${special_offer.id})" href="#quickview" role="button">
                <i class="ri-eye-line"></i>
              </a>
              <a class="cr-shopping-bag" id="shoppingBagIconData${
                special_offer.id
              }" style="bottom: -35px !important;" href="javascript:void(0)" onclick="addToCart(${special_offer.id})">
                <i class="ri-shopping-bag-line"></i>
              </a>
            </div>
          </div>
          <div class="cr-product-details">
            <div class="cr-brand">
              <div class="cr-star">
                ${filledStars}
                ${emptyStars}
                <p>(${special_offer.totalReviewCount})</p>
              </div>
            </div>
             <a href="product-detail/${stringToSlug(
               special_offer.product_name
             )}?${queryParams}" onClick="sessionStorage.setItem('product_id', ${
      special_offer.id
    }); sessionStorage.setItem('store_id', ${special_offer.store_id}); sessionStorage.setItem('is_product_size', '${
      special_offer.product_size.length != 0 ? true : false
    }'); sessionStorage.setItem('is_color', '${
      special_offer.product_color.length != 0 ? true : false
    }'); getSingleProductDetails(${special_offer.id})" class="title text-truncate" style="display:block;">${
      special_offer.product_name
    }</a>
        <a href="store-detail/${stringToSlug(special_offer.store_name)}?${querysttoreParams}"
   onClick="sessionStorage.setItem('store_id', ${special_offer.store_id})">
   <p style="color:#00438F;" class="selling-card-sub-title bold">by ${special_offer.store_name}</p>
</a>
            <p class="cr-price">
             <span class="new-price">$${special_offer.product_sale_price}</span>
              <span class="old-price">$${special_offer.product_price}</span>
            </p>
          </div>
        </div>
      </div>
    `;
    container.innerHTML += content;
  });

  // Initialize product slider after data is loaded
  setTimeout(() => {
    $('.cr-twocolumns-special').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 2000,
      responsive: [
        {
          breakpoint: 1600,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 1300,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 992,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 768,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 1
          }
        },
        {
          breakpoint: 576,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    });
  }, 1000); // You can adjust the timeout as needed, depending on how long it takes for the data to load
}

// Product Banner Dynamic
function DisplayIndexProductBanner(productbanners) {
  const container = document.getElementById('indexproductbannerdata');
  container.innerHTML = ''; // Clear any existing content

  productbanners.forEach(imageUrl => {
    const content = `
                            <div class="swiper-slide" data-aos="fade-up" data-aos-duration="2000">
                            <div class="cr-product-banner-image">
                                <img style="height: 15rem !important;object-fit: cover !important;"
                                    src="${imageUrl}"
                                    alt="product-banner">
                            </div>
                        </div>
        `;
    container.innerHTML += content;
  });
}

// Store Dynamic
function DisplayIndexStoreData(stores) {
  const container = document.getElementById('displayindexstoredata');
  container.innerHTML = ''; // Clear any existing content

  stores.slice(0, 6).forEach(stores => {
    var filledStars = '';
    var emptyStars = '';

    // Calculate number of filled and empty stars based on review rating
    for (var i = 0; i < 5; i++) {
      if (i < stores.totalAvgReview) {
        filledStars += '<i class="ri-star-fill"></i>';
      } else {
        emptyStars += '<i class="ri-star-line"></i>';
      }
    }

    // Determine the logo URL or fallback to default
    const storeLogoUrl = stores.store_logo ? stores.store_logo : 'assets/images/storelogo-default.jpg';
    // const firstImageUrl = latest_product.product_image[0];
    const firstImageUrl = stores.store_images.length > 0 ? stores.store_images[0] : 'assets/images/shop-default.jpg';

    // Create query string with product details
    let querysttoreParams = $.param({
      store_id: stores.id
    });

    // Construct the product card HTML content with the first image
    const content = `

<div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12 mb-4">
                <div class="store-card store-card-custom position-relative">
                    <div class="position-relative">
                        <img class="store-card-img-top"
                            src="${firstImageUrl}" alt="Store Image">
                        <img class="product-logo"
                             src="${storeLogoUrl}"
                            alt="Store Logo">
                    </div>
                    <div class="store-card-body store-card-body-custom ">
                        <h5 class="store-card-title bold">${stores.store_name}</h5>
                        <p class="store-card-text">
                      <div class="cr-star" style="color: #FFD428">
                    ${filledStars}
                  ${emptyStars}
                </div>
                        <span class="d-block mt-2">${stores.totalReviewCount} Reviews</span>
                        </p>
                        <div style="position: absolute; right: 1rem;bottom: 1rem;">
                             <a href="store-detail/${stringToSlug(
                               stores.store_name
                             )}?${querysttoreParams}" class="cr-button" onClick="sessionStorage.setItem('store_id', ${
      stores.id
    })";>View</a>
                        </div>
                    </div>
                </div>
            </div>
    `;
    container.innerHTML += content;
  });
}

// Best Selling Products Dynamic
function DisplayIndexBestSellingProductData(bestsellingproduct) {
  const container = document.getElementById('bestsellingproductdata');
  container.innerHTML = ''; // Clear any existing content

  bestsellingproduct.slice(0, 6).forEach(latest_product => {
    var filledStars = '';
    var emptyStars = '';

    // Calculate number of filled and empty stars based on review rating
    for (var i = 0; i < 5; i++) {
      if (i < latest_product.totalAvgReview) {
        filledStars += '<i class="ri-star-fill"></i>';
      } else {
        emptyStars += '<i class="ri-star-line"></i>';
      }
    }

    // Initialize variables for heart icon color and style
    var heartColorClass = latest_product.is_Like == 1 ? 'ri-heart-2-fill icon_color' : 'ri-heart-2-line';

    // Select the first image URL from the product_image array
    // const firstImageUrl = latest_product.product_image[0];
    const firstImageUrl =
      latest_product.product_image.length > 0 ? latest_product.product_image[0] : 'assets/images/product-default.png';

    // Create query string with product details
    let queryParams = $.param({
      product_id: latest_product.id,
      store_id: latest_product.store_id,
      is_product_size: latest_product.product_size.length != 0,
      is_color: latest_product.product_color.length != 0,
      module_id: latest_product.module_id // Include module_id in the query parameters
    });

    // Create query string with product details
    let querysttoreParams = $.param({
      store_id: latest_product.store_id
    });

    // Construct the product card HTML content with the first image
    const content = `
                <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12 mb-4 px-4 pt-4">
                    <div class="row position-relative shadow rounded selling-card">
                        <div class="col-4 position-relative px-0">
                            <a href="product-detail/${stringToSlug(
                              latest_product.product_name
                            )}?${queryParams}" onclick="sessionStorage.setItem('product_id', '${
      latest_product.id
    }'); sessionStorage.setItem('store_id', ${latest_product.store_id});  sessionStorage.setItem('is_product_size', '${
      latest_product.product_size.length != 0 ? true : false
    }'); sessionStorage.setItem('is_color', '${
      latest_product.product_color.length != 0 ? true : false
    }');  getSingleProductDetails('${latest_product.id}')">
                                <img class="selling-card-img-top" style="min-height: 10rem !important; height: 10rem !important;"  src="${firstImageUrl}" alt="${
      latest_product.product_name
    }">
                            </a>
                            <div class="cr-side-view-show">
                <a href="javascript:void(0)" class="wishlist" onclick="sessionStorage.setItem('product_id', ${
                  latest_product.id
                }); sessionStorage.setItem('store_id', ${latest_product.store_id}); likeProductIndex(${
      latest_product.id
    })">
                  <i id="heartIconlatest${latest_product.id}" class="${heartColorClass}"></i>
                </a>
                <a class="model-oraganic-product" data-bs-toggle="modal" onClick="sessionStorage.setItem('product_id', ${
                  latest_product.id
                }); sessionStorage.setItem('store_id', ${
      latest_product.store_id
    }); sessionStorage.setItem('is_product_size', '${
      latest_product.product_size.length != 0 ? true : false
    }'); sessionStorage.setItem('is_color', '${
      latest_product.product_color.length != 0 ? true : false
    }');  getProductDetails(${latest_product.id})" href="#quickview" role="button">
                  <i class="ri-eye-line"></i>
                </a>
              </div>
                        </div>
                        <div class="col-8 selling-card-body selling-card-body-custom">
                            <a href="product-detail/${stringToSlug(
                              latest_product.product_name
                            )}?${queryParams}" onclick="sessionStorage.setItem('product_id', '${
      latest_product.id
    }'); sessionStorage.setItem('store_id', ${latest_product.store_id});  sessionStorage.setItem('is_product_size', '${
      latest_product.product_size.length != 0 ? true : false
    }'); sessionStorage.setItem('is_color', '${
      latest_product.product_color.length != 0 ? true : false
    }');  getSingleProductDetails('${latest_product.id}')">
                                <h5 class="selling-card-title bold text-truncate" style="font-family: 'Poppins', sans-serif;">${
                                  latest_product.product_name
                                }</h5>
                            </a>

                            <a href="store-detail/${stringToSlug(latest_product.store_name)}?${querysttoreParams}"
   onClick="sessionStorage.setItem('store_id', ${latest_product.store_id})">
   <h6 style="color: #00438F; font-family: 'Poppins', sans-serif;" class="selling-card-sub-title bold">by ${
     latest_product.store_name
   }</h6>
</a>
                            <p class="selling-card-text">
                                <div class="cr-star" style="color: #FFD428; margin-bottom:8px;">
                                    ${filledStars}
                                    ${emptyStars}
                                </div>
                              <span style="font-weight: 600; font-size: 24px; color:#00438F" class="mt-2">
  $${latest_product.product_sale_price}
</span>
<span style="font-weight: 400; font-size: 20px; text-decoration: line-through;" class="mt-1">
  $${latest_product.product_price}
</span>

                            </p>
<a style="position: absolute; right: 1rem; bottom: 1rem; font-size: 1.5rem; cursor: pointer;" class="" id="shoppingBagIcon${
      latest_product.id
    }"  onclick="addToCart(${latest_product.id})">
    <i class="ri-shopping-cart-line"></i>
</a>
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

// Function to handle liking/disliking a product
window.likeProductIndex = function () {
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
          let heartIconlatest = document.querySelector(`#heartIconlatest${productId}`);
          heartIconlatest.classList.remove('ri-heart-2-line');
          heartIconlatest.classList.add('ri-heart-2-fill');
          heartIconlatest.classList.toggle('icon_color');
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
          let heartIconlatest = document.querySelector(`#heartIconlatest${productId}`);
          heartIconlatest.classList.add('ri-heart-2-line');
          heartIconlatest.classList.remove('ri-heart-2-fill');
          heartIconlatest.classList.toggle('icon_color');
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

GetIndexMainData();
getcategorylistforindex();
