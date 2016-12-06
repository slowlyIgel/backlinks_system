#!/bin/bash
# Program:
# backup sql each day
#PATH=/bin:/sbin:/usr/bin:/usr/sbin:/usr/local/bin:/usr/local/sbin:~/bin
#export PATH
#echo -e "Hello World! \a \n"
backup_dir="/var/www/backup" #先這樣
time="$(date +"%d-%m-%Y")"
sqlname="$backup_dir/backlink_$time.sql"
test ! -w $backup_dir && echo "Error: $backup_dir is un-writeable." && exit 0
mysqldump -uroot -pkpnseo1234567 backlinks_system > $sqlname
exit 0
