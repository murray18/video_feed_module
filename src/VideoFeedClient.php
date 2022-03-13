<?php

namespace Drupal\cat_facts;

use Drupal\Component\Serialization\Json;

/**
 * Service for calling pac12 api.
 */
class VideoFeedClient {

  /**
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * VideoFeedClient constructor.
   *
   * @param string $http_client_factory
   *   Client factory.
   */
  public function __construct($http_client_factory) {
    $this->client = $http_client_factory->fromOptions([
      'base_uri' => 'api.pac-12.com/v3',
    ]);
  }

  /**
   * Get VODs.
   *
   * @param int $count
   *   The number of VODs to get.
   */
  public function get($count = 1) {
    $response = $this->client->get('vod', [
      'query' => [
        'count' => $count,
      ],
    ]);

    return Json::decode($response->getBody());
  }

}
