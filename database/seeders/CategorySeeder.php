<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $this->command->info('No user found, skipping category seeding.');
            return;
        }

        Category::where('user_id', $user->id)->delete();

        $this->command->info('Creating default categories...');

        $homeCategories = [
            'Rent / Mortgage', 'Electricity', 'Water', 'Internet & TV', 'Gas', 'Household Supplies', 'Home Maintenance & Repairs'
        ];

        $foodCategories = [
            'Groceries', 'Dining Out', 'Coffee & Snacks', 'Food Delivery'
        ];

        $transportCategories = [
            'Fuel', 'Ride-Hailing', 'Parking & Tolls', 'Public Transport', 'Vehicle Maintenance', 'Vehicle Insurance & Tax'
        ];

        $personalCategories = [
            'Shopping', 'Personal Care', 'Hobbies', 'Gaming', 'Vaping', 'Subscriptions', 'Education', 'Gifts & Donations'
        ];

        $healthCategories = [
            'Health Insurance', 'Doctor & Pharmacy', 'Vitamins & Supplements'
        ];

        $financialCategories = [
            'Bank Fees', 'Taxes', 'Loan Repayments'
        ];

        $incomeCategories = [
            'Salary', 'Bonus', 'Freelance / Side Project', 'Investment Income', 'Gifts Received', 'Other Income'
        ];

        $allCategories = [
            'Home' => $homeCategories,
            'Food' => $foodCategories,
            'Transport' => $transportCategories,
            'Personal' => $personalCategories,
            'Health' => $healthCategories,
            'Financial' => $financialCategories,
            'Income' => $incomeCategories
        ];

        foreach ($allCategories as $categories) {
            foreach ($categories as $categoryName) {
                Category::create([
                    'user_id' => $user->id,
                    'name' => $categoryName,
                ]);
            }
        }

        $this->command->info('Default categories created successfully.');
    }
}
