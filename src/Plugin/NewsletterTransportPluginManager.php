<?php

namespace Drupal\evsy_newsletter\Plugin;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Provides the Transport plugin plugin manager.
 */
class NewsletterTransportPluginManager extends DefaultPluginManager implements NewsletterTransportPluginManagerInterface {

    /**
     * Constructs a new TransportPluginManager object.
     *
     * @param \Traversable $namespaces
     *   An object that implements \Traversable which contains the root paths
     *   keyed by the corresponding namespace to look for plugin implementations.
     * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
     *   Cache backend instance to use.
     * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
     *   The module handler to invoke the alter hook with.
     */
    public function __construct(\Traversable $namespaces,
                                CacheBackendInterface $cache_backend,
                                ModuleHandlerInterface $module_handler

    ) {
        parent::__construct(
            'Plugin/NewsletterTransport',
            $namespaces,
            $module_handler,
            'Drupal\evsy_newsletter\Plugin\NewsletterTransportPluginInterface',
            'Drupal\evsy_newsletter\Annotation\NewsletterTransport'
        );

        $this->alterInfo('evsy_newsletter_transport_plugin_info');
        $this->setCacheBackend($cache_backend, 'evsy_newsletter_transport_plugin_plugins');

    }

}
