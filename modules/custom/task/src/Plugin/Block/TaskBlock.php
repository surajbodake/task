<?php

namespace Drupal\task\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provides a block.
 *
 * @Block(
 *   id = "task_block",
 *   admin_label = @Translation("Task Block"),
 * )
 */
class TaskBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * A configuration object.
   *
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $config;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_defination) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_defination,
      $container->get('config.factory')
    );
  }

  /**
   * Creates the task block instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_defination
   *   The plugin implementation defination.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_defination, ConfigFactoryInterface $config_factory) {
    parent::__construct($configuration, $plugin_id, $plugin_defination);

    $this->config = $config_factory->get('task.settings');
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $country = $this->config->get('country');
    $city = $this->config->get('city');
    $timezone = [
      '#lazy_builder' => [
        'task.timezone:getTimeZone',
        [],
      ],
      '#create_placeholder' => TRUE,
    ];

    return [
      '#theme' => 'task_block',
      '#country' => $country,
      '#city' => $city,
      '#timezone' => $timezone,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheTags() {
    return Cache::mergeTags(parent::getCacheTags(), [
      'config:task.settings',
    ]);
  }

}
