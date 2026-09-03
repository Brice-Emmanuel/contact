<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birthdate',
        'last_birthday_wish_year',
        'plan',                     // 'free', 'premium_500', 'unlimited'
        'contact_limit',            // 10, 100, 500 ou -1 pour illimité
        'subscription_expires_at',  // Date d'expiration
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'birthdate' => 'date',
        'subscription_expires_at' => 'datetime',
    ];

    /**
     * Valeurs par défaut des attributs lors de la création d'un utilisateur.
     *
     * @var array
     */
    protected $attributes = [
        'plan' => 'free',
        'contact_limit' => 10, // Quota par défaut pour le plan gratuit
    ];

    /**
     * Relation avec les contacts enregistrés par cet utilisateur.
     */
    public function contacts()
    {
        return $this->hasMany(Contact::class, 'user_id');
    }

    /**
     * Calcule la limite de contacts effective de l'utilisateur
     * (prend en compte l'expiration éventuelle de l'abonnement).
     *
     * @return int
     */
    public function getEffectiveContactLimit(): int
    {
        // Si l'abonnement payant est expiré, retour au quota gratuit (ex: 10)
        if ($this->subscription_expires_at && $this->subscription_expires_at->isPast()) {
            return 10;
        }

        // Si contact_limit vaut NULL en BDD, on applique la valeur par défaut (10)
        return $this->contact_limit ?? 10;
    }

    /**
     * Vérifie si l'utilisateur peut encore ajouter un contact selon son quota.
     *
     * @return bool
     */
    public function canAddContact(): bool
    {
        $limit = $this->getEffectiveContactLimit();

        // -1 représente l'accès illimité
        if ($limit === -1) {
            return true;
        }

        return $this->contacts()->count() < $limit;
    }
}