<?php

use yii\db\Migration;

/**
 * Handles the creation of table `image`.
 */
class m180710_200533_create_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('image', [
            'id' => $this->primaryKey(),
            'contact_id' => $this->integer(11)->notNull(),
            'original_filename' => $this->string(255)->notNull(),
            'file_name' => $this->char('32')->notNull(),
        ], 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB');

        $this->addForeignKey('image_contact_fk', 'image', 'contact_id', 'contact', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('image');
    }
}
