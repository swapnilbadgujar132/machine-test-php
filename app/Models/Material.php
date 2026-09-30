<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'opening_balance',
    ];

    /**
     * A material belongs to a category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(MaterialCategory::class);
    }

    /**
     * A material has many stock movements.
     */
    public function movements(): HasMany
    {
        return $this->hasMany(MaterialMovement::class);
    }

    /**
     * Current balance = opening balance +/- all inward/outward quantities.
     */
    public function getCurrentBalanceAttribute(): float
    {
        $totalMovement = $this->getAttribute('movements_sum_quantity')
            ?? $this->movements()->sum('quantity');

        return (float) $this->opening_balance + (float) $totalMovement;
    }
}
