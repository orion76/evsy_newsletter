<?php

namespace Drupal\evsy_newsletter\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;

/**
 * Provides an interface for defining Transport config entities.
 */
interface NewsletterTransportConfigInterface extends ConfigEntityInterface {

    public function getPlugin();

    public function getConfig();

}
