<?php

namespace Drupal\video_feed\Plugin\views\query;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\query\QueryPluginBase;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
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
  public function __construct(ClientInterface $http_client, LoggerChannelFactoryInterface $factory) {
    $this->loggerFactory = $factory;
    $this->httpClient = $http_client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $container->get('http_client'),
      $container->get('logger.factory')
    );
  }

  /**
   * Part of views core assumes a SQL backend.
   *
   * We are using a Query Plugin backend instead so we need this work around.
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
   * Part of views core assumes a SQL backend.
   *
   * We are using a Query Plugin backend instead so we need this work around.
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

    // Sleep for 2 seconds to demonstrate the ajax capabilities.
    sleep(0);

    // @todo It might make sense to put these API calls into their own service
    // in case there's cause to reuse them elsewhere.
    try {
      // This is being logged 8 times per request. Why?
      $this->loggerFactory->get('video_feed')->info('Hit the pac 12 API');

      $display_number = $this->options['vod_display_number'];
      $params = [
        'query' => [
          'pagesize' => $display_number,
        ],
      ];
      $request = $this->httpClient->request('GET', 'http://api.pac-12.com/v3/vod', $params);
      $videos = json_decode($request->getBody()->getContents());

      $schools_request = $this->httpClient->request('GET', 'http://api.pac-12.com/v3/schools');
      $schools = json_decode($schools_request->getBody()->getContents());

      $sports_request = $this->httpClient->request('GET', 'http://api.pac-12.com/v3/sports');
      $sports = json_decode($sports_request->getBody()->getContents());
    }
    catch (Exception $e) {
      $message = 'There was an issue accessing an API endpoint. @error';
      $this->loggerFactory->get('video_feed')->error($message, $e);
    }

    if ($videos->programs) {
      $index = 0;

      foreach ($videos->programs as $video) {
        $school_names = $this->getSchoolName($schools->schools, $video);
        $sport_names = $this->getSportName($sports->sports, $video);

        $row['thumbnail'] = [
          'tiny' => $video->images->tiny,
          'small' => $video->images->small,
          'medium' => $video->images->medium,
          'large' => $video->images->large,
        ];
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
   * Convert a school id to a name @todo add a test.
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
   * Convert a sport id to a name @todo add a test.
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

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['vod_display_number'] = [
      'default' => 10,
    ];
    $options['sport'] = ['default' => 'all'];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    try {
      // An improvement here would be to move this API call into its own plugin.
      $sports_request = $this->httpClient->request('GET', 'http://api.pac-12.com/v3/sports');
      $sports = json_decode($sports_request->getBody()->getContents())->sports;
      $sports_name_array = ['all' => 'All'];
      foreach ($sports as $sport) {
        $sports_name_array[strval($sport->id)] = $sport->short_name;
      }
    }
    catch (Exception $e) {
      $message = 'There was an issue accessing an API endpoint. @error';
      $this->loggerFactory->get('video_feed')->error($message, $e);
    }

    $form['vod_display_number'] = [
      '#type' => 'number',
      '#title' => $this->t('Number of VoD to Display'),
      '#default_value' => $this->options['vod_display_number'],
      '#description' => $this->t('Set how many VoDs to display'),
    ];
    $form['sport'] = [
      '#type' => 'select',
      '#title' => $this->t('Select which sports to display'),
      '#default_value' => $this->options['sport'],
      '#options' => $sports_name_array,
    ];

    parent::buildOptionsForm($form, $form_state);
  }

}
