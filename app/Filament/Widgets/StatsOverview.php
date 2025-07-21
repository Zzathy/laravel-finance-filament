<?php

namespace App\Filament\Widgets;

use App\Models\Account;
use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $accounts = Account::all(); 
        $groupedAccounts = $accounts->groupBy('currency');

        $balanceStats = [];
        foreach ($groupedAccounts as $currency => $accountsInCurrency) {
            $total = $accountsInCurrency->map(fn(Account $account) => $account->getBalance())->sum();

            $balanceStats[] = Stat::make("Total Balance ({$currency})", number_format($total, 2))
                ->description('Net worth in ' . $currency);
        }

        $now = Carbon::now();

        $incomeThisMonth = Transaction::where('type', 'Income')
            ->whereYear('transaction_date', $now->year)
            ->whereMonth('transaction_date', $now->month)
            ->sum('amount');

        $expenseThisMonth = Transaction::where('type', 'Expense')
            ->whereYear('transaction_date', $now->year)
            ->whereMonth('transaction_date', $now->month)
            ->sum('amount');


        return array_merge($balanceStats, [
            Stat::make('Income (This Month)', 'Rp ' . number_format($incomeThisMonth))
                ->description('Total earnings this month')
                ->color('success'),
            Stat::make('Expenses (This Month)', 'Rp ' . number_format($expenseThisMonth))
                ->description('Total spending this month')
                ->color('danger'),
        ]);
    }
}
