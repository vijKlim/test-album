<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%photo}}`.
 */
class m220317_132151_create_photo_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%photo}}', [
            'id' => $this->primaryKey(),
            'album_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'url' => $this->string(255)->notNull()
        ]);

        $this->createIndex(
            'idx-photo-album_id',
            '{{%photo}}',
            'album_id'
        );

        // add foreign key for table `post`
        $this->addForeignKey(
            'fk-photo-album_id',
            'photo',
            'album_id',
            'album',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%photo}}');
    }
}
