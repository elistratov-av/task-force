<?php

namespace app\helpers;

use app\models\Task;
use app\models\User;
use app\exceptions\StatusActionException;
use app\logic\actions\CancelAction;
use app\logic\actions\CompleteAction;
use app\logic\actions\DenyAction;
use app\logic\actions\ResponseAction;
use app\logic\AvailableActions;
use yii\helpers\Html;
use yii\helpers\Url;

class UIHelper
{

    public static function showStarRating($value, $size = 'small', $starsCount = 5, $active = false)
    {
        $stars = '';

        for ($i = 1; $i <= $starsCount; $i++) {
            $className = $i <= $value ? 'fill-star' : '';
            $stars .= Html::tag('span', '&nbsp;', ['class' => $className]);
        }

        $className = 'stars-rating ' . $size;

        if ($active) {
            $className .= ' active-stars';
        }

        $result = Html::tag('div', $stars, ['class' => $className]);

        return $result;
    }

    public static function getActionButtons(Task $task, User $user)
    {
        $buttons = [];

        $colorsMap = [
            CancelAction::getInternalName()   => 'orange',
            ResponseAction::getInternalName() => 'blue',
            DenyAction::getInternalName()     => 'pink',
            CompleteAction::getInternalName() => 'yellow'
        ];

        $roleName = $user->is_contractor ? AvailableActions::ROLE_PERFORMER : AvailableActions::ROLE_CLIENT;

        try {
            $availableActionsManger = new AvailableActions($task->status->slug, $task->client_id, $task->performer_id);
            $actions = $availableActionsManger->getAvailableActions($roleName, $user->id);

            foreach ($actions as $action) {
                $color = $colorsMap[$action::getInternalName()];
                $label = $action::getLabel();

                $options = [
                    'data-action' => $action::getInternalName(),
                    'class' => 'button action-btn button--' . $color
                ];

                if ($action::getInternalName() === 'act_cancel') {
                    $options['href'] = Url::to(['tasks/cancel', 'id' => $task->id]);
                }

                $btn = Html::tag('a', $label, $options);

                $buttons[] = $btn;
            }
        } catch (StatusActionException $e) {
            error_log($e->getMessage());
        }

        return $buttons;
    }

    public static function getMyTasksMenu($isContractor)
    {
        $items = [];

        if ($isContractor) {
            $items[] = ['label' => 'В процессе', 'url' => ['tasks/my', 'status' => 'in_progress']];
            $items[] = ['label' => 'Просрочено', 'url' => ['tasks/my', 'status' => 'expired']];
            $items[] = ['label' => 'Закрытые', 'url' => ['tasks/my', 'status' => 'close']];
        }
        else {
            $items[] = ['label' => 'Новые', 'url' => ['tasks/my', 'status' => 'new']];
            $items[] = ['label' => 'В процессе', 'url' => ['tasks/my', 'status' => 'in_progress']];
            $items[] = ['label' => 'Закрытые', 'url' => ['tasks/my', 'status' => 'close']];
        }

        return $items;
    }

}