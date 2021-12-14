<?php

namespace Drupal\masterlist\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\masterlist\MasterlistTermTree;

/**
 * Defines MasterlistController class
 */
class MasterlistController extends ControllerBase {

  /**
   * @var \Drupal\masterlist\MasterlistTermTree
   */
  private $masterlistTermTree;

  /**
   * @inheritdoc
   */
  public function __construct(MasterlistTermTree $masterlistTermTree) {
    $this->masterlistTermTree = $masterlistTermTree;
  }

  /**
   * @inheritdoc
   */
  public static function create(ContainerInterface $container) {
    $termsGenerator = $container->get('masterlist.masterlist_term_tree');

    return new static($termsGenerator);
  }

  /**
   * Display the markup.
   *
   * @return array
   *  Return markup array.
   */
   public function content() {
    $terms = $this->masterlistTermTree->load('techniques');

    return [
      '#theme' => 'masterlist',
      '#terms' => $terms,
    ];
   } 
}