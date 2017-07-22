Chatbot Migration
=================

## How to setup the migration?

* Run the `local_migrate_prep.sh` (in the scripts folder in project root) to create new database
* Remove settings/settings.secret.php (if it already exists)
* Run `local_settings.sh` in the scripts folder in project root) to regenerate it with new credentials
* Run `composer install` to rebuild the site.
* Run `local_migrate_sicultura.sh` and pass the gzipped database file as argument

Now, you can have fun running existing migrations!
