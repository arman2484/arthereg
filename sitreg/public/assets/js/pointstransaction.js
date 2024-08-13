$(document).ready(function () {
  // Function to fetch transaction history from the API
  window.fetchPointsTransaction = function () {
    var token = localStorage.getItem('token');
    var baseUrlLive = sessionStorage.getItem('baseUrlLive');

    $.ajax({
      type: 'GET',
      url: baseUrlLive + '/api/points-data',
      dataType: 'json',
      headers: {
        Authorization: `Bearer ${token}`
      },
      success: function (response) {
        if (response.points && response.points.length > 0) {
          hideNoTransactionMessage();
          renderPointTransactionHistory(response.points);
        } else {
          showNoTransactionMessage();
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  };

  // Function to render transaction history based on API response
  function renderPointTransactionHistory(points) {
    var transactionContainer = $('#transactiondatapoints');
    transactionContainer.empty();

    // Sort points array by createdAtDate in descending order
    points.sort(function (a, b) {
      return new Date(b.created_at) - new Date(a.created_at);
    });

    points.forEach(function (point) {
      // Parse the created_at date string
      var amount = point.amount;
      var createdAtDate = new Date(point.created_at);

      // Format the date
      var formattedDate =
        createdAtDate.getDate() + ' ' + monthNames[createdAtDate.getMonth()] + ' ' + formatAMPM(createdAtDate);

      var transactionItem = `
          <div class="flex justify-between bg-[#F6F7FB] text-gray-500 py-3 rounded-lg px-4">
            <div>Transfered <strong>$${amount}</strong> to wallet</div>
            <div>${formattedDate}</div>
          </div>`;
      transactionContainer.append(transactionItem);
    });
  }

  // Function to hide the "No Transactions Are Found" message
  function hideNoTransactionMessage() {
    $('#notransactionfound').hide();
  }

  // Function to show the "No Transactions Are Found" message
  function showNoTransactionMessage() {
    $('#notransactionfound').show();
  }

  // Function to format time in AM/PM format
  function formatAMPM(date) {
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0' + minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;
    return strTime;
  }

  // Array of month names
  var monthNames = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December'
  ];

  fetchPointsTransaction();
});
