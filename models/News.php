<?php

namespace app\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "news".
 *
 * @property int $id
 * @property string $created_at
 * @property bool $active
 * @property string $title
 * @property int $category_id
 * @property string $preview
 * @property string $content
 * @property string $slug
 *
 * @property Comment[] $comments
 */
class News extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'preview', 'content'], 'required'],
            [['created_at'], 'safe'],
            [['created_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['created_at'], 'default', 'value' => date('Y-m-d H:i:s')],
            [['active'], 'boolean'],
            [['category_id'], 'default', 'value' => null],
            [['category_id'], 'integer'],
            [['content'], 'string'],
            [['title', 'preview', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Created At',
            'active' => 'Active',
            'title' => 'Title',
            'category_id' => 'Category',
            'preview' => 'Preview',
            'content' => 'Content',
            'slug' => 'Slug',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
            ]
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['news_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @param bool $category_id
     * @return ActiveDataProvider
     */
    public static function getPaginatedDataProvider($category_id = false)
    {
        $params = ['active' => true];
        if ($category_id) {
            $childrenIds = Category::getChildIds($category_id);
            if (is_array($childrenIds) && !empty($childrenIds)) {
                $params['category_id'] = $childrenIds;
                $params['category_id'][] = (int)$category_id;
            } else {
                $params['category_id'] = $category_id;
            }
        }

        return new ActiveDataProvider([
            'query' => self::find()->where($params)->orderBy('created_at DESC'),
            'pagination' => [
                'pageSize' => 3,
                'defaultPageSize' => 3,
                'forcePageParam' => false,
            ],
        ]);
    }

    /**
     * @param $slug
     * @return ActiveRecord|null
     */
    public static function findBySlug($slug)
    {
        return News::find()->where(['slug' => $slug])->one();
    }

    public function getDate()
    {
        return Yii::$app->formatter->asDate($this->created_at);
    }
}
