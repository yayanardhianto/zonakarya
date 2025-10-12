<?php

namespace App\Enums;

enum OrderStatus: string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case PROCESS = 'process';
    case REJECTED = 'rejected';
    case REFUND = 'refund';
    case COMPLETED = 'success';

    public static function getClass(string $status): string
    {
        return match ($status) {
            self::DRAFT->value => 'draft',
            self::PENDING->value => 'pending',
            self::PROCESS->value => 'info',
            self::REJECTED->value => 'reject',
            self::COMPLETED->value => 'success',
            self::REFUND->value => 'refund',
        };
    }

    public static function getLabel(string $status): string
    {
        $labels = self::getAll();

        return $labels[$status] ?? '';
    }

    public static function getAll(): array
    {
        return [self::DRAFT->value => __('Draft'),self::PENDING->value => __('Pending'), self::PROCESS->value => __('Process'), self::REJECTED->value => __('Rejected'), self::REFUND->value => __('Refund'), self::COMPLETED->value => __('Success')];
    }

    public static function getColor(string $status): string
    {
        $colors = [self::PENDING->value => 'warning', self::PROCESS->value => 'primary', self::REJECTED->value => 'danger', self::REFUND->value => 'info', self::COMPLETED->value => 'success'];

        return $colors[$status] ?? 'secondary';
    }
}
