<?php

namespace Drupal\evsy_newsletter\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the evsy_newsletter module.
 */
class NewsletterOverviewControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return [
      'name' => "evsy_newsletter NewsletterOverviewController's controller functionality",
      'description' => 'Test Unit for module evsy_newsletter and controller NewsletterOverviewController.',
      'group' => 'Other',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests evsy_newsletter functionality.
   */
  public function testNewsletterOverviewController() {
    // Check that the basic functions of module evsy_newsletter.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
