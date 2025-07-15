<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Account extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'type',
        'currency',
        'initial_balance',
    ];

    protected $casts = [
        'initial_balance' => 'float',
    ];
    
    protected static function booted(): void
    {
        static::addGlobalScope('user', function (Builder $builder) {
            if (Auth::check()) {
                $builder->where('user_id', Auth::id());
            }
        });

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->user_id = Auth::id();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function destinationTransactions()
    {
        return $this->hasMany(Transaction::class, 'destination_account_id');
    }
    
    public function getBalance()
    {
        $balance = $this->initial_balance;

        $incomes = Transaction::where('account_id', $this->id)->where('type', 'Income')->sum('amount');
        $expenses = Transaction::where('account_id', $this->id)->where('type', 'Expense')->sum('amount');
        $transfersOut = Transaction::where('account_id', $this->id)->where('type', 'Transfer')->sum('amount');
        $transfersIn = Transaction::where('destination_account_id', $this->id)->where('type', 'Transfer')->sum('amount');

        return $balance + $incomes - $expenses + $transfersIn - $transfersOut;
    }
}
