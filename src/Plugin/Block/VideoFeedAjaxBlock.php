<?php

namespace Drupal\video_feed\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;

/**
 * Provides the 'Video Feed' Block.
 *
 * @Block(
 *   id = "video_feed_block",
 *   admin_label = @Translation("Video Feed Ajax Block"),
 *   category= @Translation("Video Feed"),
 * )
 *
 * @package Drupal\video_feed\Plugin\Block
 */
class VideoFeedAjaxBlock extends BlockBase implements BlockPluginInterface {

  /**
   * {@inheritDoc}
   */
  public function build(): array {
    return [
      '#markup' => $this->t("<div id='video-feed-target'>Loading...</div>"),
      '#attached' => [
        'library' => [
          'video_feed/video-feed',
        ],
      ],
    ];
  }

}
