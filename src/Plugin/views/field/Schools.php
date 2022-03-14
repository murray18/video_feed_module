<?php

namespace Drupal\video_feed\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * Display the schools related to a video.
 *
 * @ViewsField("video_schools")
 */
class Schools extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $schools = $this->getValue($values);

    if ($schools) {
      return [
        '#theme' => 'item_list',
        '#items' => $schools,
      ];
    }
  }

}
