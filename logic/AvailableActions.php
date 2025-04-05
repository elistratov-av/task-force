<?php

namespace app\logic;

use app\exceptions\StatusActionException;
use app\logic\actions\AbstractAction;
use app\logic\actions\CancelAction;
use app\logic\actions\CompleteAction;
use app\logic\actions\DenyAction;
use app\logic\actions\ResponseAction;
use app\logic\actions\StartAction;

class AvailableActions
{
    const STATUS_NEW = 'new';
    const STATUS_CANCEL = 'cancel';
    const STATUS_IN_PROGRESS = 'proceed';
    const STATUS_COMPLETE = 'complete';
    const STATUS_EXPIRED = 'expired';

    const ROLE_PERFORMER = 'performer';
    const ROLE_CLIENT = 'customer';

    private string $status;

    private int $clientId;
    private ?int $performerId;

    /**
     * @param string $status
     * @param int $clientId
     * @param int|null $performerId
     * @throws StatusActionException
     */
    public function __construct(string $status, int $clientId, ?int $performerId = null)
    {
        $this->setStatus($status);
        $this->clientId = $clientId;
        $this->performerId = $performerId;
    }

    /**
     * @param string $status
     * @return void
     * @throws StatusActionException
     */
    private function setStatus(string $status): void
    {
        $availableStatus = [self::STATUS_NEW, self::STATUS_CANCEL, self::STATUS_IN_PROGRESS, self::STATUS_COMPLETE, self::STATUS_EXPIRED];
        if (!in_array($status, $availableStatus)) {
            throw new StatusActionException("Неизвестный статус: $status");
        }
        $this->status = $status;
    }

    /**
     * Возвращает доступные действия для заданной роли и пользователя
     * @param string $role
     * @param int $id
     * @return string[]
     * @throws StatusActionException
     */
    public function getAvailableActions(string $role, int $id): array
    {
        $this->checkRole($role);

        $statusActions = $this->statusAllowedActions($this->status);
        $roleActions = $this->roleAllowedActions($role);

        $allowedActions = array_intersect($statusActions, $roleActions);

        $allowedActions = array_filter($allowedActions, function ($action) use ($id) {
            return $action::checkRights($id, $this->performerId, $this->clientId);
        });

        return array_values($allowedActions);
    }

    /**
     * Возвращает «карту» статусов
     * @return string[]
     */
    public function getStatusesMap(): array
    {
        return [
            self::STATUS_NEW => "Новое",
            self::STATUS_CANCEL => "Отменено",
            self::STATUS_IN_PROGRESS => "В работе",
            self::STATUS_COMPLETE => "Выполнено",
            self::STATUS_EXPIRED => "Провалено",
        ];
    }

    /**
     * Возвращает статус, в которой перейдёт после выполнения указанного действия
     * @param AbstractAction $action
     * @return string|null
     */
    public function getNextStatus(AbstractAction $action): ?string
    {
        $map = [
            CompleteAction::class => self::STATUS_COMPLETE,
            CancelAction::class => self::STATUS_CANCEL,
            DenyAction::class => self::STATUS_EXPIRED,
            StartAction::class => self::STATUS_IN_PROGRESS,
        ];

        return $map[get_class($action)] ?? null;
    }

    /**
     * Проверяет допустимость роли
     * @param string $role
     * @return void
     * @throws StatusActionException
     */
    public function checkRole(string $role): void
    {
        $availableRoles = [self::ROLE_PERFORMER, self::ROLE_CLIENT];

        if (!in_array($role, $availableRoles)) {
            throw new StatusActionException("Неизвестная роль: $role");
        }
    }

    /**
     * Возвращает действия, доступные для указанной роли
     * @param $role
     * @return array|string[]
     */
    private function roleAllowedActions($role): array
    {
        $map = [
            self::ROLE_CLIENT => [CancelAction::class, CompleteAction::class],
            self::ROLE_PERFORMER => [ResponseAction::class, DenyAction::class]
        ];

        return $map[$role] ?? [];
    }

    /**
     * Возвращает действия, доступные для указанного статуса
     * @param string $status
     * @return array|string[]
     */
    private function statusAllowedActions(string $status): array
    {
        $map = [
            self::STATUS_NEW => [CancelAction::class, ResponseAction::class, StartAction::class],
            self::STATUS_IN_PROGRESS => [DenyAction::class, CompleteAction::class],
        ];

        return $map[$status] ?? [];
    }
}