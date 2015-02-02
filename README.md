Super simple redirection site (for making your own short links) powered by
Silex and Doctrine.  This does log hits, but doesn't include any web interfaces
such as analytics or a tool for adding links - you'll need to use another tool
for manipulating the database.

## Setup

``` bash
$ curl -sS https://getcomposer.org/installer | php
$ php composer.phar install
```

* Copy /config.php.dist to /config.php
* Enter DB credentials in config.php
* Run database.sql on selected database
* Start inserting links via SQL