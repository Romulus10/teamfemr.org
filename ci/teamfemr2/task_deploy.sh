#!/bin/sh

set -ex

aws ssm send-command --instance-ids "i-0a1b188680e008bc3" --document-name "AWS-RunShellScript" --comment "TeamFemr2 deploy" --parameters commands=['sudo -u staging /home/staging/update.sh'] --output json

#cp -a git-teamfemr2-app/. teamfemr2-build-folder/

#ls teamfemr2-build-folder

#cd teamfemr2-build-folder && composer install
