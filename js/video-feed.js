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
          if(response.hasOwnProperty('block')) {
            console.log('Loading VOD data from pac 12 backend.')
            const parsedHtml = $.parseHTML(response.block['#markup']);
            $("#video-feed-target").replaceWith(parsedHtml);
          }
        }
      });
    }
  };

})(jQuery, Drupal);
