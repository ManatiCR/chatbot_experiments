#!/usr/bin/env bash
if [ $# -ne 0 ]
then
  vagrant ssh -c "cd /var/www/chatbot_experiments; ./vendor/bin/drupal $1"
else
  echo "You need to pass the drupal console commands"
fi
