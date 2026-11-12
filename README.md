<p align="center"><img src="./src/icon.svg" width="100" height="100" alt="Microsoft Graph icon"></p>

<h1 align="center">Microsoft Graph Mailer for Craft CMS</h1>

This plugin provides a [Microsoft Graph](https://learn.microsoft.com/en-us/graph/overview) mailer adapter for Craft CMS.

## Requirements

This plugin requires Craft CMS 5.8.0 or later, or 6.0.0-beta.1 or later with the `craftcms/yii2-adapter` package, and PHP 8.2 or later.

## Installation

You can install this plugin from the Plugin Store or with Composer.

#### From the Plugin Store

Go to the Plugin Store in your project’s Control Panel and search for “Microsoft Graph Mailer”. Then press “Install”.

#### With Composer

Open your terminal and run the following commands:

```bash
# go to the project directory
cd /path/to/my-project.test

# tell Composer to load the plugin
composer require craftcms/microsoft-graph-mailer

# tell Craft to install the plugin
./craft plugin/install microsoft-graph-mailer
```

## Microsoft Prerequisites
- Microsoft Azure Portal account
- Microsoft 365 licensed mailbox

## Microsoft Setup
- Log in to your [Azure Portal account](https://portal.azure.com).
- Navigate to **Azure Active Directory (Entra)**.

### 1. Create the app
- Go to **Manage** → **App registrations** and press **New registration**.
- Give your app a **Name**.
- Under **Supported account types**, select **Accounts in this organizational directory only (Single tenant)**.
- Press **Register**. The app will be created, and you’ll be taken to its overview page.

### 2. Create credentials
- Beside **Client credentials**, press **Add a certificate or secret**.
- Under **Client secrets**, press **New client secret**. Give it a description and preferred expiration timeframe, then press **Add**.
- Copy the **Application (client) ID**, **Directory (tenant) ID**, and the secret **Value** somewhere safe. You’ll need these values to configure the Craft CMS mailer.

> [!NOTE]
> It won’t be possible to re-retrieve the secret’s value once you’ve left the page.

### 3. Grant permissions
- Go to **Manage** → **App registrations** → **_[your app]_** → **Manage** → **API permissions** and press **Add a permission**.
- Under **Microsoft APIs**, select **Microsoft Graph**.
- Select **Application permissions** (not Delegated).
- Select the checkbox for **Mail** → **Mail.Send**.
- Press **Add permissions**. The permission will show in the table with a status of “Not granted for _[your organization name]_”.
- Press **Grant admin consent for _[your organization name]_**. The permission should now show in the table with a status of “Granted for _[your organization name]_”.

## Plugin Setup

Once Microsoft Graph Mailer is installed:

1. Go to **Settings** → **Email**.
2. Change the **Transport Type** setting to **Microsoft Graph Mailer**. Also, ensure that your System Email Address in your Craft Email Settings matches the licensed mailbox you want to send emails from.
3. Enter your Azure App **Client ID** (Application (client) ID).
4. Enter your Azure App **Client Secret** (secret Value).
5. Enter your Azure App **Tenant ID** (Directory (tenant) ID).
6. If you need to use graph and auth endpoints other than the default, you can fill out **Graph Endpoint** and **Auth Endpoint** fields.
7. If you want to save the messages to sent items, toggle **Save to sent items** on.
8. Click **Save**.

## Craft 6

Craft 6 sends email through [Laravel mailers](https://laravel.com/docs/mail). Laravel doesn’t have a Microsoft Graph driver, but Symfony’s Microsoft Graph transport, which this plugin uses, can be registered as one, so you don’t need this plugin once you’ve done that.

With the `craftcms/yii2-adapter` package installed, Craft keeps sending email through the Microsoft Graph settings in your project config until you configure a Laravel mailer. It uses them as the `craft-transport` mailer, which is the default mailer unless you select a mailer in Settings → Email, set the `MAIL_MAILER` environment variable, or change the default mailer in `config/mail.php`. Craft logs a deprecation warning while it does this.

To keep sending email through this plugin, set `MAIL_MAILER=craft-transport` in your environment, or select `craft-transport` as the mailer in Settings → Email. If you do neither and `MAIL_MAILER` names another mailer, such as `log`, Craft sends email through that mailer instead.

To send email through Microsoft Graph with a Laravel mailer:

1. Require Symfony’s Microsoft Graph transport and HTTP client. Removing this plugin removes them too, otherwise.

   ```bash
   composer require symfony/microsoft-graph-mailer symfony/http-client
   ```

2. Register the `microsoft-graph` transport in `bootstrap/app.php`, before `->create()`:

   ```php
   use Illuminate\Support\Facades\Mail;
   use Symfony\Component\HttpClient\HttpClient;
   use Symfony\Component\Mailer\Bridge\MicrosoftGraph\Transport\MicrosoftGraphTransportFactory;
   use Symfony\Component\Mailer\Transport\Dsn;

   // ...
       ->booted(function () {
           Mail::extend('microsoft-graph', fn (array $config) => new MicrosoftGraphTransportFactory(client: HttpClient::create())->create(new Dsn(
               'microsoftgraph+api',
               $config['graph_endpoint'] ?? 'default',
               $config['client_id'],
               $config['client_secret'],
               options: [
                   'tenantId' => $config['tenant_id'],
                   'authEndpoint' => $config['auth_endpoint'] ?? null,
                   'noSave' => !($config['save_to_sent_items'] ?? false),
               ],
           )));
       })
       ->create();
   ```

   If your project has a service provider, such as `app/Providers/AppServiceProvider.php`, you can call `Mail::extend()` in its `boot()` method instead.

3. Add a `microsoft-graph` mailer to the `mailers` array in `config/mail.php`:

   ```php
   'microsoft-graph' => [
       'transport' => 'microsoft-graph',
       'client_id' => env('MICROSOFT_GRAPH_CLIENT_ID'),
       'client_secret' => env('MICROSOFT_GRAPH_CLIENT_SECRET'),
       'tenant_id' => env('MICROSOFT_GRAPH_TENANT_ID'),
       'graph_endpoint' => env('MICROSOFT_GRAPH_ENDPOINT', 'default'),
       'auth_endpoint' => env('MICROSOFT_GRAPH_AUTH_ENDPOINT'),
       'save_to_sent_items' => env('MICROSOFT_GRAPH_SAVE_TO_SENT_ITEMS', false),
   ],
   ```

4. Set the environment variables in `.env`. `MAIL_FROM_ADDRESS` has to be the licensed mailbox you send from, because Craft 6 uses it as the default sender for email that doesn’t have one.

   ```bash
   MAIL_MAILER=microsoft-graph
   MAIL_FROM_ADDRESS=mailbox@example.com
   MICROSOFT_GRAPH_CLIENT_ID=your-client-id
   MICROSOFT_GRAPH_CLIENT_SECRET=your-client-secret
   MICROSOFT_GRAPH_TENANT_ID=your-tenant-id
   ```

   If you set **Graph Endpoint** and **Auth Endpoint** for a national cloud, set both `MICROSOFT_GRAPH_ENDPOINT` and `MICROSOFT_GRAPH_AUTH_ENDPOINT`, as hostnames without `https://`, such as `graph.microsoft.us` and `login.microsoftonline.us`. To keep saving messages to Sent Items, set `MICROSOFT_GRAPH_SAVE_TO_SENT_ITEMS=true`.

5. Send a test email from Settings → Email, or with `php artisan craft:mailer:test`.

Then uninstall the plugin and remove it from your project:

```bash
php artisan craft:plugin:uninstall microsoft-graph-mailer
composer remove craftcms/microsoft-graph-mailer
```
