# Deployment

```bash
git clone ....
git config core.fileMode false

mkcert sasha-khandus.local www.sasha-khandus.local sasha-khandus-add.local www.sasha-khandus-add.local
# Add this certificate to /misc/apps/docker_infrastructure/local_infrastructure/traefik_rules/rules.toml

cd var/
gunzip db.sql.gz --keep
MY57
# create user, database and import DB dump
# CREATE DATABASE 

docker exec -it ....
composer install
git checkout .htaccess dev/tools/grunt/configs/less.js
php bin/magento setup:upgrade

# add domain to the /etc/hosts file
```