<?php

namespace Drupal\evsy_newsletter\Subscriber;

use Drupal;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Utility\Token;
use Drupal\evsy_event\Plugin\AppEventInterface;
use Drupal\evsy_newsletter\Entity\NewsletterConfigInterface;
use Drupal\evsy_newsletter\NewsletterConfigStorageInterface;
use Drupal\evsy_newsletter\Plugin\NewsletterTransportPluginInterface;
use Drupal\evsy_newsletter\Plugin\NewsletterTransportPluginManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use function strip_tags;

class NewsletterSubscriber implements EventSubscriberInterface {

  /** @var EntityTypeManagerInterface */
  private $configStorage;

  /** @var Token */
  private $token;

  public function __construct(EntityTypeManagerInterface $entityTypeManager, Token $token) {
    $this->configStorage = $entityTypeManager->getStorage('newsletter_config');
    $this->token = $token;
  }


  protected static function getEventNames() {
    return [
      'evsy:entity:insert',
      'evsy:entity:update',
      'evsy:entity:delete',
    ];
  }


  public static function getSubscribedEvents() {
    $events = [];
    foreach (static::getEventNames() as $event) {
      $events[$event] = 'handleEvent';
    }
    return $events;
  }

  public function handleEvent(AppEventInterface $event) {
    /** @var $storage NewsletterConfigStorageInterface */
    $storage = Drupal::service('entity_type.manager')->getStorage('newsletter_config');
    /** @var $transportManager NewsletterTransportPluginManager */
    $transportManager = Drupal::service('plugin.manager.newsletter_transport_plugin');

    $data = $event->getData();

    foreach ($storage->loadConfigs($event) as $newsletterConfig) {
      /** @var $newsletterConfig NewsletterConfigInterface */
      
      if (FALSE === $newsletterConfig->isActive()) {
        continue;
      }


      /** @var $transport NewsletterTransportPluginInterface */
      $config = $newsletterConfig->get('config');

      $output = $this->token->replace($newsletterConfig->getTemplate(), [$config['entity_type'] => $data]);
      $output = strip_tags($output);
      $output = html_entity_decode($output);

      $transport = $transportManager->createInstance($newsletterConfig->getTransport(), $newsletterConfig->getTransportConfig());
      $transport->send($output);
    }
  }
}
