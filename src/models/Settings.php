<?php
/**
 * OpenID Login plugin for Craft CMS 3.x
 *
 * Allows for CP login with Google's OpenID.
 *
 * @link      https://www.imarc.com/
 * @copyright Copyright (c) 2020 Imarc
 */

namespace Imarc\Craft\OpenidLogin\models;

use Imarc\Craft\OpenidLogin\OpenidLogin;

use Craft;
use craft\base\Model;

/**
 * OpenidLogin Settings Model
 *
 * This is a model used to define the plugin's settings.
 *
 * Models are containers for data. Just about every time information is passed
 * between services, controllers, and templates in Craft, it’s passed via a model.
 *
 * https://craftcms.com/docs/plugins/models
 *
 * @author    Imarc
 * @package   OpenidLogin
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

    /**
     * Some field model attribute
     *
     * @var string
     */
    public $clientId = '';

    public $defaultGroup = NULL;

    public $enableLogin = false;

    public $allowPasswordLogin = false;

    // Public Methods
    // =========================================================================

    /**
     * Returns the validation rules for attributes.
     *
     * Validation rules are used by [[validate()]] to check if attribute values are valid.
     * Child classes may override this method to declare different validation rules.
     *
     * More info: http://www.yiiframework.com/doc-2.0/guide-input-validation.html
     *
     * @return array
     */
    public function rules()
    {
        return [
            ['clientId', 'string'],
            ['clientId', 'default', 'value' => ''],
            ['defaultGroup', 'number'],
            ['defaultGroup', 'integerOnly' => true],
            ['enableLogin', 'boolean']
        ];
    }
}
