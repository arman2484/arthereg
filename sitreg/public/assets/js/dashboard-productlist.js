$(document).ready(function () {
  // Function to fetch product list
  function getProductList() {
    let baseUrlLive = sessionStorage.getItem('baseUrlLive');
    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/dashboard-product-list',
      dataType: 'json',
      success: function (response) {
        if (response && response.product && response.product.length > 0) {
          console.log('Products found:', response.product);
          displayProducts(response.product.slice(0, 8));
        } else {
          console.error('No products found');
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
      }
    });
  }

  // Function to fetch category data
  function getCategoryData() {
    let baseUrlLive = sessionStorage.getItem('baseUrlLive');
    $.ajax({
      type: 'GET',
      url: baseUrlLive + '/api/category',
      dataType: 'json',
      success: function (response) {
        if (response && response.data && response.data.length > 0) {
          console.log('Category data found:', response.data);
          displayCategory(response.data);
        } else {
          console.error('No category data found');
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
      }
    });
  }
  // Call the functions to fetch product list and category data
  getProductList();
  getCategoryData();

  // Function to display product list
  function displayProducts(products) {
    var productContent = $('#MixItUpDA2FB7');
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

      var productHTML = `
            <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6 col-sm-6 col-12 mb-4">
                <div class="cr-product-card">
                    <div class="cr-product-image">
                        <div class="cr-image-inner zoom-image-hover">
                            <img src="${product.product_image}" alt="${product.product_name}" style="height:200px; width:200px;" >
                        </div>
                        <div class="cr-side-view">
                            <a href="javascript:void(0)" class="wishlist">
                                <i class="ri-heart-line"></i>
                            </a>
                            <a class="model-oraganic-product" data-bs-toggle="modal" href="#quickview" role="button">
                                <i class="ri-eye-line"></i>
                            </a>
                        </div>
                        <a class="cr-shopping-bag" href="javascript:void(0)">
                            <i class="ri-shopping-bag-line"></i>
                        </a>
                    </div>
                    <div class="cr-product-details">
                        <div class="cr-brand">
                            <a href="shop-left-sidebar.html">${product.sub_category_name}</a>
                            <div class="cr-star">
                                ${filledStars}
                                ${emptyStars}
                                <p>(${product.totalAvgReview})</p>
                            </div>
                        </div>
                        <a href="product-left-sidebar.html" class="title">${product.product_name}</a>
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

  // Function to display latest five categories in the banner
  function displayCategory(categories) {
    var categoryContent = $('#categoryMixItUpDA2FB7');

    // Sort categories by date in descending order to get the latest ones
    categories.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));

    // Take only the first five categories
    var latestCategories = categories.slice(0, 5);

    latestCategories.forEach(category => {
      let categoryHTML = `
            <div class="cr-ice-cubes">
                <img src="${category.category_image.replace(
                  'https://ecommerce.theprimoapp.com/public/',
                  'https://ecommerce.theprimoapp.com/'
                )}" alt="${category.category_name} banner">

            </div>
        `;
      categoryContent.append(categoryHTML);
    });
  }
});
