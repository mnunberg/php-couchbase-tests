#!/bin/sh
set -x
if [ -z $EXTDIR ]; then
	echo "Must have extension directory as EXTDIR in environment"
	exit 1
fi

exec php -d "extension_dir=$EXTDIR/modules/" \
	-d "extension=couchbase.so" \
	-d "open_basedir=" \
	$(which phpunit) $@
