<?php

namespace App\Models\User;

use App\Utils\Tables\ETables;
use App\Utils\Tables\User\UserProfileColumn;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = ETables::USER_PROFILE->value;

    protected $fillable = [
        UserProfileColumn::USER_ID,
        UserProfileColumn::BIO,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
