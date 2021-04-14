<?php

namespace Drupal\evsy_newsletter\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for Transport plugin plugins.
 */
abstract class NewsletterTransportPluginBase extends PluginBase implements NewsletterTransportPluginInterface, ContainerFactoryPluginInterface {


    /** @var ConfigEntityStorageInterface */
    private $configStorage;

    /** @var array */
    private $config = [];

    public function __construct(array $configuration,
                                $plugin_id,
                                $plugin_definition,
                                EntityTypeManagerInterface $entityTypeManager) {
        parent::__construct($configuration, $plugin_id, $plugin_definition);
        $this->configStorage = $entityTypeManager->getStorage('newsletter_transport_config');
    }

    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('entity_type.manager')
        );
    }

    public function getConfig() {
        if (empty($this->config)) {
            $this->config = $this->configStorage->load($this->getPluginId())->getConfig();
        }
        return $this->config;
    }

    abstract public function getConfigForm($config, $form, FormStateInterface $form_state, $ajax = NULL);

    abstract public function getConfigDefault();

    public function getSettings($field_name) {
        if (isset($this->configuration[$field_name])) {
            return $this->configuration[$field_name];
        }
    }

}
