<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class MonthlyTrendsChart extends ChartWidget
{
    protected static ?string $heading = 'Income vs. Expenses (Last 6 Months)';

    protected static ?int $sort = 3;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $incomeData = [];
        $expenseData = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthName = $month->format('M Y');
            $labels[] = $monthName;

            $incomeData[] = Transaction::query()
                ->where('type', 'Income')
                ->whereYear('transaction_date', $month->year)
                ->whereMonth('transaction_date', $month->month)
                ->sum('amount');

            $expenseData[] = Transaction::query()
                ->where('type', 'Expense')
                ->whereYear('transaction_date', $month->year)
                ->whereMonth('transaction_date', $month->month)
                ->sum('amount');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Income',
                    'data' => $incomeData,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.6)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                ],
                [
                    'label' => 'Expenses',
                    'data' => $expenseData,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.6)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                ],
            ],
            'labels' => $labels,
        ];
    }
}
