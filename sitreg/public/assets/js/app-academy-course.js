'use strict';

$(document).ready(function () {
  // Select2 initialization
  $('#select2_course_select').select2({
    dropdownCss: {
      minWidth: '150px' // Set a minimum width for the dropdown
    }
  });

  // Handle store selection change
  $('#select2_course_select').on('change', function () {
    var storeId = $(this).val();
    var url = new URL(window.location.href);
    url.searchParams.set('store_id', storeId);
    window.location.href = url.href;
  });

  // Plyr video player initialization and styling
  const videoPlayer = new Plyr('#guitar-video-player');
  const videoPlayer2 = new Plyr('#guitar-video-player-2');

  // Check if Plyr elements exist before applying styles
  if (document.getElementsByClassName('plyr').length > 0) {
    document.getElementsByClassName('plyr')[0].style.borderRadius = '4px';
    document.getElementsByClassName('plyr')[1].style.borderRadius = '4px';
    document.getElementsByClassName('plyr__poster')[0].style.display = 'none';
    document.getElementsByClassName('plyr__poster')[1].style.display = 'none';
  }
});
