<?php
/**
 * OpenID Login plugin for Craft CMS 3.x
 *
 * Allows for CP login with Google's OpenID.
 *
 * @link      https://www.imarc.com/
 * @copyright Copyright (c) 2020 Imarc
 */

namespace Imarc\Craft\OpenidLogin\services;

use Imarc\Craft\OpenidLogin\OpenidLogin;
use Imarc\Craft\OpenidLogin\records\OpenidLoginRecord;
use craft\elements\User;

use Google;

use Craft;
use craft\base\Component;
// use craft\records\User;

/**
 * OpenidLoginService Service
 *
 * All of your plugin’s business logic should go in services, including saving data,
 * retrieving data, etc. They provide APIs that your controllers, template variables,
 * and other plugins can interact with.
 *
 * https://craftcms.com/docs/plugins/services
 *
 * @author    Imarc
 * @package   OpenidLogin
 * @since     1.0.0
 */
class OpenidLoginService extends Component
{
    // Public Methods
    // =========================================================================

    /**
     * This function logs in a user based off of a google id_token
     *
     * @return mixed
     */
    public function logInUser($id_token)
    {
        $client = new Google\Client(['client_id' => OpenidLogin::$plugin->getSettings()->clientId]);

        // This will validate against the clientID
        if ($payload = $client->verifyIdToken($id_token)) {

            $userExists = User::findOne(['email' => $payload['email']]);

            if ($userExists) {
                //If the user already exists log them in
                $generalConfig = Craft::$app->getConfig()->getGeneral();
                Craft::$app->getUser()->login($userExists, $generalConfig->userSessionDuration);

                $referrer = Craft::$app->request->getReferrer();
                return Craft::$app->response->redirect($referrer);

            } else {

                // Create a new user
                return $this->createUser($payload);
            }
        }

        return Craft::$app->response->setStatusCode(400);
    }


    public function createUser($payload) {
        // $userSettings = Craft::$app->getProjectConfig()->get('users') ?? [];
        // $requireEmailVerification = $userSettings['requireEmailVerification'] ?? true;

        $user = new User();
        $user->username        = $payload['email'];
        $user->email           = $payload['email'];
        $user->unverifiedEmail = $payload['email'];
        $user->firstName       = $payload['given_name'];
        $user->lastName        = $payload['family_name'];

        // Manually validate the user so we can pass $clearErrors=false
        if (
            !$user->validate(null, false) ||
            !Craft::$app->getElements()->saveElement($user, false)
        ) {

        }

        // Send the activation email
        $response = Craft::$app->getResponse();
        $users = Craft::$app->getUsers();

        // Incase the user is deleted at one point
        $record = OpenidLoginRecord::findOne(['providerId' => $payload['sub']]);
        if (!$record) {
            $record = new OpenidLoginRecord();
        }

        // Associate the user with
        $record->userId = $user->getId();
        $record->providerId = $payload['sub'];
        $record->save();

        return $response->redirect($users->getPasswordResetUrl($user));
    }
}
