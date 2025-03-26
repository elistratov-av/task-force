<?php

namespace taskforce\logic;

class AvailableActions
{
    const STATUS_NEW = 'new';
    const STATUS_CANCEL = 'cancel';
    const STATUS_IN_PROGRESS = 'proceed';
    const STATUS_COMPLETE = 'complete';
    const STATUS_EXPIRED = 'expired';

    const ACTION_RESPONSE = 'act_response';
    const ACTION_CANCEL = 'act_cancel';
    const ACTION_DENY = 'act_deny';
    const ACTION_COMPLETE = 'act_complete';
    const ACTION_START = 'act_start';

    private string $status;

    private int $clientId;
    private ?int $performerId;

    /**
     * @param string $status
     * @param int $clientId
     * @param int|null $performerId
     */
    public function __construct(string $status, int $clientId, ?int $performerId = null)
    {
        $this->status = $status;
        $this->clientId = $clientId;
        $this->performerId = $performerId;
    }

    /**
     * @param string $status
     * @return void
     */
    private function setStatus(string $status): void
    {
        $availableStatus = [self::STATUS_NEW, self::STATUS_CANCEL, self::STATUS_IN_PROGRESS, self::STATUS_COMPLETE, self::STATUS_EXPIRED];
        if (in_array($status, $availableStatus)) {
            $this->status = $status;
        }
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
     * Возвращает «карту» действий
     * @return string[]
     */
    public function getActionsMap(): array
    {
        return [
            self::ACTION_RESPONSE => "Откликнуться",
            self::ACTION_CANCEL => "Отменить",
            self::ACTION_DENY => "Отказаться",
            self::ACTION_COMPLETE => "Выполнено",
            self::ACTION_START => "Принять",
        ];
    }

    /**
     * Возвращает статус, в которой перейдёт после выполнения указанного действия
     * @param string $action
     * @return string|null
     */
    public function getNextStatus(string $action): ?string
    {
        $map = [
            self::ACTION_COMPLETE => self::STATUS_COMPLETE,
            self::ACTION_CANCEL => self::STATUS_CANCEL,
            self::ACTION_DENY => self::STATUS_EXPIRED,
            self::ACTION_START => self::STATUS_IN_PROGRESS,
        ];

        return $map[$action] ?? null;
    }

    /**
     * Возвращает действия, доступные для указанного статуса
     * @param string $status
     * @return array|string[]
     */
    public function statusAllowedActions(string $status): array
    {
        $map = [
            self::STATUS_NEW => [self::ACTION_CANCEL, self::ACTION_RESPONSE, self::ACTION_START],
            self::STATUS_IN_PROGRESS => [self::ACTION_DENY, self::ACTION_COMPLETE],
        ];

        return $map[$status] ?? [];
    }
}