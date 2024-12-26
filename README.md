-   [English](README.en.md)
-   [日本語](README.md)

# Laravel で複数のクライアント作成

こちらは　 CodeIgniter の BaseClient みたいな　複数のクライアントを管理できる Laravel の環境です。

管理画面は Tailwind CSS で書いております。　モデルなどは SweetAlert2 を使用していますが　個別の　テネット「クライアント」　などは　 <em><strong>自分で作成</em></strong> しても　大丈夫です。

ここでは　 Ajax では無くて Axios を使用しております。Ajax はセキュリティーには　弱いですので。。

参考：[OWASP](https://cheatsheetseries.owasp.org/cheatsheets/AJAX_Security_Cheat_Sheet.html)

## セットアップ

Composer を [インストール](https://getcomposer.org/download/)してください。
バージョンはどれでも　大丈夫です。

NodeJS v23 を [インストール](https://nodejs.org/en/download/package-manager) してください。理由は JS などを書く時自動で Minify と暗号化してくれます。

そして　ここに　 CMD を　開けて

```bash
composer install
```

```bash
npm install
```

もし NPM がインストールできなかったら　 npm update で新しいバージョンをアップグレードしても大丈夫です。

<em><strong> Laravel のバージョンもアップグレードしたい時　なにも気にしなくて　これで　大丈夫です。</em></strong>

```bash
composer update
```

そして　.env.local をコピーして　こちらで　新しいキーをしてください。

<em><strong> サーバーからのデータをみたいなときに.env の APP_KEY をサーバーでの　キーと　合わせてください。</em></strong>

```bash
php artisan:key generate
```

そして　 PostgresSQL で DB を作成します。　作成が終わったら　.env で　こういう感じでにしてください。

```bash
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=作成したDB名
DB_USERNAME=DBのユーザー名
DB_PASSWORD=DBのパスワード
```

こちらなどを　終わったら　ベースになるスキマを作成しましょう。

CMD で実行してください。

こちらは base_client というスキマを作成してくれます。　ここでは　管理画面の情報などを保管されます。

```bash
php artisan tenant:create-base-schema --seed
```

管理画面の開発者のユーザー名とパスワードは ここにあります。　こちらは　変更しても大丈夫です。

```bash
database\seeders\AdminSeeder.php
```

そして base_client のスキマを作成しましょ。。

```bash
php artisan setup:base-client --seed
```

ここでは　クライアントのベースになる DB を作成されます。

そして　ローカル　なら　ば　 2 つの CMD を開けます。

そして　２つの CMD で　１つつ　実行します。

```bash
php artisan serve
```

```bash
npm run dev
```

これら　実行されているときは　開発できます。

## リリース準備

リリースする時に　必ずこちらを　実行し　/public/build/を　全てアップしてください。

```bash
npm run build
```

## 開発する時　必ずこちらの手順をしてください。　しないと　エラー発生されます。

こちらは　 database/migrations/tenant で　新しい migration ファイルを作成してくれます。

```bash
php artisan make:tenant-migration create_posts_table --create=posts
```

テーブルを更新してい時はこちらでお願いします。

```bash
php artisan make:tenant-migration add_status_to_posts_table --table=posts
```

base_tenent i.e 　管理テーブルにしたいときは　こちらをお願い致します。

```bash
php artisan make:migration create_example_table --path=database/migrations/base
```

<em><strong> そして　上の手順が終わったら　必ず　必ず　こちらを　実行してください。 </em></strong>

```bash
php artisan tenants:migrate
```

<em><strong> それを実行しないで　普通の Laravel みたいな　こちらを実行したら　エラー発生されます。</em></strong>

```bash
php artisan migrate
```

そして Route を作成するとき必ず Middleware を設置してください。

参考： routes/admin/admin.php

こちらは　管理者の Middleware です。ここの中に書いているものは公開ルートです。

```bash
Route::prefix('backend/admin')->name('admin.')->middleware('set.tenant')
```

ここの中に書くものは　認証が必要なルートです。

```bash
Route::middleware(['admin.auth'])
```

参考：　 routes/tenents/tenant.php

ここは　クライアント　テネットの Middleware です。必ずこちらも必要です。

```bash
Route::prefix('backend/{tenant}')
    ->middleware('set.tenant')  // Middleware to load tenant
```

これはクライアントで認証が必要なルートです。

```bash
 Route::middleware(['tenent.auth'])
```

## Basic 認証

Basic 認証は基本 /storage/tenants/.htpasswd で登録されます。
大丈夫です。基本 Laravel の storage は　公開できないように　なってます。
Symlink のこちらの　 cmd を使用しても　大丈夫です。
基本クライアントを作成したら Basic 認証が発行されますので。。
管理画面そのクライアントの Basic 認証はリセットできます。

```bash
php artisan storage:link
```

上の Symlink は基本 /storage/app を /public にしてくれる　物なので　大丈夫です。

## Tenent 作成を開発する時の使い方

Route をしたら　テネットのばいは　こちらが　必要です。

理由はこの tenent がないと テネット個人の Middleware が動かなくなります。

JS で URL をしても　同じです。　 JS のパラメーターは自分で送ってください。

```bash
{{ route('tenant.users.check_login', ['tenant' => $tenant_name] // <- ['tenant'])が必要 }}
```

## JS のインストール仕方

NPM か CDN でインストールしてください。NPM でしないと　 JS が使えないです。

理由はこの Vite が JS などを Minify と暗号化してくれるからです。
冷：

```bash
npm install sweetalert2
```

## 改行と　インテント　問題を無くすため　Prettifierを入れています

必ずコミット前に　これを　実行してください。
なお：　VSCodeでのプラグインも使えます。
.vscodeは　設定フォルダです。
```bash
npm run format
```

## AXIOS の使い方

非同期

```bash
const response = await axios.post('/api/backend/admin/tenent_users', data);
console.log(response);
```

同期

```bash
axios.post('/api/persons/unique/alias', {
        params: {
            id: this.id,
            alias: this.alias,
        }
    })
    .then((response) => {
        console.log('2. server response:' + response.data.unique)
        this.valid = response.data.unique;
    });
```

そして Laravel の PUT と DELETE を使う時に必ず \_method でしてください。

Laravel のセキュリティーです。

```bash
 var data = {  _method: 'DELETE' };
 const response = await axios.post('/api/backend/admin/tenent_users', data);
 var data = {  _method: 'PUT' };
 const response = await axios.post('/api/backend/admin/tenent_users', data);

```

## SweetAlert2 の使い方

```bash
Swal.fire({
                    icon: 'question',
                    title: langs.ask_create.replace(':data', '各サイトのメンテナンス'),
                    html: data.modal_html,
                    focusConfirm: false,
                    showCancelButton: true,
                    confirmButtonText: langs.yes,
                    cancelButtonText: langs.no,
                    customClass: {
                        input: 'my-swal-input',
                        confirmButton: 'btn btn-primary custom-confirm-button',
                        cancelButton: 'btn btn-secondary'
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    preConfirm: () => {
                        // This callback will return false initially, preventing the modal from closing
                        return false;
                    },

                    didOpen: () => {
                        var frontSiteChecked = jsonData?.front_site === 'frontend' ? true : false;
                        var backSiteChecked = jsonData?.back_site === 'backend' ? true : false;
                        var maintenanceMode = jsonData?.maintenance_0;
                        var maintenanceTermStart = jsonData?.maintenance_term?.maintanance_term_start || '';
                        var maintenanceTermEnd = jsonData?.maintenance_term?.maintanance_term_end || '';
                        var allowIp = jsonData?.allow_ip?.join('\n'); // Join IPs with newline separator
                        var frontMessage = jsonData?.front_main_message || '';
                        var backMessage = jsonData?.back_main_message || '';
                        console.log([frontSiteChecked,
                            backSiteChecked,
                            maintenanceMode,
                            maintenanceTermStart,
                            maintenanceTermEnd,
                            allowIp,
                            frontMessage,
                            backMessage
                        ]);
                        // Collect form data from the modal
                        // Set checkbox values based on boolean
                        $('input[name="front_site_modal"]').prop('checked', frontSiteChecked);
                        $('input[name="back_site_modal"]').prop('checked', backSiteChecked);
                        $('input[name="maintenance_0_modal"][value="' + maintenanceMode + '"]').prop('checked', true);
                        // Set the textarea values
                        $('textarea[name="allow_ip_modal"]').val(allowIp);
                        $('textarea[name="front_main_message_modal"]').val(frontMessage);
                        $('textarea[name="back_main_message_modal"]').val(backMessage);

                        // If you want to populate a label or another element:
                        $('#maintenance_term_modal').text(maintenanceTermStart + ' to ' + maintenanceTermEnd);
                        flatpickr("#maintenance_term_modal", {
                            mode: 'range',
                            enableTime: true,
                            dateFormat: "Y-m-d H:i:S",
                            time_24hr: true,
                            defaultDate: [
                                $('#maintenance_term_modal').data('start'),
                                $('#maintenance_term_modal').data('end')
                            ]
                        });
                        const confirmButton = Swal.getConfirmButton();
                        if (confirmButton) {
                            confirmButton.addEventListener('click', async () => {
                                // Collect form data from the modal
                                var frontSiteChecked = $('input[name="front_site_modal"]:checked').val();
                                var backSiteChecked = $('input[name="back_site_modal"]:checked').val();
                                var maintenanceMode = $('input[name="maintenance_0_modal"]:checked').val();
                                var maintenanceTerm = $('input[name="maintenance_term_modal"]').val();
                                var allowIp = $('textarea[name="allow_ip_modal"]').val();
                                var frontMessage = $('textarea[name="front_main_message_modal"]').val();
                                var backMessage = $('textarea[name="back_main_message_modal"]').val();

                                // Create FormData object
                                var formData = new FormData();
                                // Add other form fields
                                formData.append('front_site', frontSiteChecked);
                                formData.append('back_site', backSiteChecked);
                                formData.append('maintenance_0', maintenanceMode);

                                formData.append('maintenance_term', maintenanceTerm);
                                formData.append(`allow_ip`, allowIp);

                                formData.append('front_main_message', frontMessage);
                                formData.append('back_main_message', backMessage);
                                formData.append('tenant', user_id);
                                formData.append('_method', 'PUT');

                                try {
                                    const response = await axios.post(`/api/backend/admin/maitenances/${user_id}/update`, formData);

                                    if (response.data.type === 'error') {
                                        var errorMessages = response.data.data;
                                        var errorMessage = errorMessages.join('<br>');

                                        Swal.showValidationMessage(errorMessage.trim());
                                        return; // Keep the modal open if validation fails
                                    } else {
                                        Swal.close();
                                        Swal.fire({
                                            icon: 'success',
                                            title: langs.success_title,
                                            text: langs.success.replace(':attribute', langs.account),
                                            confirmButtonText: 'OK'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Reload the page after the user clicks "OK"
                                                window.location.reload();
                                            }
                                        });
                                    }
                                } catch (error) {
                                    console.error(error);
                                    Swal.fire('Error!', 'There was an issue with your request.', 'error');
                                    return; // Keep the modal open in case of request failure
                                }
                                // AJAX form submission

                            });
                        }
                    }
                });
```
## ApacheのConf
```bash
# ServerRoot: The directory where the server's configuration, error, and log files are stored.
ServerRoot "/etc/httpd"

# Listen: Port Apache listens on (default is port 80 for HTTP).
Listen 80

# Include additional configuration files (modular system).
Include conf.modules.d/*.conf

# User and group Apache runs as.
User apache
Group apache

# ServerAdmin and ServerName settings
ServerAdmin root@localhost
ServerName labo.ascon.co.jp:80

# Default DocumentRoot configuration will be set by the script
DocumentRoot "/var/www/html"

# Directory configuration for /public (This will be updated if the directory exists)
<Directory "/var/www/html/public">
    AllowOverride All
    Require all granted
</Directory>

# Fallback DocumentRoot if /public doesn't exist
<Directory "/var/www/html">
    AllowOverride All
    Require all granted
</Directory>

# Set default directory index files (Laravel requires index.php)
DirectoryIndex index.php index.html

# Error handling for .htaccess files
<Files ".ht*">
    Require all denied
</Files>

# Logging configuration
ErrorLog "logs/error_log"
LogLevel warn

# CustomLog format for combined log format
LogFormat "%h %l %u %t \"%r\" %>s %b \"%{Referer}i\" \"%{User-Agent}i\"" combined
CustomLog "logs/access_log" combined

# Alias and ScriptAlias configuration
<IfModule alias_module>
    ScriptAlias /cgi-bin/ "/var/www/cgi-bin/"
</IfModule>

# Directory settings for CGI scripts
<Directory "/var/www/cgi-bin">
    AllowOverride All
    Options None
    Require all granted
</Directory>

# MIME module settings
<IfModule mime_module>
    TypesConfig /etc/mime.types
    AddType application/x-compress .Z
    AddType application/x-gzip .gz .tgz
    AddType text/html .shtml
    AddOutputFilter INCLUDES .shtml
</IfModule>

# UTF-8 as default charset
AddDefaultCharset UTF-8

# Enable Sendfile (for optimized file transfers)
EnableSendfile off

# Security settings
ServerTokens Prod
ServerSignature Off
TraceEnable off
Header unset X-Powered-By

# Timeout and performance settings
Timeout 300

# Include additional configuration files
IncludeOptional conf.d/*.conf

```

## Vagrant Conf
```bash
Vagrant.configure("2") do |config|
  # Use a CentOS base box
   config.vm.box = "generic/centos9s"
  config.vm.network "private_network", ip: "192.168.33.16"

  # Sync the Laravel source code from host to guest
  config.vm.synced_folder "D:\\kein\\発注システム\\source\\hatchuu_system", "/var/www/html"

  # Provisioning script for Apache setup
  config.vm.provision "shell", inline: <<-SHELL
    # Update and install necessary packages
    yum update -y
    yum install -y httpd

    # Set SELinux to permissive to avoid permission issues (not recommended for production)
    setenforce 0
    sed -i 's/^SELINUX=enforcing/SELINUX=permissive/' /etc/selinux/config

    # Ensure proper ownership and permissions for /var/www/html
    chown -R vagrant:vagrant /var/www/html
    chmod -R 755 /var/www/html

    # Configure Apache
    sed -i 's|DocumentRoot "/var/www/html"|DocumentRoot "/var/www/html/public"|' /etc/httpd/conf/httpd.conf

    # Allow .htaccess overrides and configure permissions
    cat <<EOF > /etc/httpd/conf.d/laravel.conf
    <Directory "/var/www/html/public">
        AllowOverride All
        Require all granted
    </Directory>
    EOF
	# Open HTTP port in firewall
    sudo firewall-cmd --permanent --add-service=http
    sudo firewall-cmd --reload

    # Enable and start Apache
    systemctl enable httpd
    systemctl start httpd
  SHELL
  
  
  # Configure proxy settings if the plugin is installed
  if Vagrant.has_plugin?("vagrant-proxyconf")
    config.proxy.http     = "http://172.26.67.100:80"
    config.proxy.https    = "http://172.26.67.100:80"
    config.proxy.no_proxy = "localhost,127.0.0.1"
  end

  # VirtualBox provider configuration
  config.vm.provider "virtualbox" do |vb|
    vb.gui = true              # Display the VirtualBox GUI
    vb.memory = "1024"         # Allocate 1024 MB of memory
  end

  # Disable auto-update for vbguest if the plugin is installed
  if Vagrant.has_plugin?("vagrant-vbguest")
    config.vbguest.auto_update = false
  end

  # Allow insecure box downloads
  config.vm.box_download_insecure = true
end

```


以上
