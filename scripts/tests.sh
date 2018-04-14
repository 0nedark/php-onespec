#!/bin/bash

input=$1
arr=(${input//:/ })
URI=$2
CLASS=$3
LINE=$4

if [ ${arr[1]} = "unit" ]; then
	if [ "" != "${URI}" ]; then
		vendor/bin/phpspec run spec/${URI}
	elif [ "" != "${CLASS}" ] && [ "" != "${LINE}" ]; then
		vendor/bin/phpspec run spec/${CLASS}Spec.php:${LINE}
	elif [ "" != "${CLASS}" ]; then
		vendor/bin/phpspec run spec/${CLASS}Spec.php
	else
		vendor/bin/phpspec run
	fi
elif [ ${arr[1]} = "create" ]; then
	if [ ${arr[2]} = "unit" ]; then
		vendor/bin/phpspec desc OneSpec/${CLASS}
	fi
elif [ ${arr[1]} = "destroy" ]; then
	if [ ${arr[2]} = "unit" ]; then
		rm code/spec/${CLASS}Spec.php
		rm code/src/${CLASS}.php
	fi
fi