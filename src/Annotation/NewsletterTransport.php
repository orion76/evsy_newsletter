<?php

namespace Drupal\evsy_newsletter\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Transport plugin item annotation object.
 *
 * @see \Drupal\evsy_newsletter\Plugin\NewsletterTransportPluginManager
 * @see plugin_api
 *
 * @Annotation
 */
class NewsletterTransport extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
