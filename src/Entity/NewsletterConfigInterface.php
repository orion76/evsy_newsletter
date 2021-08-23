<?php

namespace Drupal\evsy_newsletter\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Newsletter config entities.
 */
interface NewsletterConfigInterface extends ConfigEntityInterface {

  /**
   * @return bool
   */
  public function isActive();

  public function getEvent();

  public function getTransport();

  public function getConfig();

  public function getTransportConfig();

  public function getTemplate();

  public function hasKeys($keys);
}
