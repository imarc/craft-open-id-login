<?php
/**
 * OpenID Login plugin for Craft CMS 3.x
 *
 * Allows for CP login with Google's OpenID.
 *
 * @link      https://www.imarc.com/
 * @copyright Copyright (c) 2020 Imarc
 */

namespace \openidlogintests\unit;

use Codeception\Test\Unit;
use UnitTester;
use Craft;
use \openidlogin\OpenidLogin;

/**
 * ExampleUnitTest
 *
 *
 * @author    Imarc
 * @package   OpenidLogin
 * @since     1.0.0
 */
class ExampleUnitTest extends Unit
{
    // Properties
    // =========================================================================

    /**
     * @var UnitTester
     */
    protected $tester;

    // Public methods
    // =========================================================================

    // Tests
    // =========================================================================

    /**
     *
     */
    public function testPluginInstance()
    {
        $this->assertInstanceOf(
            OpenidLogin::class,
            OpenidLogin::$plugin
        );
    }

    /**
     *
     */
    public function testCraftEdition()
    {
        Craft::$app->setEdition(Craft::Pro);

        $this->assertSame(
            Craft::Pro,
            Craft::$app->getEdition()
        );
    }
}
