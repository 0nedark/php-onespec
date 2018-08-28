#!/usr/bin/env sh

php --define phar.readonly=0 /app/bin/build.php
chmod +x /app/bin/onespec.phar
mv /app/bin/onespec.phar /app/bin/onespec
