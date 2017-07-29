<?php

/**
 * @file
 * Contains ChatbotExperimentsViewsIntent class.
 */

namespace Drupal\chatbot_experiments_chatbot\Plugin\Chatbot\Intent;

use Drupal\chatbot_api\Plugin\Chatbot\Intent\ViewsIntent;
use Drupal\Component\Render\HtmlEscapedText;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * ChatbotExperimentsViewsIntent.
 */
class ChatbotExperimentsViewsIntent extends ViewsIntent {

  use \Drupal\Core\StringTranslation\StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public function process() {
    // Initialise views handlers.
    $this->view->initHandlers();
    $this->view->initPager();

    // Process plugin filters and pager progress.
    $this->processFilters();
    $this->processIterationProgress();

    // Node ID.
    $output = $this->view->executeDisplay($this->displayID, []);
    $output = trim(preg_replace('/\s+/', ' ', strip_tags($this->renderer->render($output))));
    if (is_numeric($output)) {
      $node = Node::load($output);
      $title = new HtmlEscapedText($node->title->value);
      /** @var \Drupal\Core\Render\Renderer $renderer */
      $this->response->setIntentResponse($title);
      $this->response->setData($this->buildFbGenericTemplate($node));
    }
    $this->incrementIterationProgress();
  }

  /**
   * Build facebook generic template from node.
   *
   * @param \Drupal\node\Entity\Node $node
   *   Node used to build the generic template.
   *
   * @return array
   *   Generic template data as shown at
   *   https://discuss.api.ai/t/card-json-response-webhook/2258/2
   */
  protected function buildFbGenericTemplate(Node $node) {
    // @TODO: Url.
    $url = Url::fromRoute('entity.node.canonical', ['node' => $node->nid->value]);
    $image_url = NULL;
    if (!empty($node->field_imagen->entity->fid->value)) {
      $uri = $node->field_imagen->entity->uri->value;
      $stream_wrapper_manager = \Drupal::service('stream_wrapper_manager')->getViaUri($uri);
      // @TODO: Remove procedural code.
      $image_url = $stream_wrapper_manager->getExternalUrl();
    }
    $data = [
      'facebook' => [
        'attachment' => [
          'type' => 'template',
          'payload' => [
            'template_type' => 'generic',
            'elements' => [
              'title' => new HtmlEscapedText($node->title->value),
              'subtitle' => substr(strip_tags($node->body->value), 0, 80),
              'image_url' => $image_url,
              'default_action' => [
                'type' => 'web_url',
                'url' => $url,
              ],
              'buttons' => [
                [
                  'type' => 'web_url',
                  'url' => $url,
                  'title' => $this->t('View'),
                ],
              ],
            ],
            'sharable' => TRUE,
          ],
        ],
      ],
    ];

    return $data;
  }

}
