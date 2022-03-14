<?php

namespace Drupal\video_feed\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
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
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['thumbnail_size'] = ['default' => 'tiny'];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $form['thumbnail_size'] = [
      '#type' => 'select',
      '#title' => $this->t('Thumbnail size'),
      '#default_value' => $this->options['thumbnail_size'],
      '#options' => [
        'tiny' => $this->t('Tiny'),
        'small' => $this->t('Small'),
        'medium' => $this->t('Medium'),
        'large' => $this->t('Large'),
      ],
    ];

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $thumbnail = $this->getValue($values);
    if ($thumbnail) {
      return [
        '#theme' => 'image',
        '#uri' => $thumbnail[$this->options['thumbnail_size']],
        '#alt' => $this->t('Thumbnail'),
      ];
    }
  }

}
