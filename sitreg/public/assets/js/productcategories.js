function FetchProductCategoriesFilter() {
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');
  let module_id = localStorage.getItem('module_id');

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/fetch-categoriesfilter',
    dataType: 'json',
    data: { module_id },
    success: function (response) {
      if (response && response.message === 'Categories found' && response.data && response.data.length > 0) {
        console.log('Categories Data Found:', response.data);
        DisplayProductCategoriesFilter(response.data);
      } else {
        console.error('No categories data found');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
    }
  });
}

function DisplayProductCategoriesFilter(categoriesData) {
  const accordionContainer = document.getElementById('productAccordian');
  accordionContainer.innerHTML = ''; // Clear existing content

  let stored_category_id = sessionStorage.getItem('category_id');
  let stored_subcategory_ids = sessionStorage.getItem('sub_category_id')
    ? sessionStorage.getItem('sub_category_id').split(',')
    : [];

  categoriesData.forEach(category => {
    const categoryId = category.id;
    const categoryName = category.category_name;

    // Check if any subcategory in this category is stored as checked
    const isAnySubCategoryChecked = category.sub_categories.some(subCategory =>
      stored_subcategory_ids.includes(String(subCategory.id))
    );

    // Start building the accordion item
    const accordionItem = document.createElement('div');
    accordionItem.className = 'accordion-item';

    // Category Header
    const accordionHeader = document.createElement('h2');
    accordionHeader.className = 'accordion-header';
    accordionHeader.innerHTML = `
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse${categoryId}" aria-expanded="${isAnySubCategoryChecked}" aria-controls="collapse${categoryId}">
        <div class="checkbox-group" style="margin-left: -15px;">
          <input type="checkbox" ${categoryId == stored_category_id || isAnySubCategoryChecked ? 'checked' : ''}
            id="category${categoryId}" class="category-checkbox" style="width: 18px; height: 15px; margin-right: 5px; border: 1px solid #ddd !important;">
          <label style="color: #7a7a7a;">${categoryName}</label>
        </div>
      </button>
    `;
    accordionItem.appendChild(accordionHeader);

    // Category Body
    const accordionBody = document.createElement('div');
    accordionBody.id = `collapse${categoryId}`;
    accordionBody.className = `accordion-collapse collapse ${isAnySubCategoryChecked ? 'show' : ''}`;
    accordionBody.setAttribute('data-bs-parent', '#productAccordian');

    const bodyContent = document.createElement('div');
    bodyContent.className = 'accordion-body';

    const checkboxContainer = document.createElement('div');
    checkboxContainer.className = 'cr-checkbox';

    category.sub_categories.forEach(subCategory => {
      const subCategoryId = subCategory.id;
      const subCategoryName = subCategory.sub_category_name;

      const checkboxGroup = document.createElement('div');
      checkboxGroup.className = 'checkbox-group';

      const checkboxInput = document.createElement('input');
      checkboxInput.type = 'checkbox';
      checkboxInput.id = `subCategory${subCategoryId}`;
      checkboxInput.className = 'subcategory-checkbox';
      checkboxInput.checked = stored_subcategory_ids.includes(String(subCategoryId));

      const checkboxLabel = document.createElement('label');
      checkboxLabel.setAttribute('for', `subCategory${subCategoryId}`);
      checkboxLabel.innerText = subCategoryName;

      checkboxGroup.appendChild(checkboxInput);
      checkboxGroup.appendChild(checkboxLabel);
      checkboxContainer.appendChild(checkboxGroup);
    });

    bodyContent.appendChild(checkboxContainer);
    accordionBody.appendChild(bodyContent);
    accordionItem.appendChild(accordionBody);
    accordionContainer.appendChild(accordionItem);
  });

  // Add event listener for category checkboxes
  document.querySelectorAll('.category-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', handleCategorySelection);
  });

  // Add event listener for subcategory checkboxes
  document.querySelectorAll('.subcategory-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', handleSubCategorySelection);
  });

  // Check the initial state to show/hide "Clear Filter"
  toggleClearFilterVisibility();
}

// Function to handle category and subcategory selection changes
// function handleCategorySelection() {
//   // Check/uncheck all subcategories when a category is checked/unchecked
//   document.querySelectorAll('.category-checkbox').forEach(categoryCheckbox => {
//     const categoryId = categoryCheckbox.id.replace('category', '');
//     const subcategoryCheckboxes = document.querySelectorAll(`#collapse${categoryId} .subcategory-checkbox`);
//     subcategoryCheckboxes.forEach(subCheckbox => {
//       subCheckbox.checked = categoryCheckbox.checked;
//     });
//   });

//   // Update selections
//   updateSelections();
// }

// Function to handle subcategory selection changes
// Function to handle category and subcategory selection changes
function handleCategorySelection() {
  // Check/uncheck all subcategories when a category is checked/unchecked
  document.querySelectorAll('.category-checkbox').forEach(categoryCheckbox => {
    const categoryId = categoryCheckbox.id.replace('category', '');
    const subcategoryCheckboxes = document.querySelectorAll(`#collapse${categoryId} .subcategory-checkbox`);
    subcategoryCheckboxes.forEach(subCheckbox => {
      subCheckbox.checked = categoryCheckbox.checked;
    });
  });

  // Update selections
  debounceUpdateSelections();
}

// Function to handle subcategory selection changes
function handleSubCategorySelection() {
  // Check if any subcategory is checked, then check the corresponding category
  document.querySelectorAll('.accordion-item').forEach(item => {
    const categoryCheckbox = item.querySelector('.category-checkbox');
    const subcategoryCheckboxes = item.querySelectorAll('.subcategory-checkbox');
    const isAnySubCategoryChecked = Array.from(subcategoryCheckboxes).some(checkbox => checkbox.checked);

    if (isAnySubCategoryChecked) {
      categoryCheckbox.checked = true;
    }

    // Update selections
    debounceUpdateSelections();
  });
}

// Debounce function to limit the number of API calls
let debounceTimer;
function debounceUpdateSelections() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(updateSelections, 300);
}

// Function to update selections and fetch data
function updateSelections() {
  const selectedCategories = Array.from(document.querySelectorAll('.category-checkbox:checked')).map(checkbox =>
    checkbox.id.replace('category', '')
  );

  const selectedSubCategories = Array.from(document.querySelectorAll('.subcategory-checkbox:checked')).map(checkbox =>
    checkbox.id.replace('subCategory', '')
  );

  if (selectedCategories.length > 0 || selectedSubCategories.length > 0) {
    sessionStorage.setItem('category_id', selectedCategories.join(','));
    sessionStorage.setItem('sub_category_id', selectedSubCategories.join(','));
    displaydataasperfiltering(selectedCategories, selectedSubCategories);
  } else {
    sessionStorage.removeItem('category_id');
    sessionStorage.removeItem('sub_category_id');
    getInnerProductList();
    showPagination();
  }
  // Toggle "Clear Filter" visibility based on the current selection
  toggleClearFilterVisibility();
}

document.addEventListener('DOMContentLoaded', function () {
  FetchProductCategoriesFilter();
});

// Function to toggle the visibility of the "Clear Filter" button
function toggleClearFilterVisibility() {
  const isAnyCheckboxChecked =
    document.querySelector('.category-checkbox:checked') || document.querySelector('.subcategory-checkbox:checked');
  const clearFilterButton = document.querySelector('.cr-shop-sub-title-name[style*="cursor: pointer"]');
  if (isAnyCheckboxChecked) {
    clearFilterButton.style.display = 'block';
    hidePagination(); // Hide pagination when any checkbox is checked
  } else {
    clearFilterButton.style.display = 'none';
    showPagination(); // Show pagination when no checkbox is checked
  }
}
function displaydataasperfiltering(categoryIds, SubCategoryIds) {
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');
  let module_id = localStorage.getItem('module_id');
  let userId = localStorage.getItem('userId'); // Assuming userId is stored in sessionStorage

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/product-list',
    data: {
      module_id: module_id,
      user_id: userId,
      category_id: categoryIds.join(','), // Pass category IDs as a comma-separated string
      sub_category_id: SubCategoryIds.join(',') // Pass category IDs as a comma-separated string
    },
    dataType: 'json',
    success: function (response) {
      // Check if products data exists and has a length greater than 0
      if (response && response.products && response.products.data.length > 0) {
        console.log('Products found:', response.products.data);

        DisplayDataAsperFiltering(response.products.data);
        hidePagination();
      } else {
        console.error('No products found');
        displayNoCouponFoundMessage();
        hidePagination();
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
      displayNoCouponFoundMessage();
      hidePagination();
    }
  });
}

// Function to display product list
function DisplayDataAsperFiltering(products) {
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
                <img src="${product.product_image}" alt="${product.product_name}" style="height:200px; width:200px;">
            </a>

            <div class="cr-side-view">
              <a href="javascript:void(0)" class="wishlist" onclick="sessionStorage.setItem('product_id', ${
                product.id
              }); sessionStorage.setItem('store_id', ${product.store_id}); likeProduct(${product.id})">
                <i id="heartIcon${product.id}" class="${heartColorClass}"></i> <!-- Apply inline style here -->
              </a>
              <a class="model-oraganic-product" data-bs-toggle="modal" onClick="sessionStorage.setItem('product_id', ${
                product.id
              }); sessionStorage.setItem('store_id', ${product.store_id}); sessionStorage.setItem('is_product_size', '${
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

    // Append the generated product HTML to the product content container
    productContent.append(productHTML);
  });
}

 function stringToSlug(inputString) {
   // Remove leading and trailing whitespace and convert to lowercase
   const cleanedString = inputString.trim().toLowerCase();

   // Replace spaces with hyphens
   const slug = cleanedString.replace(/\s+/g, '-');

   return slug;
 }

function displayNoCouponFoundMessage() {
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

function hidePagination() {
  $('#pagelist').hide();
}

// $(document).on('click', '.cr-shop-sub-title-name[style*="cursor: pointer"]', function () {
//   alert("clear;")
//   // Clear all checkboxes
//   $('.cr-checkbox input[type="checkbox"]').prop('checked', false);
//   $('.category-checkbox').prop('checked', false);

//   sessionStorage.removeItem('category_id');
//   sessionStorage.removeItem('sub_category_id');

//   getInnerProductList();
//   showPagination();
// });

function showPagination() {
  $('#pagelist').show();
}
