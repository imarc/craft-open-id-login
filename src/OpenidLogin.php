<?php
/**
 * OpenID Login plugin for Craft CMS 3.x
 *
 * Allows for CP login with Google's OpenID.
 *
 * @link      https://www.imarc.com/
 * @copyright Copyright (c) 2020 Imarc
 */

namespace Imarc\Craft\OpenidLogin;

use Imarc\Craft\OpenidLogin\services\OpenidLoginService as OpenidLoginServiceService;
use Imarc\Craft\OpenidLogin\models\Settings;

use Imarc\Craft\OpenidLogin\assetbundles\openidlogin\OpenidLoginAsset;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\web\UrlManager;
use craft\events\RegisterUrlRulesEvent;

use yii\base\Event;

/**
 * Craft plugins are very much like little applications in and of themselves. We’ve made
 * it as simple as we can, but the training wheels are off. A little prior knowledge is
 * going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL,
 * as well as some semi-advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://docs.craftcms.com/v3/extend/
 *
 * @author    Imarc
 * @package   OpenidLogin
 * @since     1.0.0
 *
 * @property  OpenidLoginServiceService $openidLoginService
 * @property  Settings $settings
 * @method    Settings getSettings()
 */
class OpenidLogin extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * Static property that is an instance of this plugin class so that it can be accessed via
     * OpenidLogin::$plugin
     *
     * @var OpenidLogin
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * To execute your plugin’s migrations, you’ll need to increase its schema version.
     *
     * @var string
     */
    public string $schemaVersion = '1.0.0';

    /**
     * Set to `true` if the plugin should have a settings view in the control panel.
     *
     * @var bool
     */
    public bool $hasCpSettings = true;

    /**
     * Set to `true` if the plugin should have its own section (main nav item) in the control panel.
     *
     * @var bool
     */
    // public $hasCpSection = true;

    // Public Methods
    // =========================================================================

    /**
     * Set our $plugin static property to this class so that it can be accessed via
     * OpenidLogin::$plugin
     *
     * Called after the plugin class is instantiated; do any one-time initialization
     * here such as hooks and events.
     *
     * If you have a '/vendor/autoload.php' file, it will be loaded for you automatically;
     * you do not need to load it in your init() method.
     *
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Register our CP routes
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function(RegisterUrlRulesEvent $event) {
                $event->rules['open-id-login'] = 'open-id-login/default/defaults';
            }
        );
        
        // Do something after we're installed
        Event::on(
            Plugins::class,
            Plugins::EVENT_AFTER_LOAD_PLUGINS,
            function() {
                $this->executeLogic();
            }
        );

/**
 * Logging in Craft involves using one of the following methods:
 *
 * Craft::trace(): record a message to trace how a piece of code runs. This is mainly for development use.
 * Craft::info(): record a message that conveys some useful information.
 * Craft::warning(): record a warning message that indicates something unexpected has happened.
 * Craft::error(): record a fatal error that should be investigated as soon as possible.
 *
 * Unless `devMode` is on, only Craft::warning() & Craft::error() will log to `craft/storage/logs/web.log`
 *
 * It's recommended that you pass in the magic constant `__METHOD__` as the second parameter, which sets
 * the category to the method (prefixed with the fully qualified class name) where the constant appears.
 *
 * To enable the Yii debug toolbar, go to your user account in the AdminCP and check the
 * [] Show the debug toolbar on the front end & [] Show the debug toolbar on the Control Panel
 *
 * http://www.yiiframework.com/doc-2.0/guide-runtime-logging.html
 */
        Craft::info(
            Craft::t(
                'open-id-login',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return \craft\base\Model|null
     */
    protected function createSettingsModel() : ?\craft\base\Model
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): ?string
    {
        return Craft::$app->view->renderTemplate(
            'open-id-login/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }


    /**
     * Run the main logic of the plugin.
     *
     * @return void
     */
    protected function executeLogic()
    {
        $request = Craft::$app->getRequest();
        $settings = $this->getSettings();

        // Only run if all the settings are set
        if (!$settings->enableLogin || empty($settings->clientId)) {
            return;
        }

        if ($request->getIsLoginRequest()) {
            $view = Craft::$app->getView();
            $view->registerAssetBundle(OpenidLoginAsset::class);
            //$view->registerMetaTag([
            //    'name'    => 'google-signin-scope',
            //    'content' => 'profile email'
            //]);
            
            // Leaving this in as it's easier to retrieve the clientId from
            // here than pass it into the Asset JS.
            $view->registerMetaTag([
                'name'    => 'google-signin-client_id',
                'content' => $settings->clientId
            ]);
            $view->registerJsFile("https://accounts.google.com/gsi/client");
        }
    }
}
