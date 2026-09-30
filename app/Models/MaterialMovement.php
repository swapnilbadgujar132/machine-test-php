<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialMovement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'material_id',
        'movement_date',
        'quantity',
    ];

    /**
     * A movement belongs to one material.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    protected function casts(): array
    {
        return [
            'movement_date' => 'date',
            'quantity' => 'decimal:2',
        ];
    }
}
