#!/usr/bin/env sh

echo 'phar.readonly=0' > /usr/local/etc/php/php.ini
php /app/bin/build.php
mv /app/bin/onespec.phar /app/bin/onespec
chmod +x /app/bin/onespec
rm /usr/local/etc/php/php.ini