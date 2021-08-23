<?php

namespace Drupal\evsy_newsletter\Form;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\evsy_event\Plugin\AppEventInterface;
use Drupal\evsy_event\Plugin\AppEventManagerInterface;
use Drupal\evsy_newsletter\Entity\NewsletterConfigInterface;
use Drupal\evsy_newsletter\Entity\NewsletterTransportConfigInterface;
use Drupal\evsy_newsletter\Plugin\NewsletterTransportPluginInterface;
use Drupal\evsy_newsletter\Plugin\NewsletterTransportPluginManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use function array_map;
use function str_replace;
use function ucfirst;

/**
 * Class NewsletterConfigForm.
 */
class NewsletterConfigForm extends EntityForm {

    const CONFIG = 'config';

    const TRANSPORT_CONFIG = 'transport_config';

    /** @var NewsletterConfigInterface */
    protected $entity;

    /** @var AppEventManagerInterface */
    private $eventManager;

    /** @var NewsletterTransportPluginManagerInterface */
    private $transportManager;

    protected function getEventManager() {
        if (empty($this->eventManager)) {
            $this->eventManager = \Drupal::service('plugin.manager.app_event');
        }
        return $this->eventManager;
    }

    protected function getTransportManager() {
        if (empty($this->transportManager)) {
            $this->transportManager = \Drupal::service('plugin.manager.newsletter_transport_plugin');
        }
        return $this->transportManager;
    }

    protected function getEventOptions() {
        $options = [];
        foreach ($this->getEventManager()->getDefinitions() as $event) {
            $source = ucfirst($event['source']);

            if (!isset($options[$source])) {
                $options[$source] = [];
            }
            $options[$source][$event['id']] = $event['label'];
        }
        return $options;
    }

    protected function getTransportOptions() {
        return array_map(function ($item) {
            return $item['label'];
        }, $this->getTransportManager()->getDefinitions());
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state) {
        $form = parent::buildForm($form, $form_state);

        $entity = $this->entity;
        $entity->getEntityType();
        $form['label'] = [
            '#type' => 'textfield',
            '#title' => $this->t('Label'),
            '#maxlength' => 255,
            '#default_value' => $entity->label(),
            '#description' => $this->t("Label for the Newsletter config."),
            '#required' => TRUE,
        ];

        $form['id'] = [
            '#type' => 'machine_name',
            '#default_value' => $entity->id(),
            '#machine_name' => [
                'exists' => '\Drupal\evsy_newsletter\Entity\NewsletterConfig::load',
            ],
            '#disabled' => !$entity->isNew(),
        ];

      $form['active'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('Active'),
        '#default_value' => $entity->isActive(),
      ];
        
        
        $empty_option = $this->t('-- Select --');

        $event_id = $entity->get('event');

        $form['event'] = [
            '#type' => 'select',
            '#label' => $this->t('Event'),
            '#default_value' => $event_id,
            '#empty_option' => $empty_option,
            '#options' => $this->getEventOptions(),
            '#ajax' => $this->getAjax(self::CONFIG),
        ];

        $form[self::CONFIG] = [
            '#type' => 'container',
            '#attributes' => ['id' => $this->createFormConfigContainerId($entity, self::CONFIG)],
        ];

        if (!empty($event_id)) {
            /** @var $eventPlugin AppEventInterface */
            $eventPlugin = $this->getEventManager()->createInstance($event_id);

            $form[self::CONFIG]['#type'] = 'fieldset';
            $form[self::CONFIG] += [
                '#title' => $this->t('Config'),
                '#tree' => TRUE,
            ];
            $config = $entity->getConfig() + $eventPlugin->getConfigDefault();
            $form[self::CONFIG] += $eventPlugin->getConfigForm($config, $form, $form_state, $this->getAjax(self::CONFIG));

        }

        $transport_id = $entity->get('transport');

        $form['transport'] = [
            '#type' => 'select',
            '#label' => $this->t('Transport'),
            '#default_value' => $transport_id,
            '#empty_option' => $empty_option,
            '#options' => $this->getTransportOptions(),
            '#ajax' => $this->getAjax(self::TRANSPORT_CONFIG),
        ];

        $form[self::TRANSPORT_CONFIG] = [
            '#type' => 'container',
            '#attributes' => ['id' => $this->createFormConfigContainerId($entity, self::TRANSPORT_CONFIG)],
        ];

        if (!empty($transport_id)) {
            /** @var $transportPlugin NewsletterTransportPluginInterface */
            $transportPlugin = $this->getTransportManager()->createInstance($transport_id);

            $form[self::TRANSPORT_CONFIG]['#type'] = 'fieldset';
            $form[self::TRANSPORT_CONFIG] += [
                '#title' => $this->t('Transport Config'),
                '#tree' => TRUE,
            ];
            $config = $entity->getTransportConfig() + $transportPlugin->getNewsletterConfigDefault();
            $form[self::TRANSPORT_CONFIG] += $transportPlugin->getNewsletterConfigForm($config, $form, $form_state, $this->getAjax(self::TRANSPORT_CONFIG));

        }
        $form['template'] = [
            '#type' => 'textarea',
            '#title' => $this->t('Template'),
            '#format' => 'basic_html',
            '#default_value' => $entity->get('template'),
        ];

        $token_types = [];
        $config = $entity->getConfig();
        if (isset($config['entity_type'])) {
            $token_types[] = $config['entity_type'];
        }
        $form['token_tree'] = [
            '#theme' => 'token_tree_link',
            '#token_types' => $token_types,
            '#show_restricted' => TRUE,
            '#weight' => 90,
        ];

        return $form;
    }


    public function validateForm(array &$form, FormStateInterface $form_state) {
        parent::validateForm($form, $form_state);
        $trigger = $form_state->getTriggeringElement();
        if ($trigger['#name'] === 'event') {
            $form_state->setRebuild();
        }
        $n = 0;
    }

    public function ___submitForm(array &$form, FormStateInterface $form_state) {

        if ($form_state->getValue('question_type_submit') == 'Choose') {
            $form_state->setValue('question_type_select', $form_state->getUserInput()['question_type_select']);
            $form_state->setRebuild();
        }
    }

    protected function getAjax($type) {
        $ajax = [
            'wrapper' => $this->createFormConfigContainerId($this->entity, $type),
        ];
        switch ($type) {
            case self::CONFIG:
                $ajax['callback'] = '::ajaxConfigContainer';

                break;
            case self::TRANSPORT_CONFIG:
                $ajax['callback'] = '::ajaxConfigTransportContainer';
                break;
        }


        return $ajax;
    }

    protected function createFormConfigContainerId(ConfigEntityInterface $entity, $suffix) {
        return 'evsy-' . str_replace('_', '-', $entity->id() . '-form-' . $suffix);
    }

    static function ajaxConfigContainer($form, FormStateInterface $form_state) {
        return $form[self::CONFIG];
    }

    static function ajaxConfigTransportContainer($form, FormStateInterface $form_state) {
        return $form[self::TRANSPORT_CONFIG];
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $form_state) {
        $newsletter_config = $this->entity;

        $template = $newsletter_config->get('template');
        if (!empty($template) && isset($templaye['value'])) {
            $newsletter_config->set('template', $templaye['value']);
        }
        $status = $newsletter_config->save();

        switch ($status) {
            case SAVED_NEW:
                $this->messenger()->addMessage($this->t('Created the %label Newsletter config.', [
                    '%label' => $newsletter_config->label(),
                ]));
                break;

            default:
                $this->messenger()->addMessage($this->t('Saved the %label Newsletter config.', [
                    '%label' => $newsletter_config->label(),
                ]));
        }
        $form_state->setRedirectUrl($newsletter_config->toUrl('collection'));
    }

}
