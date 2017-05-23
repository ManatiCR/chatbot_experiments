#!/usr/bin/env bash
DRUSH="./vendor/bin/drush"
SITE_ALIAS="@chatbot_experiments.chatbot-experiments.dev"
SITE_UUID="a267a8ed-e3ba-4372-97d8-eccedf0bd4cd"
$DRUSH $SITE_ALIAS cc drush
echo "Installing..."
$DRUSH $SITE_ALIAS si chatbot_experiments --account-pass=admin -y
echo "Set site uuid..."
$DRUSH $SITE_ALIAS config-set "system.site" uuid "$SITE_UUID" -y
echo "Importing config..."
if [ -f ./config/sync/core.extension.yml ] ; then $DRUSH $SITE_ALIAS cim -y ; fi
echo "Cleaning cache..."
$DRUSH $SITE_ALIAS cr
