<?php

namespace Drupal\evsy_newsletter;

use Drupal\Core\Config\Entity\ConfigEntityStorageInterface;
use Drupal\evsy_event\Plugin\AppEventInterface;

interface NewsletterConfigStorageInterface extends ConfigEntityStorageInterface{

    public function getEventIds();
    public function loadConfigs(AppEventInterface $event);
}
