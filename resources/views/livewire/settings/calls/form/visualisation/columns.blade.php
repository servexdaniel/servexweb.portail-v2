<div>
    <div>
        @if (!$isAllMandatoryColumnsActive && count($columns) > 0)
            <div class="alert alert-warning" role="alert">
                <strong>Warning!</strong> Some mandatory columns are deactivated. Please activate all mandatory columns
                to
                ensure proper functionality.
                <button type="submit" wire:click="enableAllMandatoryColumns()"
                    class="px-4 py-2 rounded-md bg-green-500 text-white ml-4">
                    Enable all mandatory columns
                </button>
            </div>
        @endif
        @if (count($columns) > 0)
            <div class="mr-10 pr-18 py-2 dark:bg-darker flex items-center justify-end" x-data="{
                isOn: @entangle('selectall')
            }">
                <div class="flex items-center space-x-2">
                    <div wire:loading class="text-xs text-servex opacity-80">
                        <i class="fas fa-spinner text-servex" wire:loading.class="animate-spin"></i>
                    </div>


                    <span class="text-sm text-gray-500 dark:text-light">{{ __('All') }}</span>
                    <button class="relative focus:outline-none" x-cloak @click="isOn = !isOn; $wire.isOn = isOn"
                        wire:click="handleAllTrigger">
                        <div class="w-12 h-6 transition rounded-full outline-none bg-primary-100  "
                            :class="{ 'bg-gray-300 darK:bg-gray-300': !isOn, 'bg-primary-light dark:bg-primary-lighter': isOn }">
                        </div>
                        <div class="absolute top-0 left-0 inline-flex items-center justify-center w-6 h-6 transition-all duration-200 ease-in-out transform scale-110 rounded-full shadow-sm"
                            :class="{
                                'translate-x-0 border-2 border-primary bg-white dark:bg-primary-100': !
                                    isOn,
                                'translate-x-6 border-2 border-primary-dark bg-white dark:bg-primary': isOn
                            }">
                        </div>
                    </button>
                </div>
            </div>
        @else
            <div class="text-center p-4">
                <p class="text-gray-600">No columns available.</p>
            </div>
        @endif
        <div class="space-y-2 w-full overflow-auto h-96">
            @foreach ($columns as $column)
                <div
                    class="flex items-center space-x-2 p-2 border rounded-md {{ $column->visible ? 'bg-green-50' : 'bg-red-50' }}">
                    <div class="flex-1">
                        <h3 class="text-md font-semibold">{{ $column->column }}</h3>
                        <p class="text-sm text-gray-600">{{ $column->description }}</p>
                        @if ($column->ismandatory)
                            <span class="text-xs text-yellow-800 bg-yellow-200 px-2 py-1 rounded-full">Mandatory</span>
                        @endif
                    </div>
                    <div>
                        <button type="submit" wire:click="toggleColumn({{ $column->id }})"
                            class="px-4 py-2 rounded-md {{ $column->visible ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}">
                            {{ $column->visible ? 'Deactivate' : 'Activate' }}
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
