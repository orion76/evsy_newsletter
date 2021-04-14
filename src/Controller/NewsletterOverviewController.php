<?php

namespace Drupal\evsy_newsletter\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class NewsletterOverviewController.
 */
class NewsletterOverviewController extends ControllerBase {


  public function overview() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: ovewrview')
    ];
  }

}
