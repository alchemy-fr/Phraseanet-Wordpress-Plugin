git clone git@github.com:alchemy-fr/Phraseanet-Wordpress-Plugin.git wp-phraseanet
if [ $? -gt 0 ]; then
    echo "Error while fetching data from github"
    exit 1
fi

curl -s https://getcomposer.org/installer | php
if [ $? -gt 0 ]; then
    echo "Error while retrieving composer"
    exit 1
fi

mv composer.phar wp-phraseanet
if [ $? -gt 0 ]; then
    echo "Error while moving composer.phar to Worpress plugin directory"
    exit 1
fi

sh -c "cd wp-phraseanet && php composer.phar install && rm -Rf .git* && rm composer.*"
if [ $? -gt 0 ]; then
    echo "Error while initializing dependencies"
    exit 1
fi

zip -r -m wp-phraseanet.zip wp-phraseanet
if [ $? -gt 0 ]; then
    echo "Error while zipping plugin"
    exit 1
fi
