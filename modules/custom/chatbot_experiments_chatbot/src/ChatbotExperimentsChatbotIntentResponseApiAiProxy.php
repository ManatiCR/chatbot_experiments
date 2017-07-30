<?php

/**
 * @file
 * Contains ChatbotExperimentsChatbotIntentResponseApiAiProxy class.
 */

namespace Drupal\chatbot_experiments_chatbot;

use Drupal\chatbot_api_apiai\IntentResponseApiAiProxy;

/**
 * Proxy wrapping Api.ai Response in a ChatbotRequestInterface.
 *
 * @package Drupal\chatbot_api_apiai
 */
class ChatbotExperimentsChatbotIntentResponseApiAiProxy extends IntentResponseApiAiProxy {

  use \Drupal\chatbot_api_apiai\ApiAiContextTrait;

  /**
   * {@inheritdoc}
   */
  public function setData($data) {
    return $this->original->add('data', $data);
  }

}
