<?php

/**
 * @file
 * Contains \Drupal\chatbot_migration\Plugin\migrate\source\Evento.
 */

namespace Drupal\chatbot_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;
use Drupal\migrate\Plugin\MigrationInterface;

/**
 * D8 nodes from D7 database.
 *
 * @MigrateSource(
 *   id = "evento"
 * )
 */
class Evento extends SqlBase {
  public function query() {
    $query = $this->select('node', 'n')
      ->fields('n', [
        'nid',
        'vid',
        'title'
      ]);

    /* Join tables for getting fields.*/
    // For text, link and email fields.
    $query->leftJoin('field_data_body', 'fdb', 'fdb.entity_id = n.nid AND fdb.revision_id = n.vid');
    $query->leftJoin('field_data_field_es_destacado', 'fdfed', 'fdfed.entity_id = n.nid AND fdfed.revision_id = n.vid');
    $query->leftJoin('field_data_field_precio', 'fdfp', 'fdfp.entity_id = n.nid AND fdfp.revision_id = n.vid');
    $query->leftJoin('field_data_field_nombre_del_lugar', 'fdfndl', 'fdfndl.entity_id = n.nid AND fdfndl.revision_id = n.vid');
    $query->leftJoin('field_data_field_direccion_del_lugar', 'fdfddl', 'fdfddl.entity_id = n.nid AND fdfddl.revision_id = n.vid');
    $query->leftJoin('field_data_field_sitio_web', 'fdfsw', 'fdfsw.entity_id = n.nid AND fdfsw.revision_id = n.vid');
    $query->leftJoin('field_data_field_telefono_de_contacto', 'fdftdc', 'fdftdc.entity_id = n.nid AND fdftdc.revision_id = n.vid');
    $query->leftJoin('field_data_field_email_de_contacto', 'fdfedc', 'fdfedc.entity_id = n.nid AND fdfedc.revision_id = n.vid');
    $query->leftJoin('field_data_field_fecha_y_hora', 'fdffyh', 'fdffyh.entity_id = n.nid AND fdffyh.revision_id = n.vid');
    // For fields referencing taxonomies.
    $query->leftJoin('field_data_field_categoria_evento', 'fdfce', 'fdfce.entity_id = n.nid AND fdfce.revision_id = n.vid');

    /* Add fields for each table.*/
    // field_categoria_evento.
    $query->addField('fdfce', 'field_categoria_evento_tid');
    // field_es_destacado.
    $query->addField('fdfed', 'field_es_destacado_value');
    // body.
    $query->addField('fdb', 'body_value');
    // field_precio.
    $query->addField('fdfp', 'field_precio_value');
    // field_nombre_del_lugar.
    $query->addField('fdfndl', 'field_nombre_del_lugar_value');
    // field_direccion_del_lugar.
    $query->addField('fdfddl', 'field_direccion_del_lugar_value');
    // field_sitio_web.
    $query->addField('fdfsw', 'field_sitio_web_url');
    // field_telefono_de_contacto.
    $query->addField('fdftdc', 'field_telefono_de_contacto_value');
    // field_email_de_contacto.
    $query->addField('fdfedc', 'field_email_de_contacto_email');
    // field_fecha_y_hora.
    $query->addField('fdffyh', 'field_fecha_y_hora_value');
    $query->addField('fdffyh', 'field_fecha_y_hora_value2');

    $query->condition('n.type', 'evento');
    $query->condition('n.status', 1);

    return $query;
  }

  public function fields() {
    $fields = [
      'nid' => $this->t('Node id.'),
      'title' => $this->t('Node title.'),
      'field_categoria_evento_tid' => $this->t('Term ID for field_categoria_evento.'),
      'field_es_destacado_value' => $this->t('Es destacado.'),
      'body_value' => $this->t('Node body.'),
      'field_precio_value' => $this->t('Price.'),
      'field_nombre_del_lugar_value' => $this->t('Event place name.'),
      'field_direccion_del_lugar_value' => $this->t('Event place address.'),
      'field_sitio_web_url' => $this->t('Website'),
      'field_telefono_de_contacto_value' => $this->t('Contact phone.'),
      'field_email_de_contacto_email' => $this->t('Contact email.'),
      'field_fecha_y_hora_value' => $this->t('Date and time: from.'),
      'field_fecha_y_hora_value2' => $this->t('Date and time: to.'),
      'field_division_politica' => $this->t('Term ID for field_provincia_canton_distrito.'),
      'latitude' => $this->t('Field location latitude.'),
      'longitude' => $this->t('Field location longitude.'),
    ];

    return $fields;
  }

  public function getIds() {
    return [
      'nid' => [
        'type' => 'integer',
        'alias' => 'n',
      ],
    ];
  }

  public function prepareRow(Row $row) {
    if (parent::prepareRow($row) === FALSE) {
      return FALSE;
    }

    $nid = $row->getSourceProperty('nid');
    $vid = $row->getSourceProperty('vid');
    $division_politica = [];
    $location = [];

    if (!empty($nid) && !empty($vid)) {
      // Process field_data_field_provincia_canton_distrito.
      $division_politica = $this->select('field_data_field_provincia_canton_distrito', 'fdfpcd')
        ->fields('fdfpcd', ['field_provincia_canton_distrito_tid'])
        ->condition('fdfpcd.entity_id', $nid)
        ->condition('fdfpcd.revision_id', $vid)
        ->execute()
        ->fetchCol();
      // Process field_locations.
      $location = $this->select('location', 'l')
        ->fields('l', ['latitude', 'longitude']);
      $location->leftJoin('field_data_field_location', 'fdfl', 'l.lid = fdfl.field_location_lid');
      $location = $location->condition('fdfl.entity_id', $nid)
        ->condition('fdfl.revision_id', $vid)
        ->execute()
        ->fetchAllKeyed(0, 1);

      // If the url doesn't have the schema, add it.
      $sitio_web = $row->getSourceProperty('field_sitio_web_url');
      if (!empty($sitio_web)) {
        if (preg_match('/https:\/\//', $sitio_web) == 0 && preg_match('/http:\/\//', $sitio_web) == 0) {
          $sitio_web = 'https://' . $sitio_web;
          $row->setSourceProperty('field_sitio_web_url', $sitio_web);
        }
      }
    }

    // Set property for field_provincia_canton_distrito.
    $row->setSourceProperty('field_division_politica', $division_politica);
    // Set properties for field_location.
    foreach ($location as $latitude => $longitude) {
      // Latitude.
      $row->setSourceProperty('latitude', $latitude);
      // Longitude.
      $row->setSourceProperty('longitude', $longitude);
    }

    // Transform dates.
    $from = $row->getSourceProperty('field_fecha_y_hora_value');
    $row->setSourceProperty('field_fecha_y_hora_value', gmdate(DATETIME_DATETIME_STORAGE_FORMAT, strtotime($from)));
    $to = $row->getSourceProperty('field_fecha_y_hora_value2');
    $row->setSourceProperty('field_fecha_y_hora_value2', gmdate(DATETIME_DATETIME_STORAGE_FORMAT, strtotime($to)));
  }
}
