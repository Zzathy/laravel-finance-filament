<x-filament-widgets::widget>
    {{-- We have removed the <x-slot name="heading"> from here --}}
    <form wire:submit.prevent="create">
        {{ $this->form }}

        <div class="mt-6 flex justify-end">
            <x-filament::button type="submit">
                Add Expense
            </x-filament::button>
        </div>
    </form>
</x-filament-widgets::widget>