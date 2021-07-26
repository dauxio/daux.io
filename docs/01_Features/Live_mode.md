Keep in mind, this mode can be used for production, but it is not recommended.

The whole directory must be scanned on each request. This might not make a big impact on small documentations but can be a bottleneck on bigger ones.

## Running Locally

There are several ways to run the docs locally. You can use something like <a href="http://www.mamp.info/en/index.html" target="_blank">MAMP</a> or <a href="http://www.wampserver.com/en/" target="_blank">WAMP</a>.

The easiest is to use PHP's built-in server.

For that i've included a short command, run `daux serve` in the projects folder to start the local web server. By default the server will run at: <a href="http://localhost:8085" target="_blank">http://localhost:8085</a>

## Running Remotely

### Clean URLs configuration

Daux provides native support for Clean URLs provided the webserver has its URL Rewrite module enabled.
To enable the same, set the toggle in the `config.json` file in the `/docs` folder.

```json
{
    "live": {
        "clean_urls": true
    }
}
```

### Apache

Copy the files from the repo to a web server that can run PHP 7.4.0 or newer.

There is an included `.htaccess` for Apache web server.

You can follow this tutorial for [install apache on Ubuntu/Debian](https://www.digitalocean.com/community/tutorials/how-to-install-the-apache-web-server-on-ubuntu-18-04-quickstart).

### Nginx

Daux.io works perfectly fine on Nginx too, just drop this configuration in your `nginx.conf`

You can follow this tutorial for [install nginx on Ubuntu/Debian](https://www.armanism.com/blog/install-nginx-on-ubuntu).

```
server {
    listen 8085;
    server_name  localhost;

    index index.html index.php;
    charset utf-8;

    root /var/www/docs;

    location / {
        if (!-e $request_filename){
            rewrite ^(.*)$ /index.php$1;
        }
    }

    location ~ \.php {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;

        fastcgi_pass unix:/var/run/php5-fpm.sock;
        #fastcgi_pass   127.0.0.1:9000;
        fastcgi_index index.php;

        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_param PATH_INFO $fastcgi_path_info;
    }
}
```

## IIS

If you have set up a local or remote IIS web site, you may need a `web.config` with:

-   A rewrite configuration, for handling clean urls.
-   A mime type handler for less files, if using a custom theme.

### Clean URLs

The `web.config` needs an entry for `<rewrite>` under `<system.webServer>`:

```xml
<configuration>
    <system.webServer>
        <rewrite>
            <rules>
                <rule name="Main Rule" stopProcessing="true">
                    <match url=".*" />
                    <conditions logicalGrouping="MatchAll">
                        <add
                            input="{REQUEST_FILENAME}"
                            matchType="IsFile"
                            negate="true"
                        />
                        <add
                            input="{REQUEST_FILENAME}"
                            matchType="IsDirectory"
                            negate="true"
                        />
                    </conditions>
                    <action
                        type="Rewrite"
                        url="index.php"
                        appendQueryString="false"
                    />
                </rule>
            </rules>
        </rewrite>
    </system.webServer>
</configuration>
```

To use clean URLs on IIS 6, you will need to use a custom URL rewrite module, such as [URL Rewriter](http://urlrewriter.net/).

## Docker

This is not meant for production use, but you can bundle your documentation in Daux's docker container

```
FROM daux/daux.io

WORKDIR /daux/
COPY docs/ /daux/docs/

EXPOSE 80
ENTRYPOINT [ "php", "-S", "0.0.0.0:80", "index.php" ]
```

When you add this to a `Dockerfile` and run `docker build --name my-daux-doc .` and then `docker --rm run -p 8000:80 my-daux-doc`

You can access your documentation at `localhost:8000`
