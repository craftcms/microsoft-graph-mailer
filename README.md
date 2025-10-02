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

## Azure Setup

## Plugin Setup

Once Microsoft Graph Mailer is installed:

1. Go to **Settings** → **Email**.
2. Change the **Transport Type** setting to **Microsoft Graph Mailer**. Also, ensure that your System Email Address in your Craft Email Settings matches the licensed mailbox you want to send emails from.
3. Enter your Azure App **Client ID**.
4. Enter your Azure App **Client Secret**.
5. Enter your Azure App **Tenant ID**.
6. If you need to use graph and auth endpoints other than the default, you can fill out **Graph Endpoint** and **Auth Endpoint** fields.
7. If you want to save the messages to sent items, toggle **Save to sent items** on.
8. Click **Save**.

