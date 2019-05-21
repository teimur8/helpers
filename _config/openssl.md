# создаем и подписываем сертификат
# http://pechenek.net/linux/sozdayom-samopodpisannyiy-ssl-sertifikat-s-pomoshhyu-openssl/

#create certificate
sudo openssl req -x509  \
	-nodes  \
	-newkey rsa:2048  \
	-days 365  \
	-keyout  /etc/nginx/ssl/crm.site.kz/mykey.key  \
	-out /etc/nginx/ssl/crm.site.kz/cert.crt
	

Country Name (2 letter code) [AU]: 77 — страна/регион
State or Province Name (full name) [Some-State]:. — штат (у нас их нет, поэтому пропускаем)
Locality Name (eg, city) []:Moscow — город
Organization Name (eg, company) [Internet Widgits Pty Ltd]:Pechenek.NET — имя организации
Organizational Unit Name (eg, section) []:. — подразделение организации, пропускаем.
Common Name (e.g. server FQDN or YOUR name) []:Pechenek.net — Обязательное поле, это имя по которому к серверу будут обращаться.
Email Address []:admin@adm.ru — адрес электронной почты, тоже можно пропустить.



# nginx config
ssl                         on;
ssl_protocols TLSv1 TLSv1.1 TLSv1.2;
ssl_certificate         	/etc/nginx/ssl/crm.adkulan.kz/cert.crt;
ssl_certificate_key     	/etc/nginx/ssl/crm.adkulan.kz/mykey.key;
