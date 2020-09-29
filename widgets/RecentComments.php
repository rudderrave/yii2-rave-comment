<?php

namespace ravesoft\comment\widgets;

use ravesoft\comment\models\search\CommentSearch;
use ravesoft\comments\models\Comment;
use ravesoft\models\User;
use ravesoft\widgets\DashboardWidget;
use Yii;

class RecentComments extends DashboardWidget
{

    /**
     * Most recent comments limit
     */
    public $recentLimit = 5;
    
    public $layout = 'layout';
    public $commentTemplate = 'comment';

    public function run()
    {
        $recentComments = Comment::find()
                ->active()
                ->orderBy(['created_at' => SORT_DESC])
                ->limit($this->recentLimit)
                ->all();

        return $this->render($this->layout, [
            'recentComments' => $recentComments,
            'commentTemplate' => $this->commentTemplate,
        ]);
    }

}
