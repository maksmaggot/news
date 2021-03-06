<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%comment}}`.
 */
class m190616_150208_create_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'text' => $this->string(),
            'user_id' => $this->integer(),
            'news_id' => $this->integer(),
        ]);

        $this->createIndex(
            'idx-comment-user_id',
            '{{%comment}}',
            'user_id'
        );

        $this->createIndex(
            'idx-comment-news_id',
            '{{%comment}}',
            'news_id'
        );

        $this->addForeignKey(
            'fk-comment-user_id',
            '{{%comment}}',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-comment-news_id',
            '{{%comment}}',
            'news_id',
            'news',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-comment-user_id', '{{%comment}}');
        $this->dropIndex('idx-comment-news_id', '{{%comment}}');
        $this->dropForeignKey('fk-comment-user_id','{{%comment}}');
        $this->dropForeignKey('fk-comment-news_id','{{%comment}}');
        $this->dropTable('{{%comment}}');
    }
}
