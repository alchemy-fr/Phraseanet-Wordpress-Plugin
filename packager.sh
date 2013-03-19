git clone git@github.com:alchemy-fr/Phraseanet-Wordpress-Plugin.git
curl -s https://getcomposer.org/installer | php
mv composer.phar Phraseanet-Wordpress-Plugin
sh -c "cd Phraseanet-Wordpress-Plugin && php composer.phar install && rm -Rf .git*"
zip -r -m Phraseanet-Wordpress-Plugin.zip Phraseanet-Wordpress-Plugin
