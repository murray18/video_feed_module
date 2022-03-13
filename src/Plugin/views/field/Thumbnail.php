<?php

namespace Drupal\video_feed\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * Display a video thumbnail.
 *
 * @ViewsField("video_thumbnail")
 */
class Thumbnail extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $thumbnail = $this->getValue($values);
    if ($thumbnail) {
      return [
        '#theme' => 'image',
        '#uri' => $thumbnail,
        '#alt' => $this->t('Thumbnail'),
      ];
    }
  }

}
