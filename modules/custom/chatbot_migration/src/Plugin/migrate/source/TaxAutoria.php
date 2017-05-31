<?php

/**
 * @file
 * Contains \Drupal\chatbot_migration\Plugin\migrate\source\TaxAutoria.
 */

namespace Drupal\chatbot_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Taxonomy source from database.
 *
 * @MigrateSource(
 *   id = "tax_autoria"
 * )
 */
class TaxAutoria extends SqlBase {
  public function query() {
    $query = $this->select('taxonomy_term_data', 't')
      ->fields('t', array(
        'tid',
        'name',
        'description',
      ))
      ->condition('vid', 10);
    return $query;
  }

  public function fields() {
    $fields = [
      'tid' => $this->t('Term ID'),
      'name' => $this->t('Term name'),
      'description' => $this->t('Term description'),
    ];
  }

  public function getIds() {
    return [
      'tid' => [
        'type' => 'integer',
        'alias' => 't',
      ],
    ];
  }
}
