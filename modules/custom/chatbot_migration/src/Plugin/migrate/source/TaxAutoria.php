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
      ->condition('vid', 10);
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
