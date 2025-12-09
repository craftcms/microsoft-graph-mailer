# Microsoft Graph Mailer

Microsoft Graph Mailer adapter for Craft CMS

## Requirements

This plugin requires Craft CMS 5.8.0 or later, and PHP 8.2 or later.

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
- Navigate to Azure Active Directory (Entra).

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
- Go to **Manage** → **App registrations** → _[your app]_ → **Manage** → **API permissions** and press **Add a permission**.
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
