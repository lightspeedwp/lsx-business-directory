#!/bin/bash

echo "* NANDOTESS LOCALHOST DEPLOY *";
cd ~/Sites-LightSpeed/@git/lsx-business-directory;
gulp compile-css;
gulp compile-js;

echo "* TSA-V2.MAMP:8888 *";
rm -Rf ~/Sites-LightSpeed/@mamp/tsa-v2.mamp/wp-content/plugins/lsx-business-directory;
rsync -a \
	--exclude='.git' \
	--exclude='.idea' \
	--exclude='.sass-cache' \
	--exclude='node_modules' \
	--exclude='.DS_Store' \
	--exclude='.gitignore' \
	--exclude='.gitmodules' \
	--exclude='gulpfile.js' \
	--exclude='package.json' \
	~/Sites-LightSpeed/@git/lsx-business-directory ~/Sites-LightSpeed/@mamp/tsa-v2.mamp/wp-content/plugins;
