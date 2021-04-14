<?php

namespace Drupal\evsy_newsletter;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function uasort;

/**
 * Provides a listing of Transport config entities.
 */
class TransportConfigListBuilder extends ConfigEntityListBuilder {

    /** @var PluginManagerInterface */
    private $pluginManager;

    public function __construct(EntityTypeInterface $entity_type,
                                EntityStorageInterface $storage,
                                PluginManagerInterface $pluginManager) {
        parent::__construct($entity_type, $storage);
        $this->pluginManager = $pluginManager;
    }

    public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
        return new static(
            $entity_type,
            $container->get('entity_type.manager')->getStorage($entity_type->id()),
            $container->get('plugin.manager.newsletter_transport_plugin')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildHeader() {
        $header['label'] = $this->t('Transport config');
        $header['id'] = $this->t('Machine name');
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity) {
        $row['label'] = $entity->label();
        $row['id'] = $entity->id();
        // You probably want a few more properties here...
        return $row + parent::buildRow($entity);
    }

    public function load() {
        $this->addPluginsConfig();
        return parent::load();
    }

    protected function addPluginsConfig() {
        $entity_ids = $this->getEntityIds();
        foreach ($this->pluginManager->getDefinitions() as $definition) {
            $id = $definition['id'];
            if (isset($entity_ids[$id])) {
                continue;
            }
            $values = [
                'id' => $id,
                'label' => $definition['label'],
            ];
            $entity = $this->storage->create($values);
            $entity->save();
        }

    }
}
