@extends('layouts.v1.app')


@section('content')
    <div class="w-full mx-auto sm:px-6 lg:px-2">

        <div class="overflow-hidden">
            <div class="px-6 mt-2">
                <div class="grid grid-cols-6 gap-6 mt-5">

                    <div class=" col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-gray-50 p-0 rounded-md border-2 ">
                        <div class="rounded-md mt-1">
                            <div class="px-3 py-2 border-b">
                                <h3 class="font-semibold text-lg ">{{ __('Calls (Grid)') }}</h3>
                                <p class="text-sm text-gray-400">
                                    {{ __('Activate the columns visible in the calls grid.') }}<br />
                                </p>
                            </div>
                            <div class="px-3 py-2 ml-2 mr-2">
                                <livewire:settings.calls.grid.columns />
                            </div>
                        </div>
                    </div>

                    <div x-data="{ activeTabe: '1' }"
                        class=" col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-gray-50 p-0 rounded-md border-2  ">
                        <div class="px-3 py-2 border-b ">
                            <h3 class="font-semibold text-lg ">{{ __('Calls (Detail)') }}</h3>
                            <p class="text-sm text-gray-400">
                                {{ __('Activate the information visible in the detail screen of each call.') }}
                            </p>
                        </div>
                        <!-- Panel header -->
                        <div
                            class="flex-shrink-0 bg-primary-50 dark:bg-primary-light border-b dark:border-primary dark:text-light dark:bg-dark">
                            <div class="flex items-center justify-between px-4 pt-2 ">
                                <div class="space-x-2 text-gray-700 lg:text-base md:text-base sm:text-xs font-medium">
                                    <button @click.prevent="activeTabe = '1'"
                                        class="px-px pb-4 focus:outline-none focus:ring-transparent"
                                        :class="{
                                            'border-b-2 border-primary-dark dark:border-b-2 dark:border-primary-light': activeTabe ==
                                                '1',
                                            'border-transparent': activeTabe != '1'
                                        }">
                                        <span class="text-gray-700 dark:text-light">{{ __('Visualisation') }}</span>
                                    </button>
                                    <button @click.prevent="activeTabe = '2'"
                                        class="px-px pb-4 focus:outline-none focus:ring-transparent"
                                        :class="{
                                            'border-b-2 border-primary-dark dark:border-b-2 dark:border-primary-light': activeTabe ==
                                                '2',
                                            'border-transparent': activeTabe != '2'
                                        }">
                                        <span class="text-gray-700 dark:text-light">{{ __('Création') }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Panel content (tabs) -->
                        <div
                            class="flex-1 pt-0 overflow-y-hidden hover:overflow-y-auto dark:text-light bg-gray-50 dark:bg-darker">

                            <!-- Visualisation tab -->
                            <div class="space-y-4 dark:text-light dark:bg-darker" x-show.transition.in="activeTabe == '1'">
                                <div class="px-3 py-2 ml-2 mr-2">
                                    <livewire:settings.calls.form.visualisation.columns />
                                </div>
                            </div>


                            <!-- Création tab -->
                            <div class="space-y-4 dark:text-light dark:bg-darker" x-show.transition.in="activeTabe == '2'">
                                <div class="px-3 py-2 ml-2 mr-2">
                                    <livewire:settings.calls.form.creation.columns />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-6 gap-6 mt-5">

                    <div class=" col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-gray-50 p-0 rounded-md border-2    ">
                        <div class="rounded-md mt-1">
                            <div class="px-3 py-2 border-b ">
                                <h3 class="font-semibold text-lg">{{ __('Others') }}</h3>
                                </p>
                            </div>
                            <div class="px-3 py-2 ml-2 mr-2 mt-10">
                                livewire:config.call-datax
                                livewire:config.call-sub-total
                                livewire:config.call-show-archive
                            </div>
                        </div>
                    </div>

                    <div class=" col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-gray-50 p-0 rounded-md border-2    ">
                        <div class="rounded-md mt-1">
                            <div class="px-3 py-2 border-b ">
                                <h3 class="font-semibold text-lg">{{ __('Products and Interventions') }}</h3>
                                <p class="text-sm text-gray-400">
                                    {{ __('Activate the columns visible in the equipment grid.') }}<br />
                                </p>
                            </div>
                            <div class="px-3 py-2 ml-2 mr-2">
                                livewire:config.equipment-columns
                            </div>
                        </div>
                    </div>

                </div>



            </div>
        </div>
    </div>
@endsection
