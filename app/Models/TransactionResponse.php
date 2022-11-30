<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransactionResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_description_id',
        'xendit_id',
        'reference_id',
        'status'
    ];

    /**
     * Transaction response
     * relationship with order description.
     * 
     * @return BelongsTo
     */
    public function orederDescription() : BelongsTo
    {
        return $this->belongsTo(OrderDescription::class, 'order_description_id');
    }
}
