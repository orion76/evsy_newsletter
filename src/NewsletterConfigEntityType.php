<?php


namespace Drupal\evsy_newsletter;


use Drupal\Core\Config\Entity\ConfigEntityType;

class NewsletterConfigEntityType extends ConfigEntityType {

    public function __construct($definition) {
        
        parent::__construct($definition);
    }
}
