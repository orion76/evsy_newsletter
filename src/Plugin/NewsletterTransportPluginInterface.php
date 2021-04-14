<?php

namespace Drupal\evsy_newsletter\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines an interface for Transport plugin plugins.
 */
interface NewsletterTransportPluginInterface extends PluginInspectionInterface {


    public function send($text);

    public function getConfigForm($config, $form, FormStateInterface $form_state, $ajax = NULL);

    public function getNewsletterConfigForm($config, $form, FormStateInterface $form_state, $ajax = NULL);

    public function getSettings($field_name);

    public function getConfigDefault();

    public function getNewsletterConfigDefault();

    public function getConfig();
}
