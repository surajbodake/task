<?php

namespace Drupal\task\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Security\TrustedCallbackInterface;

/**
 * Service to get the timezone.
 */
class TimeZoneService implements TrustedCallbackInterface {

  /**
   * A configuration object.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * Constructor for TimeZoneService.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The configuration factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->get('task.settings');
  }

  /**
   * Get the timezone.
   */
  public function getTimeZone() {
    $timezone = $this->config->get('timezone');
    $date = new DrupalDateTime('now', $timezone);

    return [
      '#markup' => $date->format('jS M Y - h:i A'),
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public static function trustedcallbacks() {
    return ['getTimeZone'];
  }

}
