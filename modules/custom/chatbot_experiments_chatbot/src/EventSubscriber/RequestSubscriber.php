<?php

namespace Drupal\chatbot_experiments_chatbot\EventSubscriber;

use Drupal\api_ai_webhook\ApiAiEvent;
use Drupal\chatbot_api_apiai\IntentRequestApiAiProxy;
use Drupal\chatbot_experiments_chatbot\ChatbotExperimentsChatbotIntentResponseApiAiProxy;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * An event subscriber for ApiAi request events.
 */
class RequestSubscriber implements EventSubscriberInterface {

  /**
   * Gets the event.
   */
  public static function getSubscribedEvents() {
    $events[ApiAiEvent::NAME][] = array('onRequest', 0);
    return $events;
  }

  /**
   * Called upon a request event.
   *
   * @param \Drupal\api_ai_webhook\ApiAiEvent $event
   *   The event object.
   */
  public function onRequest(ApiAiEvent $event) {
    /** @var \Drupal\chatbot_api_apiai\IntentRequestApiAiProxy $request */
    $request = new IntentRequestApiAiProxy($event->getRequest());
    /** @var \Drupal\chatbot_experiments_chatbot\ChatbotExperimentsChatbotIntentResponseApiAiProxy $request */
    $response = new ChatbotExperimentsChatbotIntentResponseApiAiProxy($event->getResponse());

    /** @var \Drupal\chatbot_api\Plugin\IntentPluginManager $manager */
    $manager = \Drupal::service('plugin.manager.chatbot_intent_plugin');
    // @TODO: Remove procedural code.
    if ($manager->hasDefinition($request->getIntentName())) {

      $configuration = [
        'request' => $request,
        'response' => $response,
      ];
      $plugin = $manager->createInstance($request->getIntentName(), $configuration);
      $plugin->process();
    }
  }

}
