<?php

namespace App;

enum PaymentStatus: string
{
    //
    case Pending = 'pending';
    case Paid = 'paid';
    case Failed = 'failed';
    
    public function getColor(): string | array | null
    {
        return match ($this) {
            self::Pending => 'warning',
            self::Paid => 'success',
            self::Failed => 'danger',
        };
    }
}
