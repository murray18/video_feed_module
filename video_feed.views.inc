<?php

/**
 * @file
 * Use the Pac12 API as the views data source.
 */

/**
 * Implements hook_views_data().
 */
function video_feed_views_data() {
  $data = [];
  // Base data.
  $data['video_feed_content']['table']['group'] = t('Video Feed');
  $data['video_feed_content']['table']['base'] = [
    'title' => t('Video Feed API Source'),
    'help' => t('Pac 12 VOD data provided via their API.'),
    'query_id' => 'video_feed',
  ];

  // Fields.
  $data['video_feed_content']['thumbnail'] = [
    'title' => t('Thumbnail'),
    'help' => t('Thumbnail'),
    'field' => [
      'id' => 'video_thumbnail',
    ],
  ];
  $data['video_feed_content']['title'] = [
    'title' => t('Title'),
    'help' => t('Title'),
    'field' => [
      'id' => 'standard',
    ],
  ];
  $data['video_feed_content']['duration'] = [
    'title' => t('Duration'),
    'help' => t('Duration'),
    'field' => [
      'id' => 'video_duration',
    ],
  ];
  $data['video_feed_content']['schools'] = [
    'title' => t('Schools'),
    'help' => t('Schools'),
    'field' => [
      'id' => 'video_schools',
    ],
  ];
  $data['video_feed_content']['sports'] = [
    'title' => t('Sports'),
    'help' => t('Sports'),
    'field' => [
      'id' => 'video_sports',
    ],
  ];

  return $data;

}
