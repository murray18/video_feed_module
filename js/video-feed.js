/**
 * @file
 * Getting the server time via AJAX.
 */

(function ($, Drupal) {

  'use strict';

  /**
   * Replaces content of video feed block with response from Ajax call.
   */
  Drupal.behaviors.videoFeedGetTime = {
    attach: function () {
      console.log('use ajax in js file')

      $.ajax({
        url: Drupal.url('video-feed-response'),
        type: 'POST',
        dataType: 'json',
        success: function (response) {
          console.log('SUCCESS!')
          console.log(response)
          if(response.hasOwnProperty('block')) {
            const parsedHtml = $.parseHTML(response.block['#markup']);
            console.log(parsedHtml)
            $("#video-feed-target").replaceWith(parsedHtml);
          }
        }
      });
    }
  };

})(jQuery, Drupal);
