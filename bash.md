```sh
rename -v 's/#1571288401.txt/.csv/' *.txt
grep -hr "[А-Яа-я]" /media/user1/linux/gdrive/php/ezyparts/resources | sed 's/[^А-Яа-я ]//gi'
cat /path/backup.sql | docker-compose exec -T db /usr/bin/mysql -u 'USER_FROM_ENV' --password=PASS_FROM_ENV DATABASE_NAME_FROM_ENV
```