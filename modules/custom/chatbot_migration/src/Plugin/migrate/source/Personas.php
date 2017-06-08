<?php

/**
 * @file
 * Contains \Drupal\chatbot_migration\Plugin\migrate\source\Personas.
 */

namespace Drupal\chatbot_migration\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SqlBase;
use Drupal\migrate\Row;
use Drupal\migrate\Plugin\MigrationInterface;

/**
 * D8 nodes from D7 database.
 *
 * @MigrateSource(
 *   id = "personas"
 * )
 */
class Personas extends SqlBase {

  /**
   * {@inheritdoc}
   */
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
    $query->leftJoin('field_data_field_direccion_detallada', 'fdfdd', 'fdfdd.entity_id = n.nid AND fdfdd.revision_id = n.vid');
    $query->leftJoin('field_data_field_telefono_de_oficina', 'fdftdo', 'fdftdo.entity_id = n.nid AND fdftdo.revision_id = n.vid');
    $query->leftJoin('field_data_field_telefono_habitacion', 'fdfth', 'fdfth.entity_id = n.nid AND fdfth.revision_id = n.vid');
    $query->leftJoin('field_data_field_telefono_movil', 'fdftm', 'fdftm.entity_id = n.nid AND fdftm.revision_id = n.vid');
    $query->leftJoin('field_data_field_fax', 'fdff', 'fdff.entity_id = n.nid AND fdff.revision_id = n.vid');
    $query->leftJoin('field_data_field_sitio_web', 'fdfsw', 'fdfsw.entity_id = n.nid AND fdfsw.revision_id = n.vid');
    $query->leftJoin('field_data_field_correo_electronico', 'fdfce', 'fdfce.entity_id = n.nid AND fdfce.revision_id = n.vid');
    $query->leftJoin('field_data_field_correo_electronico_alt', 'fdfcea', 'fdfcea.entity_id = n.nid AND fdfcea.revision_id = n.vid');
    $query->leftJoin('field_data_field_apartado_postal', 'fdfap', 'fdfap.entity_id = n.nid AND fdfap.revision_id = n.vid');
    $query->leftJoin('field_data_field_facebook', 'fdffb', 'fdffb.entity_id = n.nid AND fdffb.revision_id = n.vid');
    $query->leftJoin('field_data_field_twitter', 'fdftw', 'fdftw.entity_id = n.nid AND fdftw.revision_id = n.vid');
    $query->leftJoin('field_data_field_google_', 'fdfg', 'fdfg.entity_id = n.nid AND fdfg.revision_id = n.vid');
    $query->leftJoin('field_data_field_linkedin', 'fdfli', 'fdfli.entity_id = n.nid AND fdfli.revision_id = n.vid');
    $query->leftJoin('field_data_field_canal_youtube', 'fdfcy', 'fdfcy.entity_id = n.nid AND fdfcy.revision_id = n.vid');
    $query->leftJoin('field_data_field_canal_vimeo', 'fdfcv', 'fdfcv.entity_id = n.nid AND fdfcv.revision_id = n.vid');
    // For fields referencing taxonomies.
    $query->leftJoin('field_data_field_autoria', 'fdfa', 'fdfa.entity_id = n.nid AND fdfa.revision_id = n.vid');

    /* Add fields for each table.*/
    // body.
    $query->addField('fdb', 'body_value');
    // field_direccion_detallada.
    $query->addField('fdfdd', 'field_direccion_detallada_value');
    // field_telefono_de_oficina.
    $query->addField('fdftdo', 'field_telefono_de_oficina_value');
    // field_telefono_habitacion.
    $query->addField('fdfth', 'field_telefono_habitacion_value');
    // field_telefono_movil.
    $query->addField('fdftm', 'field_telefono_movil_value');
    // field_fax.
    $query->addField('fdff', 'field_fax_value');
    // field_sitio_web.
    $query->addField('fdfsw', 'field_sitio_web_url');
    // field_correo_electronico.
    $query->addField('fdfce', 'field_correo_electronico_email');
    // field_correo_electronico_alt.
    $query->addField('fdfcea', 'field_correo_electronico_alt_email');
    // field_apartado_postal.
    $query->addField('fdfap', 'field_apartado_postal_value');
    // field_facebook.
    $query->addField('fdffb', 'field_facebook_url');
    // field_twitter.
    $query->addField('fdftw', 'field_twitter_url');
    // field_google_.
    $query->addField('fdfg', 'field_google__url');
    // field_linkedin.
    $query->addField('fdfli', 'field_linkedin_url');
    // field_canal_youtube.
    $query->addField('fdfcy', 'field_canal_youtube_url');
    // field_canal_vimeo.
    $query->addField('fdfcv', 'field_canal_vimeo_url');
    // field_autoria.
    $query->addField('fdfa', 'field_autoria_tid');

    $query->condition('n.type', 'fichas_personas');
    $query->condition('n.status', 1);

    return $query;
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'nid' => $this->t('Node id.'),
      'title' => $this->tt('Node title.'),
      'body_value' => $this->t('Node body.'),
      'field_direccion_detallada_value' => $this->t('Address detailed info.'),
      'field_telefono_de_oficina_value' => $this->t('Office phone.'),
      'field_telefono_habitacion_value' => $this->t('Home phone.'),
      'field_telefono_movil_value' => $this->t('Mobile phone.'),
      'field_fax_value' => $this->t('Fax number.'),
      'field_sitio_web_url' => $this->t('Web site url.'),
      'field_correo_electronico_email' => $this->t('Email.'),
      'field_correo_electronico_alt_email' => $this->t('Alternative email.'),
      'field_apartado_postal_value' => $this->t('Postal code.'),
      'field_facebook_url' => $this->t('Facebook URL.'),
      'field_twitter_url' => $this->t('Twitter URL.'),
      'field_google__url' => $this->t('Google URL.'),
      'field_linkedin_url' => $this->t('LinkedIn URL.'),
      'field_canal_youtube_url' => $this->t('Youtube Channel.'),
      'field_canal_vimeo_url' => $this->t('Vimeo Channel.'),
      'field_division_politica' => $this->t('Term ID for field_provincia_canton_distrito.'),
      'field_autoria_tid' => $this->t('Term ID for field_autoria.'),
      'latitude' => $this->t('Location latitude.'),
      'longitude' => $this->t('Location longitude.'),
      'field_videos' => $this->t('Values for field_videos'),
      'field_categoria' => $this->t('Values for field_categoria'),
    ];

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'nid' => [
        'type' => 'integer',
        'alias' => 'n',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    if (parent::prepareRow($row) === FALSE) {
      return FALSE;
    }

    $nid = $row->getSourceProperty('nid');
    $vid = $row->getSourceProperty('vid');
    $videos = [];
    $categories = [];
    $division_politica = [];
    $location = [];

    if (!empty($nid) && !empty($vid)) {
      // Process field_videos.
      $videos = $this->select('field_data_field_video', 'fdfv')
        ->fields('fdfv', ['field_video_video_url'])
        ->condition('fdfv.entity_id', $nid)
        ->condition('fdfv.revision_id', $vid)
        ->execute()
        ->fetchCol();
      // Process field_categoria (tax_personas).
      $categories = $this->select('field_data_field_categoria', 'fdfc')
        ->fields('fdfc', ['field_categoria_tid'])
        ->condition('fdfc.entity_id', $nid)
        ->condition('fdfc.revision_id', $vid)
        ->execute()
        ->fetchCol();
      // Process field_data_field_provincia_canton_distrito.
      $division_politica = $this->select('field_data_field_provincia_canton_distrito', 'fdfpcd')
        ->fields('fdfpcd', ['field_provincia_canton_distrito_tid'])
        ->condition('fdfpcd.entity_id', $nid)
        ->condition('fdfpcd.revision_id', $vid)
        ->execute()
        ->fetchCol();
      // Process field_data_field_location.
      $location = $this->select('location', 'l')
        ->fields('l', ['latitude', 'longitude']);
      $location->leftJoin('field_data_field_location', 'fdfl', 'l.lid = fdfl.field_location_lid');
      $location = $location->condition('fdfl.entity_id', $nid)
        ->condition('fdfl.revision_id', $vid)
        ->execute()
        ->fetchAllKeyed(0, 1);
    }

    // Set property for field_videos.
    $row->setSourceProperty('field_videos', $videos);
    // Set property for field_categoria.
    $row->setSourceProperty('field_categoria', $categories);
    // Set property for field_provincia_canton_distrito.
    $row->setSourceProperty('field_division_politica', $division_politica);
    // Set properties for field_location.
    foreach ($location as $latitude => $longitude) {
      // Latitude.
      $row->setSourceProperty('latitude', $latitude);
      // Longitude.
      $row->setSourceProperty('longitude', $longitude);
    }
  }
}
