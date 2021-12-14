<?php

namespace Drupal\masterlist;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityTypeManager;

class MasterlistTermTree {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * MasterlistTermTree constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManager $entityTypeManager
   * @param \Drupal\Core\Database\Connection $connection
   */
  public function __construct(EntityTypeManager $entityTypeManager, Connection $connection) {
    $this->entityTypeManager = $entityTypeManager;
    $this->connection = $connection; 
  }

  /**
   * Loads the tree of a vocabulary.
   *
   * @param string $vocabulary
   *  Machine name
   *
   * @return array
   */
  public function load($vocabulary) {
    $terms = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vocabulary, 0, 1);
    
    $tree = [];
    foreach ($terms as $term) {
      $this->buildTree($tree, $term, $vocabulary);
    }

    return $tree;
  }

  /**
   * Populates a tree array given a taxonomy term object.
   *
   * @param $tree
   * @param $term
   * @param $vocabulary
   */
  protected function buildTree(&$tree, $term, $vocabulary) {
    
    if ($term->depth != 0) {
      return;
    }

    $tid = $term->tid;
    $tree[$tid] = $term;
    $tree[$tid]->children = [];
    $term_children = [];

    $children = $this->entityTypeManager->getStorage('taxonomy_term')->loadTree($vocabulary, $tid, 1);

    if (!$children) {
      $query = $this->connection->query("SELECT field_needs_target_id FROM paragraph__field_needs JOIN taxonomy_term__field_discussion_by_disabilities ON paragraph__field_needs.entity_id = taxonomy_term__field_discussion_by_disabilities.field_discussion_by_disabilities_target_id WHERE taxonomy_term__field_discussion_by_disabilities.entity_id = :entity_id", [':entity_id' => $tid, ]);
      $results = $query->fetchAll();

      $needs = [];

      foreach ($results as $result) {
        $need_tid = $result->field_needs_target_id;
        $need = $this->entityTypeManager->getStorage('taxonomy_term')->load($need_tid)->getName();
        array_push($needs, $this->transformNeeds($need));
      }    

      $tree[$tid]->needs = $needs;

      return;
    }

    foreach ($children as $child) {
      $this->buildTree($tree[$tid]->children, $child, $vocabulary);
      $term_children[$child->tid] = $child;
    }

    $tree[$tid]->children = $term_children;
  }

  private function transformNeeds($need) {
    switch($need) {
      case 'Blindness':
        return 'B';
      case 'Low Vision':
        return 'LV';
      case 'Cognitive, Language, Learning Disabilities & Low Literacy':
        return 'CLL';
      case 'Physical Disabilities':
        return 'PHY';
      case 'Deaf and Hard of Hearing':
        return 'D/HOH';
      default:
        return 'Other/All';
    }
  }
}