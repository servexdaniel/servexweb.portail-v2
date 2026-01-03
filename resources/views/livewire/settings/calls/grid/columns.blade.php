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
