<x-filament-panels::page>
    {{ $this->form }}

    <x-filament-actions::actions
        :actions="$this->getFormActions()"
        alignment="left"
    />
</x-filament-panels::page>