<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'contacts';

    protected $fillable = [
        'user_id',
        'name',
        'surname',
        'email',
        'adress',
        'phone',
        'group',
        'favoris',
        'Birthday',
        'notes',
    ];

    protected $casts = [
        'favoris' => 'boolean',
        'Birthday' => 'date',
    ];

    /**
     * Relation : Un contact appartient à un seul utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}