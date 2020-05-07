### bash giude
```sh
#!/bin/bash or #!/bin/sh # start line of file

VAR=10 #variables
VAR2=/file/path
VAR3="String"
VAR4=$(($VAR + 10)) #arithmetic

echo $VAR $VAR2 $VAR3
echo ${VAR}abc

```



### same
```sh
rename -v 's/#1571288401.txt/.csv/' *.txt
# вытаскиваем русские слова
grep -hr "[А-Яа-я]" /media/user1/linux/gdrive/php/ezyparts/resources | sed 's/[^А-Яа-я ]//gi'
grep -r "8988" projects/*/.env
```

### docker
[commands](https://docs.docker.com/engine/reference/commandline/docker/)
```sh
# разворачиваем бд 
cat /path/backup.sql | docker-compose exec -T db /usr/bin/mysql -u 'USER_FROM_ENV' --password=PASS_FROM_ENV DATABASE_NAME_FROM_ENV

dc restart sphinx

dc exec sphinx bash # войти в контйнер

```
### mysqldump backup table
```bash
#!/bin/bash

# user pass database "" "table1" только table1
# user pass database "table1 table2" все кроме table1 table2

USER=$1
PASSWORD=$2
DATABASE=$3
EXCLUDE=$4
ONLY=$5


tables=$(eval "mysql -u$USER -p$PASSWORD -BN -e \"use $DATABASE; show tables\"")
for word in $tables
do

    path="/root/backup/files/${DATABASE}-${word}-$(date +"%m_%d_%Y_%H_%M")"
    command="mysqldump -u$USER -p$PASSWORD --single-transaction $DATABASE $word | gzip > ${path}.sql.gz"

    # исключаем
    if [[ $EXCLUDE == *"$word"* ]]; then
        continue
    fi

    # включаем только их
    if [ $ONLY ];then
        if [[ $ONLY == *"$word"* ]]; then
            echo $word
            eval $command
        fi
         continue;
    fi

    echo $word
    eval $command

done
```


# davfs2 yandex.disc

[link](http://it-inside.org/2016/04/yandex-disk-and-auto-mount-with-davfs/)

```bash
sudo mkdir /mnt/yandex.disk
sudo apt install davfs2
sudo mount -t davfs -o gid=1000,uid=1000 https://webdav.yandex.ru /mnt/yandex.disk/

# config
sudo nano /etc/davfs2/secrets
/mnt/yandex.disk логин пасс. - В пароле не должно быть #

# automount
sudo nano /etc/rc.local
sleep 60 && sudo mount -t davfs -o gid=1000,uid=1000 https://webdav.yandex.ru /mnt/yandex.disk/
```
