<?php

namespace Drupal\evsy_newsletter\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;

/**
 * Defines the Newsletter config entity.
 *
 * @ConfigEntityType(
 *   id = "newsletter_config",
 *   label = @Translation("Newsletter config"),
 *   entity_type_class = "Drupal\evsy_newsletter\NewsletterConfigEntityType" ,
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\evsy_newsletter\NewsletterConfigListBuilder",
 *     "form" = {
 *       "add" = "Drupal\evsy_newsletter\Form\NewsletterConfigForm",
 *       "edit" = "Drupal\evsy_newsletter\Form\NewsletterConfigForm",
 *       "delete" = "Drupal\evsy_newsletter\Form\NewsletterConfigDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\evsy_newsletter\NewsletterConfigHtmlRouteProvider",
 *     },
 *     "storage" = "Drupal\evsy_newsletter\NewsletterConfigStorage",
 *   },
 *   config_prefix = "newsletter_config",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/newsletter/newsletter_config/{newsletter_config}",
 *     "add-form" = "/admin/structure/newsletter/newsletter_config/add",
 *     "edit-form" = "/admin/structure/newsletter/newsletter_config/{newsletter_config}/edit",
 *     "delete-form" = "/admin/structure/newsletter/newsletter_config/{newsletter_config}/delete",
 *     "collection" = "/admin/structure/newsletter/newsletter_config"
 *   },
 *   config_export = {
 *     "id",
 *     "active",
 *     "label",
 *     "event",
 *     "transport",
 *     "config",
 *     "transport_config",
 *     "template",
 *   }
 * )
 */
class NewsletterConfig extends ConfigEntityBase implements NewsletterConfigInterface {

  /**
   * The Newsletter config ID.
   *
   * @var string
   */
  protected $id;

  /** @var boolean */
  protected $active;

  /**
   * The Newsletter config label.
   *
   * @var string
   */
  protected $label;

  protected $event;

  protected $transport;

  protected $config = [];

  protected $transport_config = [];

  protected $template = [];

  public function getEvent() {
    return $this->event;
  }

  public function getTransport() {
    return $this->transport;
  }

  public function getConfig() {
    return empty($this->config) ? [] : $this->config;
  }

  public function getTransportConfig() {
    return empty($this->transport_config) ? [] : $this->transport_config;
  }

  public function getTemplate() {
    return $this->template;
  }

  public function hasKeys($keys) {
    foreach ($keys as $name => $value) {
      if (FALSE === $this->compareKey($name, $value)) {
        return FALSE;
      }
    }
    return TRUE;
  }

  /**
   * @return bool
   */
  public function isActive() {
    return (boolean) $this->active;
  }

  protected function compareKey($name, $value) {
    return isset($this->config[$name]) && !empty($value) && $this->config[$name] === $value;
  }
  
}
