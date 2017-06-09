#!/usr/bin/env bash
DRUSH="./vendor/bin/drush"
SITE_ALIAS="@chatbot_experiments.chatbot-experiments.dev"

if [[ -f $1 ]]; then
  gunzip -c $1 | $DRUSH $SITE_ALIAS sqlc --database=sicultura
else
  echo "You must specify the path to the database contents as an argument."
  exit 1
fi
