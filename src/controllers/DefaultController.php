<?php
/**
 * OpenID Login plugin for Craft CMS 3.x
 *
 * Allows for CP login with Google's OpenID.
 *
 * @link      https://www.imarc.com/
 * @copyright Copyright (c) 2020 Imarc
 */

namespace Imarc\Craft\OpenidLogin\controllers;

use Imarc\Craft\OpenidLogin\OpenidLogin;
use Imarc\Craft\OpenidLogin\services\OpenidLoginService;
use Imarc\Craft\OpenidLogin\assetbundles\indexcpsection\IndexCPSectionAsset;
use Imarc\Craft\OpenidLogin\assetbundles\openidlogin\OpenidLoginAsset;

use Google;

use Craft;
use craft\web\Controller;

/**
 * Default Controller
 *
 * Generally speaking, controllers are the middlemen between the front end of
 * the CP/website and your plugin’s services. They contain action methods which
 * handle individual tasks.
 *
 * A common pattern used throughout Craft involves a controller action gathering
 * post data, saving it on a model, passing the model off to a service, and then
 * responding to the request appropriately depending on the service method’s response.
 *
 * Action methods begin with the prefix “action”, followed by a description of what
 * the method does (for example, actionSaveIngredient()).
 *
 * https://craftcms.com/docs/plugins/controllers
 *
 * @author    Imarc
 * @package   OpenidLogin
 * @since     1.0.0
 */
class DefaultController extends Controller
{

    // Protected Properties
    // =========================================================================

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     *         The actions must be in 'kebab-case'
     * @access protected
     */
    protected array|int|bool $allowAnonymous = ['index'];

    public $enableCsrfValidation = false;

    // Public Methods
    // =========================================================================

    /**
     * Handle a request going to our plugin's index action URL,
     * e.g.: actions/open-id-login/default
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Craft::$app->request;
        $id = $request->post('credential');

        $loginService = new OpenidLoginService();
        return $loginService->logInUser($id);
    }
}
