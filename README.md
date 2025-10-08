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
- Go to "Manage" > "App registrations" and select "+ New registration".
  - Give your app a "Name".
  - Under "Supported account types", choose "Accounts in this organizational directory only (Single tenant)".
  - Click "Register" - the app will be created, and you'll be taken to its overview page.

### 2. Create credentials
- Next to "Client credentials" click the "Add a certificate or secret".
  - Under "Client secrets", click "+ New client secret"; give it a description and choose preferred expiration timeframe; click "Add".
  - Important: make a note of the secret's Value - you won't be able to retrieve it if you leave or reload the page.
- You'll need the "Application (client) ID", "Directory (tenant) ID" and the secret "Value" to configure the plugin in Craft CMS.

### 3. Grant permissions
- In your app (Manage > App registrations > <your app>), go to "Manage" > "API permissions" and select "+ Add a permission".
- Under "Microsoft APIs", select "Microsoft Graph".
- Select "Application permissions" (not Delegated); Find "Mail" > "Mail.Send" and select a checkbox next to it.
- Click "Add permissions" - the permission will show in the table with a status of "Not granted for <your organization name>".
- Click the "✓ Grant admin consent for <your organization name>" - the permission should now show in the table with a status of "Granted for <your organization name>".

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

