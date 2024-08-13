$(document).ready(function () {
  let baseUrlLive = sessionStorage.getItem('baseUrlLive');
  let module_id = localStorage.getItem('module_id');

  // Function to fetch category list
  window.GetCategoryInnerData = function (page) {
    $.ajax({
      type: 'POST',
      url: baseUrlLive + '/api/get-category',
      data: {
        module_id: module_id
      },
      dataType: 'json',
      success: function (response) {
        // Check if category data exists and has a length greater than 0
        if (response && response.data && response.data.length > 0) {
          console.log('Categories found:', response.data);
          displayInnerCategoryData(response.data);
        } else {
          console.error('No categories found');
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error: ' + error);
      }
    });
  };

  GetCategoryInnerData();

  // Function to display category list
  window.displayInnerCategoryData = function (categories) {
    var categoryContent = $('#innercategorydata');
    categoryContent.empty(); // Clear previous content

    // Loop through each category and generate the corresponding HTML
    $.each(categories, function (index, category) {
      const imageUrl = category.category_image ? category.category_image : 'assets/images/storelogo-default.jpg';
      // Generate the category HTML using template literals
      var categoryHTML = `
                        <div class="col-xxl-3 col-xl-4 col-6 cr-product-box mb-24">
                            <div class="cr-product-card" style="height: 260px;">
                                <a href="productlist" onClick="sessionStorage.setItem('category_id', ${
                                  category.id
                                }); sessionStorage.setItem('sub_category_id', '${category.subcategory_ids.join(
        ','
      )}');">
                                  <div class="cr-product-image">
                                      <img src="${imageUrl}" alt="${category.category_name}" style="height: 160px;">
                                  </div>
                                </a>
                                <div class="cr-product-details">
                                    <a href="productlist" onClick="sessionStorage.setItem('category_id', ${
                                      category.id
                                    }); sessionStorage.setItem('sub_category_id', '${category.subcategory_ids.join(
        ','
      )}');" class="title">${category.category_name}</a>
                                </div>
                            </div>
                        </div>`;

      // Append the generated category HTML to the category content container
      categoryContent.append(categoryHTML);
    });
  };
});
