<?php

namespace ravesoft\comment\widgets\dashboard;

use ravesoft\comment\models\search\CommentSearch;
use ravesoft\comments\models\Comment;
use ravesoft\models\User;
use ravesoft\widgets\DashboardWidget;
use Yii;

class Comments extends DashboardWidget
{
    /**
     * Most recent comments limit
     */
    public $recentLimit = 5;

    /**
     * Comment index action
     */
    public $commentIndexAction = 'comment/default/index';

    /**
     * Total comments options
     *
     * @var array
     */
    public $options;

    public function run()
    {
        if (!$this->options) {
            $this->options = $this->getDefaultOptions();
        }

        if (User::hasPermission('viewComments')) {
            $searchModel = new CommentSearch();
            $formName = $searchModel->formName();

            $recentComments = Comment::find()->active()->orderBy(['id' => SORT_DESC])->limit($this->recentLimit)->all();

            foreach ($this->options as &$option) {
                $count = Comment::find()->filterWhere($option['filterWhere'])->count();
                $option['count'] = $count;
                $option['url'] = [$this->commentIndexAction, $formName => $option['filterWhere']];
            }

            return $this->render('comments', [
                'height' => $this->height,
                'width' => $this->width,
                'position' => $this->position,
                'comments' => $this->options,
                'recentComments' => $recentComments,
            ]);
        }
    }

    public function getDefaultOptions()
    {
        return [
            ['label' => Yii::t('rave', 'Approved'), 'icon' => 'ok', 'filterWhere' => ['status' => Comment::STATUS_APPROVED]],
            ['label' => Yii::t('rave', 'Pending'), 'icon' => 'search', 'filterWhere' => ['status' => Comment::STATUS_PENDING]],
            ['label' => Yii::t('rave', 'Spam'), 'icon' => 'ban-circle', 'filterWhere' => ['status' => Comment::STATUS_SPAM]],
            ['label' => Yii::t('rave', 'Trash'), 'icon' => 'trash', 'filterWhere' => ['status' => Comment::STATUS_TRASH]],
        ];
    }
}