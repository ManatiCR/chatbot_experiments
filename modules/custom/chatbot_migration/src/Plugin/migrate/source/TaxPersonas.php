<?php

/**
 * @file
 * Contains \Drupal\chatbot_migration\Plugin\migrate\source\TaxPersonas.
 */

namespace Drupal\chatbot_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Tags source from database.
 *
 * @MigrateSource(
 *   id = "tax_personas"
 * )
 */
class TaxPersonas extends SqlBase {
  public function query() {
    $query = $this->select('taxonomy_term_data', 't')
      ->fields('t', [
        'tid',
        'name',
        'description',
      ])
      ->condition('vid', 9);
    $query->join('taxonomy_term_hierarchy', 'th', 't.tid = th.tid');
    $query->addField('th', 'parent');
    return $query;
  }

  public function fields() {
    $fields = [
      'tid' => $this->t('Term ID'),
      'name' => $this->t('Term name'),
      'description' => $this->t('Term description'),
    ];
    return $fields;
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
