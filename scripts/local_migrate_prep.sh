#!/usr/bin/env bash
vagrant ssh -c "mysql -uroot -proot -e 'CREATE DATABASE sicultura; GRANT ALL ON sicultura.* TO drupal@localhost IDENTIFIED BY \"drupal\"; FLUSH PRIVILEGES;'"
