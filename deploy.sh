#!/usr/bin/env bash

if [[ $1 == -g || $1 == --gulp ]] ; then
    gulp --production 
else
    echo No gulp
fi

rsync -avP dist lib screenshot.png templates *.php *.css poman:public_html/wp/wp-content/themes/poman
