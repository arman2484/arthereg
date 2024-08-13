$(document).ready(function () {
  // Function to fetch transaction history from the API
  function fetchTransactionHistory() {
    var token = localStorage.getItem('token');
    var baseUrlLive = sessionStorage.getItem('baseUrlLive');

    $.ajax({
      type: 'GET',
      url: baseUrlLive + '/api/wallet-data',
      dataType: 'json',
      headers: {
        Authorization: `Bearer ${token}`
      },
      success: function (response) {
        if (response.wallets && response.wallets.length > 0) {
          hideNoTransactionData();
          renderTransactionHistory(response.wallets);
        } else {
          showNoTransactionData();
        }
      },
      error: function (xhr, status, error) {
        console.error(xhr.responseText);
      }
    });
  }

  // Function to render transaction history based on API response
  function renderTransactionHistory(wallets) {
    var transactionContainer = $('#transactiondata');
    transactionContainer.empty();

    // Sort wallets array by createdAtDate in descending order
    wallets.sort(function (a, b) {
      return new Date(b.created_at) - new Date(a.created_at);
    });

    wallets.forEach(function (wallet) {
      // Parse the created_at date string
      var createdAtDate = new Date(wallet.created_at);

      // Format the date
      var formattedDate =
        createdAtDate.getDate() + ' ' + monthNames[createdAtDate.getMonth()] + ' ' + formatAMPM(createdAtDate);

      // Format the amount based on status
      var formattedAmount = wallet.status === 'add' ? '+ $' + wallet.amount : '- $' + wallet.amount;

      var transactionItem = `
      <div class="flex justify-between bg-[#F6F7FB] text-gray-500 py-3 rounded-lg px-4">
        <div>Payment through ${wallet.payment_method}</div>
        <div class="font-bold">+${wallet.amount}</div>
        <div>${formattedDate}</div>
      </div>`;
      transactionContainer.append(transactionItem);
    });
  }

  // Function to hide the "No Transactions Are Found" message
  function hideNoTransactionData() {
    $('#notransaction').hide();
  }

  // Function to show the "No Transactions Are Found" message
  function showNoTransactionData() {
    $('#notransaction').show();
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

  fetchTransactionHistory();
});
