<?php

namespace Drupal\evsy_newsletter\Plugin\NewsletterTransport;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Logger\LoggerChannel;
use Drupal\evsy_newsletter\Plugin\NewsletterTransportPluginBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class DrupalLog
 *
 * @NewsletterTransport(
 *   id = "drupal_log",
 *   label = "Drupal Log"
 * )
 */
class DrupalLog extends NewsletterTransportPluginBase {

    /** @var LoggerChannel */
    private $logger;

    public function __construct(array $configuration,
                                $plugin_id,
                                $plugin_definition,
                                EntityTypeManagerInterface $entityTypeManager,
                                LoggerChannel $logger
    ) {
        parent::__construct($configuration, $plugin_id, $plugin_definition, $entityTypeManager);
        $this->logger = $logger;
    }

    public function send($text) {
        $this->logger->debug('NEWSLETTER_DEBUG');
    }

    public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
        return new static(
            $configuration,
            $plugin_id,
            $plugin_definition,
            $container->get('entity_type.manager'),
            $container->get('logger.factory')->get('evsy_newsletter')
        );
    }

    public function getConfigDefault() {
        return [];
    }

    public function getNewsletterConfigForm($config, $form, FormStateInterface $form_state, $ajax = NULL) {
        return [];
    }

    public function getNewsletterConfigDefault() {
        return [];
    }

    public function getConfigForm($config, $form, FormStateInterface $form_state, $ajax = NULL) {
        return [];
    }

    public function getFormConfig($config, $form, FormStateInterface $form_state) {
        return [];
    }
}
