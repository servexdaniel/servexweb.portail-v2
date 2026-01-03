@extends('layouts.v1.app')

@section('content')
    <div class="py-2">
        <div class="w-full sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div>
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900">
                                {{ __('Call Columns') }}
                            </h2>

                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Manage the columns displayed in call records.') }}
                            </p>
                        </header>

                        <div class="p-6 text-gray-900">
                            <livewire:settings.calls.grid.columns />
                        </div>


                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
