<?php

namespace Drupal\video_feed\Plugin\views\query;

use Drupal\views\Plugin\views\query\QueryPluginBase;
use Drupal\views\ViewExecutable;
use Drupal\views\ResultRow;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Wrap calls to the Pac12 API in order to expose the results to views.
 *
 * @ViewsQuery(
 *   id = "video_feed",
 *   title = @Translation("Video Feed"),
 *   help = @Translation("Query against the Pac 12 API.")
 * )
 */
class VideoFeed extends QueryPluginBase {

  /**
   * Guzzle\Client instance.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('http_client')
    );
  }

  /**
   * Writes an .htaccess file in the given directory, if it doesn't exist.
   *
   * @param string $table
   *   The table.
   * @param bool $relationship
   *   Table relationship.
   *
   * @return string
   *   The return value
   */
  public function ensureTable($table, $relationship = NULL) {
    return '';
  }

  /**
   * Writes an .htaccess file in the given directory, if it doesn't exist.
   *
   * @param string $table
   *   The table.
   * @param string $field
   *   Table relationship.
   * @param string $alias
   *   Alias.
   * @param string $params
   *   Params.
   *
   * @return string
   *   The return value
   */
  public function addField($table, $field, $alias = '', $params = []) {
    return $field;
  }

  /**
   * {@inheritdoc}
   */
  public function execute(ViewExecutable $view) {

    // Sleep for 5 seconds to demonstrate the ajax capabilities.
    sleep(5);

    $request = $this->httpClient->request('GET', 'http://api.pac-12.com/v3/vod');
    $videos = json_decode($request->getBody()->getContents());

    $schools_request = $this->httpClient->request('GET', 'http://api.pac-12.com/v3/schools');
    $schools = json_decode($schools_request->getBody()->getContents());

    $sports_request = $this->httpClient->request('GET', 'http://api.pac-12.com/v3/sports');
    $sports = json_decode($sports_request->getBody()->getContents());

    if ($videos->programs) {
      $index = 0;

      foreach ($videos->programs as $video) {
        $school_names = $this->getSchoolName($schools->schools, $video);
        $sport_names = $this->getSportName($sports->sports, $video);

        $row['thumbnail'] = $video->images->tiny;
        $row['title'] = $video->title;
        $row['duration'] = $video->duration;
        $row['schools'] = $school_names;
        $row['sports'] = $sport_names;
        $row['index'] = $index++;
        $view->result[] = new ResultRow($row);
      }
    }
  }

  /**
   * Convert a school id to a name.
   *
   * @param object $schools
   *   The school class.
   * @param object $video
   *   The school class.
   */
  private function getSchoolName($schools, $video) {
    $school_names = [];
    foreach ($video->schools as $school_id) {
      $id = $school_id->id;

      $key = array_search($id, array_column($schools, 'id'));

      $school_names[] = $schools[$key]->name;
    }

    return $school_names;
  }

  /**
   * Convert a sport id to a name.
   *
   * @param object $sports
   *   The sport class.
   * @param object $video
   *   The sport class.
   */
  private function getSportName($sports, $video) {
    $sport_names = [];
    foreach ($video->sports as $school_id) {
      $id = $school_id->id;

      $key = array_search($id, array_column($sports, 'id'));

      $sport_names[] = $sports[$key]->name;
    }

    return $sport_names;
  }

}
