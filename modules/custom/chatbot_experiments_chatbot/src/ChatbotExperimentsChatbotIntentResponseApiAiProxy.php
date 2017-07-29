<?php

namespace Drupal\chatbot_experiments_chatbot;

use ApiAi\Model\Context;
use Drupal\api_ai_webhook\ApiAi\Model\Webhook\Response;
use Drupal\chatbot_api\IntentResponseApiAiProxy;

/**
 * Proxy wrapping Api.ai Response in a ChatbotRequestInterface.
 *
 * @package Drupal\chatbot_api_apiai
 */
class ChatbotExperimentsChatbotIntentResponseApiAiProxy extends IntentResponseApiAiProxy {

  use ApiAiContextTrait;

  /**
   * {@inheritdoc}
   */
  public function setData($data) {
    return $this->original->add('data', $data);
  }

}
