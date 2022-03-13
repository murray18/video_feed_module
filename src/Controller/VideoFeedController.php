<?php

namespace Drupal\video_feed\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns Ajax response for Video feed route.
 */
class VideoFeedController extends ControllerBase {

  /**
   * Returns Ajax Response containing the video feed view.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Ajax response containing html to render time.
   */
  public function createVideoFeedResponse(): JsonResponse {
    $views_block = views_embed_view('video_feed', 'block_1');
    // @todo inject this service.
    $rendered_block['#markup'] = \Drupal::service('renderer')->render($views_block);

    $response['block'] = $rendered_block;
    return new JsonResponse($response);
  }

}
