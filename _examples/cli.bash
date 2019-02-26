// redis-cli
info
KEYS "cross_*" | xargs redis-cli DEL //delete keys
KEYS "cross_*" | wc -l // count keysre
SCAN 0 COUNT 100 MATCH * // get 100 keys



// centos firewall, открываем порты
// https://www.digitalocean.com/community/tutorials/how-to-set-up-a-firewall-using-firewalld-on-centos-7
firewall-cmd --state
firewall-cmd --get-active-zones
firewall-cmd --zone=public --list-all
firewall-cmd --zone=public --add-port=3306/tcp --permanent
firewall-cmd --reload


// network
netstat -anp | grep 3306 //find listening port
netstat -atn
nmap -sT -O localhost
lsof -i | grep 22


// filesystem
find . -delete //удалить все в папке
find  path -type d -ctime +2 -exec echo {} \; //показывает фаилы старше 2 дней
find  /var/* -type f  -cmin +60 -exec rm -rf {} \; # delete files
find  /var/* -type d  -cmin +60 -exec rmdir {} + 2>/dev/null; # delete directories
du -hs * | sort -h   // по папкам
df -h   // по дискам




// Создаём бекап и сразу его архивируем
mysqldump -u USER -pPASSWORD DATABASE | gzip > /path/to/outputfile.sql.gz
// Создание бекапа с указанием его даты
mysqldump -u USER -pPASSWORD DATABASE | gzip > `date +/path/to/outputfile.sql.%Y%m%d.%H%M%S.gz`
mysqldump -uroot -p -v --databases db1 db2 db3 | gzip > db.sql.gz
// Заливаем бекап в базу данных
mysql -u USER -pPASSWORD DATABASE < /path/to/dump.sql
// Заливаем архив бекапа в базу
gunzip < /path/to/outputfile.sql.gz | mysql -u USER -pPASSWORD DATABASE
// или так
zcat /path/to/outputfile.sql.gz | mysql -u USER -pPASSWORD DATABASE

mysqldump -uroot -p -v --databases db1 db2 db3 | gzip > db.sql.gz
pv db.sql.gz | gunzip | mysql -uroot -p


// links
https://rtfm.co.ua/ispolzovanie-git-cherez-http-proxy/



yum install
apt-get install mc
apt-get autoremove mc

// команды
cat /proc/version -проверить версию линукса

yum update

// Install MySQL Server on CentOS
https://support.rackspace.com/how-to/installing-mysql-server-on-centos/

ps aux | grep mysql

/ets/pam.d/sshd
auth       required     pam_tally2.so  file=/var/log/tallylog deny=3 even_deny_root unlock_time=120
account    required     pam_tally2.so


//show ip
ip addr show
/sbin/ifconfig | grep 'inet addr:'
hostname -I

//ssh
ssh root@192.168.0.150 22

//работа с жестким диском
fdisk -l



// git
// основные команды

.gitconfig лежит в home

git add filename.txt
git commit -m "Commit message"

git push origin master // origin - куда, master - ветка
git push

git pull

// ветки
git checkout -b branch-name
git checkout origin/adaptive -b adaptive


// лог
git log --oneline --decorate --all
git log --graph --all -n 20 --pretty=format:"%C(yellow)%h %C(cyan)%C(bold)%d% %C(cyan)(%cr) %C(green)%ce%Creset %s"

git branch -r // ветки на УР

//remove from git

git rm file1.txt
git commit -m "remove file1.txt"

git rm --cached file1.txt



git push // отправка
git pull // получение



git config --global http.proxy http://proxyuser:proxypass@proxyaddress:8080


// команды

cat /proc/version -проверить версию линукса

yum update

// Install MySQL Server on CentOS
https://support.rackspace.com/how-to/installing-mysql-server-on-centos/


ps aux | grep mysql


# /etc/crontab
* * * * * php /var/www/php-deamon/app.php &>/dev/null #every minute


----------------

#commands
service cron reload

#logs
/var/log/syslog
grep CRON /var/log/syslog








// alias
alias du1='du -sh * | sort -h'
alias gitlog='git log --graph --all -n 20 --pretty=format:"%C(yellow)%h %C(cyan)%C(bold)%d% %C(cyan)(%cr) %C(green)%ce%Creset %s"'