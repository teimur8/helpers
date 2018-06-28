https://gist.github.com/bradmontgomery/6487319

Setting up a SSL Cert from Comodo
=================================

Заказываем на goodhost.kz, создаем CRS для домена. Сохраняем их в

* ``example_com.key`` -- your Private key. You'll need this later to configure ngxinx.
* ``example_com.csr`` -- Your CSR file.

или можно через командную строку

::
    openssl req -new -newkey rsa:2048 -nodes -keyout example_com.key -out example_com.csr


Now, purchase the certificate [1]_, follow the steps on their site, and you should soon get an
email with your *PositiveSSL Certificate*. It contains a zip file with the following:

* Root CA Certificate - `AddTrustExternalCARoot.crt`
* Intermediate CA Certificate - `COMODORSAAddTrustCA.crt`
* Intermediate CA Certificate - `COMODORSADomainValidationSecureServerCA.crt`
* Your PositiveSSL Certificate - `www_example_com.crt` (or the subdomain you gave them)

Install the Commodo SSL cert
----------------------------

Combine everything for nginx [2]_:

1. Combine the above crt files into a bundle (the order matters, here)::

    cat www_example_com.crt COMODORSADomainValidationSecureServerCA.crt  COMODORSAAddTrustCA.crt AddTrustExternalCARoot.crt > ssl-bundle.crt

2. Store the bundle wherever nginx expects to find it::

    mkdir -p /etc/nginx/ssl/example_com/
    mv ssl-bundle.crt /etc/nginx/ssl/example_com/

3. Ensure your private key is somewhere nginx can read it, as well.::

    mv example_com.key /etc/nginx/ssl/example_com/

4. Make sure your nginx config points to the right cert file and to the private
   key you generated earlier::

    server {
        listen 443;

        ssl on;
        ssl_certificate /etc/nginx/ssl/example_com/ssl-bundle.crt;
        ssl_certificate_key /etc/nginx/ssl/example_com/example_com.key;

        # side note: only use TLS since SSLv2 and SSLv3 have had recent vulnerabilities
        ssl_protocols TLSv1 TLSv1.1 TLSv1.2;

        # ...

    }

6. Restart nginx.


.. [1] I purchased mine through Namecheap.com.
.. [2] Based on these instructions: http://goo.gl/4zJc8

