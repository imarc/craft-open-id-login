# OpenID Login plugin for Craft CMS 3.x

Allows for CP login with Google's OpenID.

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require imarc/craft-open-id-login

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for OpenID Login.

## OpenID Login Overview
A simple plugin that allows for creating/accessing accounts through Google Login. 

Requires you to create a OAuth Client ID from [Google Developers Console](https://console.developers.google.com/apis/credentials). Then paste the client Id into the 
'Client Id' field under OpenId settings(`/admin/settings/plugins/open-id-login`). Once you're ready flip the lightswitch for `Enable Login` and users will be able to log in with their google accounts. 

If you would like to only allow accounts from your organization make sure that your google application user type is set to Internal.

## Settings


Brought to you by [Imarc](https://www.imarc.com/)
