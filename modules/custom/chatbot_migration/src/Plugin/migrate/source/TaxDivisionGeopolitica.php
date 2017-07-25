<?php

/**
 * @file
 * Has \Drupal\chatbot_migration\Plugin\migrate\source\TaxDivisionGeopolitica.
 */

namespace Drupal\chatbot_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;

/**
 * Taxonomy source from database.
 *
 * @MigrateSource(
 *   id = "tax_division_geopolitica"
 * )
 */
class TaxDivisionGeopolitica extends SqlBase {

  /**
   * {@inheritdoc}
   */
  public function query() {
    $query = $this->select('taxonomy_term_data', 't')
      ->fields('t', [
        'tid',
        'name',
        'description',
      ])
      ->condition('vid', 6);
    $query->join('taxonomy_term_hierarchy', 'th', 't.tid = th.tid');
    $query->addField('th', 'parent');
    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'tid' => $this->t('Term ID'),
      'name' => $this->t('Term name'),
      'description' => $this->t('Term description'),
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'tid' => [
        'type' => 'integer',
        'alias' => 't',
      ],
    ];
  }

}
