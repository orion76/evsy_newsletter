<?php

namespace Drupal\evsy_newsletter\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\evsy_newsletter\Entity\NewsletterTransportConfigInterface;
use Drupal\evsy_newsletter\Plugin\NewsletterTransportPluginInterface;

/**
 * Class TransportConfigForm.
 */
class TransportConfigForm extends EntityForm {

    /** @var NewsletterTransportConfigInterface */
    protected $entity;

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = parent::buildForm($form, $form_state);

        $entity = $this->entity;
        $form['label'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Label'),
            '#maxlength' => 255,
            '#default_value' => $entity->label(),
            '#description' => $this->t("Label for the Transport config."),
            '#required' => TRUE,
        ];

        $form['id'] = [
            '#type' => 'machine_name',
            '#default_value' => $entity->id(),
            '#machine_name' => [
                'exists' => '\Drupal\evsy_newsletter\Entity\TransportConfig::load',
            ],
            '#disabled' => !$entity->isNew(),
        ];
        /** @var $plugin NewsletterTransportPluginInterface */
        $plugin = $entity->getPlugin();

        $form['config'] = [
            '#type' => 'fieldset',
            '#title' => '',
            '#tree' => TRUE,
        ];

        $form['config'] += $plugin->getFormConfig($entity->getConfig(), $form, $form_state);

        
        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $form_state) {
        $transport_config = $this->entity;
        $status = $transport_config->save();

        switch ($status) {
            case SAVED_NEW:
                $this->messenger()->addMessage($this->t('Created the %label Transport config.', [
                    '%label' => $transport_config->label(),
                ]));
                break;

            default:
                $this->messenger()->addMessage($this->t('Saved the %label Transport config.', [
                    '%label' => $transport_config->label(),
                ]));
        }
        $form_state->setRedirectUrl($transport_config->toUrl('collection'));
    }

}
