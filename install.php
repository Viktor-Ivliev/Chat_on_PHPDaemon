<?php
chdir(__DIR__);
system("curl -sS https://getcomposer.org/installer | php");
system('php composer.phar -o install');
