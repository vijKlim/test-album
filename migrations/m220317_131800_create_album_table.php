<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%album}}`.
 */
class m220317_131800_create_album_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%album}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
        ]);

        $this->createIndex(
            'idx-album-user_id',
            '{{%album}}',
            'user_id'
        );

        // add foreign key for table `post`
        $this->addForeignKey(
            'fk-album-user_id',
            'album',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%album}}');
    }
}
