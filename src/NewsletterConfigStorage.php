<?php


namespace Drupal\evsy_newsletter;


use Drupal\Core\Config\Entity\ConfigEntityStorage;
use Drupal\evsy_event\Plugin\AppEventInterface;
use Drupal\evsy_newsletter\Entity\NewsletterConfigInterface;

class NewsletterConfigStorage extends ConfigEntityStorage implements NewsletterConfigStorageInterface {

    public function getEventIds() {
        $ids = [];
        foreach ($this->loadMultiple() as $config) {
            /** @var $config NewsletterConfigInterface */
            $event_id = $config->getEvent();
            if (isset($ids[$event_id])) {
                continue;
            }
            $ids[$event_id] = TRUE;
        }
        return $ids;
    }

    public function loadConfigs(AppEventInterface $event) {
        $configs = $this->loadByProperties(['event' => $event->getPluginId()]);

        $data = $event->getData();

        switch ($event->getSource()) {
            case 'entity':
                /** @var $data \Drupal\Core\Entity\EntityInterface */
                $keys = [
                    'entity_type' => $data->getEntityTypeId(),
                    'bundle' => $data->bundle(),
                ];
                $configs = array_filter($configs, function (NewsletterConfigInterface $config) use ($keys) {
                    return $config->hasKeys($keys);
                });
                break;

            default:
        }
        return $configs;
    }

}
