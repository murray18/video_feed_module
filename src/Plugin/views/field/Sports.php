<?php

namespace Drupal\video_feed\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * Display the sports related to a video.
 *
 * @ViewsField("video_sports")
 */
class Sports extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $sports = $this->getValue($values);

    if ($sports) {
      return [
        '#title' => t('Sports'),
        '#theme' => 'item_list',
        '#items' => $sports,
      ];
    }
  }

}
