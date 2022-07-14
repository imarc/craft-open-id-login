<?php
/**
 * OpenID Login plugin for Craft CMS
 *
 * Allows for CP login with Google's OpenID.
 *
 * @link      https://www.imarc.com/
 * @copyright Copyright (c) 2022 Imarc
 */

namespace Imarc\Craft\OpenidLogin\services;

use Imarc\Craft\OpenidLogin\OpenidLogin;
use Imarc\Craft\OpenidLogin\records\OpenidLoginRecord;
use craft\elements\User;
use craft\records\UserGroup;

use Google;

use Craft;
use craft\base\Component;

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
            $generalConfig = Craft::$app->getConfig()->getGeneral();
            $response      = Craft::$app->response;
            $users         = Craft::$app->getUsers();
            $userExists    = User::findOne(['email' => $payload['email']]);

            if (!$userExists) {
                // Create a new user
                $userExists = $this->createUser($payload);

                if (!$userExists) {
                    return $response->setStatusCode(400);
                }

                Craft::$app->getUser()->login($userExists, $generalConfig->userSessionDuration);
                return $response->redirect($users->getPasswordResetUrl($userExists));
            }

            //If the user already exists log them in
            if (Craft::$app->getUser()->login($userExists, $generalConfig->userSessionDuration)) {
                $return_url = Craft::$app->user->getReturnUrl();
                return Craft::$app->response->redirect($return_url);
            }
        }

        return Craft::$app->response->setStatusCode(400);
    }

    /**
     * Creates a new user based on information from the Open ID payload.
     *
     * @return User
     */
    public function createUser($payload) {
        $settings = OpenidLogin::$plugin->getSettings();

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

        //Assign User default Group
        $group = UserGroup::findOne(['id' => $settings->defaultGroup ?? null]);
        if ($group) {
            Craft::$app->getUsers()->assignUserToGroups($user->getId(), [$group->id]);
        }

        // In case the user is deleted at one point
        $record = OpenidLoginRecord::findOne(['providerId' => $payload['sub']]);
        if (!$record) {
            $record = new OpenidLoginRecord();
        }

        // Associate the user with plugin record
        $record->userId     = $user->getId();
        $record->providerId = $payload['sub'];
        $record->save();

        return $user;
    }
}
