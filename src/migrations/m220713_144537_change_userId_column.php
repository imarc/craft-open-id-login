<?php

namespace Imarc\Craft\OpenidLogin\migrations;

use Craft;
use craft\db\Migration;

/**
 * m220713_144537_change_userId_column migration.
 */
class m220713_144537_change_userId_column extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp(): bool
    {
        $this->alterColumn(
            '{{%imarc_openidlogin_record}}',
            'userId',
            $this->integer()->notNull()
        );

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown(): bool
    {
        $this->alterColumn(
            '{{%imarc_openidlogin_record}}',
            'userId',
            $this->string(255)->notNull()
        );
        return false;
    }
}
