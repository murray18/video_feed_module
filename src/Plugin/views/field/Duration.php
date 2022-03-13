<?php

namespace Drupal\video_feed\Plugin\views\field;

use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * Display a video duration.
 *
 * @ViewsField("video_duration")
 */
class Duration extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $duration = $this->getValue($values);

    $value = $this->convertToFormattedDate($duration);

    if ($value) {
      return [
        '#markup' => $value,
      ];
    }
  }

  /**
   * Convert a string from ms to mm:ss.
   *
   * Attribution: https://stackoverflow.com/questions/17820239/convert-secondsfloat-into-mmssms-in-php.
   *
   * @param string $duration
   *   The string to convert.
   *
   * @return string
   *   A formatted string value.
   */
  private function convertToFormattedDate($duration) {
    $uSec = $duration % 1000;
    $duration = floor($duration / 1000);
    $seconds = $duration % 60;
    $duration = floor($duration / 60);
    $minutes = $duration % 60;
    $minutes = str_pad($minutes, 2, '0', STR_PAD_LEFT);
    $seconds = str_pad($seconds, 2, '0', STR_PAD_LEFT);

    $time = "$minutes:$seconds";
    return $time;
  }

}
