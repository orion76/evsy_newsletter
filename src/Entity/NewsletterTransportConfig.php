<?php

namespace Drupal\evsy_newsletter\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\evsy_newsletter\Plugin\NewsletterTransportPluginInterface;
use Drupal\evsy_newsletter\Plugin\NewsletterTransportPluginManager;

/**
 * Defines the Transport config entity.
 *
 * @ConfigEntityType(
 *   id = "newsletter_transport_config",
 *   label = @Translation("Transport config"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\evsy_newsletter\TransportConfigListBuilder",
 *     "form" = {
 *       "edit" = "Drupal\evsy_newsletter\Form\TransportConfigForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\evsy_newsletter\TransportConfigHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "newsletter_transport_config",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/newsletter/transport_config/{newsletter_transport_config}",
 *     "edit-form" = "/admin/structure/newsletter/transport_config/{newsletter_transport_config}/edit",
 *     "collection" = "/admin/structure/newsletter/transport_config"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "config"
 *   }
 * )
 */
class NewsletterTransportConfig extends ConfigEntityBase implements NewsletterTransportConfigInterface {

    /**
     * The Transport config ID.
     *
     * @var string
     */
    protected $id;

    /**
     * The Transport config label.
     *
     * @var string
     */
    protected $label;


    /** @var NewsletterTransportPluginManager */
    protected $pluginManager;

    /** @var NewsletterTransportPluginInterface */
    protected $plugin;


    /**
     * @return NewsletterTransportPluginManager
     */
    protected function getPluginManager() {
        if (empty($this->pluginManager)) {
            $this->pluginManager = \Drupal::service('plugin.manager.newsletter_transport_plugin');
        }
        return $this->pluginManager;
    }

    /**
     * @return \Drupal\evsy_newsletter\Plugin\NewsletterTransportPluginInterface
     * @throws \Drupal\Component\Plugin\Exception\PluginException
     */
    public function getPlugin() {
        if (empty($this->plugin)) {
            $this->plugin = $this->getPluginManager()->createInstance($this->get('id'));
        }
        return $this->plugin;
    }

    public function getConfig() {
        $config = $this->get('config');
        if (is_null($config)) {
            $config = [];
        }
        return $config + $this->getPlugin()->getConfigDefault();
    }

}
