<?php

namespace Drupal\video_feed\Plugin\views\filter;

use Drupal\Core\Form\FormStateInterface;
use Drupal\views\Plugin\views\filter\FilterPluginBase;
use Drupal\Core\Logger\LoggerChannelFactoryInterface;
use GuzzleHttp\ClientInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Filter for sports.
 *
 * @ingroup views_filter_handlers
 *
 * @ViewsFilter("sports")
 */
class Sport extends FilterPluginBase {

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
   * Do not allow for different conditionals.
   *
   * @var bool
   *
   *  * @phpcs:disable Drupal.NamingConventions.ValidVariableName.LowerCamelName
   */
  public $no_operator = TRUE;
  // phpcs:enable

  /**
   * {@inheritdoc}
   */
  protected function valueForm(&$form, FormStateInterface $form_state) {
    try {
      // Get all the sports from the API to display as filter options.
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

    $form['value'] = [
      '#type' => 'select',
      '#title' => $this->t('Please select a sport'),
      '#default_value' => $this->value,
      '#options' => $sports_name_array,
    ];
  }

}
