<?php

namespace Drupal\chatbot_experiments_chatbot;


use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Modifies the chatbot api apiai event subscriber service.
 */
class ChatbotExperimentsChatbotServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    // Overrides chatbot_api_apiai.request_subscriber class.
    $definition = $container->getDefinition('chatbot_api_apiai.request_subscriber');
    $definition->setClass('Drupal\chatbot_experiments_chatbot\EventSubscriber\RequestSubscriber');
  }
}
