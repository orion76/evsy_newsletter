<?php

namespace Drupal\evsy_newsletter;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\evsy_event\Plugin\AppEventInterface;
use Drupal\evsy_event\Plugin\AppEventManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function ucfirst;

/**
 * Provides a listing of Newsletter config entities.
 */
class NewsletterConfigListBuilder extends ConfigEntityListBuilder {

    /** @var AppEventManagerInterface */
    private $eventManager;

    public function __construct(EntityTypeInterface $entity_type,
                                EntityStorageInterface $storage,
                                AppEventManagerInterface $eventManager) {
        parent::__construct($entity_type, $storage);
        $this->eventManager = $eventManager;
    }

    public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
        return new static(
            $entity_type,
            $container->get('entity_type.manager')->getStorage($entity_type->id()),
            $container->get('plugin.manager.app_event')
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildHeader() {
        $header['label'] = $this->t('Newsletter config');
        $header['id'] = $this->t('Machine name');
        $header['event'] = $this->t('Event');
        $header['active'] = $this->t('Active');
        $header['transport'] = $this->t('Transport');
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity) {
        /** @var $entity ConfigEntityInterface */
        $row['label'] = $entity->label();
        $row['id'] = $entity->id();

        /** @var $event AppEventInterface */
        $event = $this->eventManager->createInstance($entity->get('event'));

        $row['event'] = $event->getLabel();
        $row['active'] = $entity->get('active')?$this->t('Active'):$this->t('Not active');
        $row['transport'] = $entity->get('transport');

        return $row + parent::buildRow($entity);
    }

}
