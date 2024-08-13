let userId = localStorage.getItem('user_id');
let baseUrlLive = sessionStorage.getItem('baseUrlLive');
let module_id = localStorage.getItem('module_id');

// Function to fetch product list with filters
function getFilteredProductData(userId, category_ids, prices) {
  var category_id = category_ids.join(',');
  var pricePairs = [];
  for (var i = 0; i < prices.length; i++) {
    if (prices[i] === '10000_and_above') {
      pricePairs.push('10000');
    } else {
      var range = prices[i].split('_to_');
      pricePairs.push(range.join(','));
    }
  }

  $.ajax({
    type: 'POST',
    url: baseUrlLive + '/api/product-list',
    data: {
      user_id: userId,
      module_id: module_id,
      category_id: category_id,
      price: pricePairs.join(';')
    },
    dataType: 'json',
    success: function (response) {
      if (response && response.message === 'Products found' && response.productFilter) {
        updateFilterOptions(response.productFilter);
        displayInnerProducts(response.products.data);
        updateCategoryCheckboxes(category_ids);
      } else {
        console.error('No products found or invalid response received.');
      }
    },
    error: function (xhr, status, error) {
      console.error('AJAX error: ' + error);
    }
  });
}

// Function to update category checkboxes to remain checked
function updateCategoryCheckboxes(category_ids) {
  $('.cr-shop-categories input[type="checkbox"]').each(function () {
    var categoryId = $(this).attr('id');
    if (category_ids.includes(categoryId)) {
      $(this).prop('checked', true);
    } else {
      $(this).prop('checked', false);
    }
  });
}

// Function to update category options in UI
function updateCategoryOptions(categories) {
  var categoryContainer = $('.cr-shop-categories .cr-checkbox').empty();
  categories.forEach(category => {
    var categoryCheckbox = createCategoryCheckbox(category.id, category.category_name);
    categoryCheckbox.find('input[type="checkbox"]').on('click', function () {
      var selectedCategories = getSelectedOptions('.cr-shop-categories input[type="checkbox"]');
      updateFilter();
      hidePagination();
      updateCategoryCheckboxes(selectedCategories);
    });
    categoryContainer.append(categoryCheckbox);
  });

  // Re-check the previously selected categories
  let selectedCategories = getSelectedOptions('.cr-shop-categories input[type="checkbox"]');
  updateCategoryCheckboxes(selectedCategories);
}

// Function to update filter options in UI
function updateFilterOptions(productFilter) {
  updateCategoryOptions(productFilter.categories);
}

// Function to create a checkbox element
function createCheckbox(id, label) {
  var checkbox = $('<div class="checkbox-group"></div>');
  checkbox.append('<input type="checkbox" id="' + id + '" value="' + id + '">');
  checkbox.append('<label for="' + id + '">' + label + '</label>');
  return checkbox;
}

// Event listener for filter button click
$('.cr-button').on('click', function () {
  updateFilter();
});

$('.cr-shop-price input[type="checkbox"]').on('click', function () {
  updateFilter();
  hidePagination();
});

// Function to get selected options
function getSelectedOptions(selector) {
  return $(selector + ':checked')
    .map(function () {
      return this.id;
    })
    .get();
}

// Function to get selected options
function getSelectedOptionsPrice(selector) {
  return $(selector + ':checked')
    .map(function () {
      return this.value;
    })
    .get();
}

function createCategoryCheckbox(id, category_name) {
  var checkbox = $('<div class="checkbox-group"></div>');
  checkbox.append('<input type="checkbox" id="' + id + '">');
  checkbox.append('<label for="' + id + '">' + category_name + '</label>');
  return checkbox;
}

// Function to update price options in UI
function updatePriceOptions(prices) {
  var priceContainer = $('.cr-shop-price .cr-checkbox').empty();
  prices.forEach(price => {
    var priceCheckbox = createCheckbox(price.id, price.label);
    priceCheckbox.find('input[type="checkbox"]').on('click', function () {
      $('.cr-shop-price input[type="checkbox"]').prop('checked', false);
      $(this).prop('checked', true);
      updateFilter();
    });
    priceContainer.append(priceCheckbox);
  });

  // Re-check the previously selected prices
  let selectedPrices = getSelectedOptionsPrice('.cr-shop-price input[type="checkbox"]');
  updatePriceCheckboxes(selectedPrices);
}

// Function to update price checkboxes to remain checked
function updatePriceCheckboxes(selectedPrices) {
  $('.cr-shop-price input[type="checkbox"]').each(function () {
    var priceValue = $(this).val(); // Get the value instead of id
    if (selectedPrices.includes(priceValue) || (priceValue === '10000_and_above' && selectedPrices.length === 0)) {
      $(this).prop('checked', true);
    } else {
      $(this).prop('checked', false);
    }
  });
}

// Initial call to fetch products with default filters
getFilteredProductData(userId, [], []);

// Common filter update function
function updateFilter() {
  var selectedCategories = getSelectedOptions('.cr-shop-categories input[type="checkbox"]');
  var selectedPrices = getSelectedOptions('.cr-shop-price input[type="checkbox"]');
  getFilteredProductData(userId, selectedCategories, selectedPrices);
}
function hidePagination() {
  $('#pagelist').hide();
}

$(document).on('click', '.cr-shop-sub-title-name:contains("Clear Filter")', function () {
  // Clear all checkboxes
  $('.cr-checkbox input[type="checkbox"]').prop('checked', false);

  displayInnerProducts([]);
  updateFilter();
  showPagination();
});

function showPagination() {
  $('#pagelist').show();
}
