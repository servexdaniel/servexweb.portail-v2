<div class="dark:text-light dark:bg-darker">
    {{-- <x-loader /> --}}
    <div x-data="dataTableCall()" x-init="initCallData()
    window.addEventListener('updatecalls', (event) => {
        refreshCallsTable(event.detail);
    });
    window.addEventListener('refetch', (event) => {
        Livewire.emitTo('grids.calls', 'refetch');
    });
    $watch('searchInput', value => {
        search(value)
    })" x-cloak>

        <div class="p-0">

            {{-- <div class="p-2 w-full flex justify-end rounded-md bg-gray-50 dark:bg-darker shadow" >
                <div  class="mx-1 " wire:click="refreshCalls">
                    <div wire:loading wire:target="refreshCalls" class="text-xs text-servex-light opacity-80">
                        {{ __('Loading.....')}}
                        <i class="fas fa-spinner text-servex-light animate-spin" wire.target="refreshCalls"></i>
                    </div>
                    <i wire:loading.remove class="fas fa-refresh text-servex-light ml-4 cursor-pointer opacity-80" wire.target="refreshCalls" wire:loading.class="animate-spin" ></i>
                </div>
                <div class="mx-1"><a href="javascript:void(0);" class="full-screen"><i class="icon-size-fullscreen text-servex-light "></i></a></div>
            </div> --}}

            <!-- Main Content -->
            <div class="grid grid-cols-1 gap-1 my-2 ">
                <div
                    class="flex items-center justify-center w-full h-auto rounded-md dark:bg-darker dark:text-primary-light">
                    <div
                        class="-mx-2 sm:-mx-4 sm:px-0 p-0 overflow-x-auto min-h-screen w-full rounded-md border-2 dark:border-primary-light bg-gray-50 dark:bg-darker">

                        <table class="table md:table-sm sm:table-sm w-full lg:text-base md:text-base sm:text-xs ">
                            <thead>
                                <tr class="bg-transparent">
                                    <th :colspan="countHeadings()"
                                        class="px-4 py-3 text-left font-normal tracking-wider">


                                        <div class="col-span-2 dark:text-light dark:bg-darker">
                                            <div class="flex items-center justify-start ">

                                                <div>
                                                    <button onclick="openCall()">
                                                        @slot('svg')
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-6 w-6 mr-1 text-servex-light" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        @endslot
                                                        <span class="text-servex-light">
                                                            {{ __('MenuNewCall') }}
                                                        </span>
                                                    </button>
                                                </div>

                                                <div
                                                    class="search-box border-2 border-gray-200 dark:border-primary-dark">
                                                    <button
                                                        class="btn-search focus:outline-none focus:ring-transparent ">
                                                        <i
                                                            class="fas fa-search text-servex-lighter dark:text-servex-light border-servex "></i>
                                                    </button>
                                                    <input x-model="searchInput" type="text"
                                                        class="input-search text-servex-lighter dark:text-servex-light focus:outline-none focus:ring-transparent "
                                                        placeholder="{{ __('Search') }}...">
                                                </div>

                                                <div>
                                                    <select x-model="view" @change="changeView()"
                                                        class=" mx-2 text-servex-lighter dark:text-servex-light font-normal appearance-none rounded-lg focus:outline-none focus:shadow-outline border border-primary-dark dark:border-primary-dark bg-primary-50 focus:bg-primary-50 focus:ring-transparent">
                                                        <option value="15">15</option>
                                                        <option value="25">25</option>
                                                        <option value="50">50</option>
                                                        <option value="100">100</option>
                                                    </select>
                                                </div>

                                                {{-- Filtres sur les CPA --}}
                                                @unless (request()->routeIs('contact.products'))
                                                    {{-- <div x-show="list_cpa.length > 0" >
                                                    <div class="relative inline-flex self-center">
                                                        <select
                                                                x-model="currentCpaFilter" @change="changeCpaFilter()"
                                                                class="w-60 my-2 mx-2 text-servex-lighter dark:text-servex-light font-normal appearance-none rounded-lg focus:outline-none focus:shadow-outline border border-primary-dark dark:border-primary-dark bg-primary-50 focus:bg-primary-50 focus:ring-transparent">
                                                            <option value="">{{__('All') }}</option>
                                                            <template x-for="cpa in list_cpa">
                                                                <option
                                                                    :value="cpa.CpTitle"
                                                                >
                                                                    <span x-text="cpa.CpTitle" class="text-left"></span>
                                                                </option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                </div> --}}
                                                @endunless

                                                {{-- Si la route est autre que la route pour l'historique des appels, on affiche lees filtres sur les périodes --}}
                                                @unless (request()->routeIs('contact.products'))
                                                    <div>
                                                        <div class="relative inline-flex self-center">
                                                            <select x-model="currentFilter" @change="changeFilter()"
                                                                class="w-60 my-2  mx-2 text-servex-lighter dark:text-servex-light font-normal appearance-none rounded-lg focus:outline-none focus:shadow-outline border border-primary-dark dark:border-primary-dark bg-primary-50 focus:bg-primary-50 focus:ring-transparent">
                                                                <option value="">{{ __('All') }}</option>
                                                                <option value="today">{{ __('Today') }}</option>
                                                                <option value="Current week">{{ __('Current week') }}
                                                                </option>
                                                                <option value="Past 7 days">{{ __('Past 7 days') }}</option>
                                                                <option value="This Month">{{ __('This Month') }}</option>
                                                                <option value="This year">{{ __('This year') }}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                @endunless

                                                <div>
                                                    <div class="my-2">
                                                        <div class="shadow rounded-lg flex">
                                                            <div class="relative">
                                                                <button
                                                                    class="rounded-lg inline-flex items-center bg-primary-50 hover:text-blue-500 focus:outline-none focus:shadow-outline text-gray-500 font-normal py-2 px-2 md:px-4 border border-primary-light dark:border-primary-dark focus:bg-primary-100"
                                                                    @click.prevent="open = !open">
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="w-6 h-6 md:hidden text-servex-lighter dark:text-servex-light"
                                                                        viewBox="0 0 24 24" stroke-width="2"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <rect x="0" y="0" width="24" height="24"
                                                                            stroke="none"></rect>
                                                                        <path
                                                                            d="M5.5 5h13a1 1 0 0 1 0.5 1.5L14 12L14 19L10 16L10 12L5 6.5a1 1 0 0 1 0.5 -1.5" />
                                                                    </svg>
                                                                    <span
                                                                        class="hidden md:block text-servex-lighter dark:text-servex-light font-normal">{{ __('Display') }}</span>
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="w-5 h-5 ml-1 text-servex-lighter dark:text-servex-light"
                                                                        width="24" height="24"
                                                                        viewBox="0 0 24 24" stroke-width="2"
                                                                        stroke="currentColor" fill="none"
                                                                        stroke-linecap="round" stroke-linejoin="round">
                                                                        <rect x="0" y="0" width="24" height="24"
                                                                            stroke="none"></rect>
                                                                        <polyline points="6 9 12 15 18 9" />
                                                                    </svg>
                                                                </button>

                                                                <div x-show="open" @click.away="open = false" x-cloak
                                                                    class="absolute right-0 mt-2 w-64 bg-white dark:bg-darker border border-gray-200 dark:border-primary-darker rounded-lg shadow-xl overflow-y-auto max-h-96 z-50">
                                                                    <template x-for="heading in headings">
                                                                        <label
                                                                            class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-primary-darker cursor-pointer">
                                                                            <input type="checkbox"
                                                                                :checked="visibleColumns[heading.name]"
                                                                                @click="toggleColumn(heading.name)"
                                                                                class="mr-3 rounded">
                                                                            <span x-text="heading.title"
                                                                                class="text-sm"></span>
                                                                        </label>
                                                                    </template>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                {{-- <div class="flex items-center space-x-2">
                                                    <div wire:click="refreshCalls"
                                                        class="w-auto px-2 h-auto inline-flex flex items-center " >
                                                        <div wire:loading wire:target="refreshCalls" class="text-xs main-text-color dark:text-servex-light opacity-50">{{ __('Loading.....')}}</div>
                                                        <i class="fas fa-sync fa-lg cursor-pointer ml-2 main-text-color dark:text-servex-light opacity-50" wire.target="refreshCalls" wire:loading.class="animate-spin"></i>
                                                    </div>
                                                </div> --}}

                                            </div>
                                        </div>


                                    </th>
                                </tr>
                                <tr class="bg-primary-50">
                                    <th
                                        class="px-2 py-2 border-b-2 dark:border-primary-light text-left font-extralight tracking-tighter default-width">
                                        <span>{{ __('Photos') }}</span>
                                    </th>
                                    <template x-for="heading in headings" :key="heading.name">
                                        <th class="px-2 py-2 border-b-2 dark:border-primary-light text-left font-extralight tracking-tighter default-width"
                                            :x-ref="heading.name"
                                            :class="{
                                                [heading.name]: true,
                                                'hidden': !visibleColumns[heading.name]
                                            }">
                                            <div class="flex items-center justify-left justify-between space-x-0">
                                                <div><span x-text="heading.title"
                                                        class="text-left whitespace-nowrap"></span></div>
                                                <div>
                                                    <div class="flex flex-col items-start px-2">
                                                        <svg @click="sort(heading.name, 'asc')" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" viewBox="0 0 24 24" stroke="currentColor"
                                                            class="h-3 w-3 cursor-pointer text-gray-500 "
                                                            x-bind:class="{
                                                                'text-servex': sorted.field === heading.name && sorted
                                                                    .rule === 'asc'
                                                            }">
                                                            <path d="M5 15l7-7 7 7"></path>
                                                        </svg>
                                                        <svg @click="sort(heading.name, 'desc')" fill="none"
                                                            stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" viewBox="0 0 24 24"
                                                            stroke="currentColor"
                                                            class="h-3 w-3 cursor-pointer text-gray-500 "
                                                            x-bind:class="{
                                                                'text-servex': sorted.field === heading.name && sorted
                                                                    .rule === 'desc'
                                                            }">
                                                            <path d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                            </div>
                                        </th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody class="dark:text-light dark:bg-darker">
                                <template x-for="(item, index) in items" :key="index">
                                    <tr x-show="checkView(index + 1)"
                                        class="font-extralight             hover:text-primary hover:bg-gray-100 dark:hover:text-light dark:hover:bg-primary-darker dark:bg-darker focus:outline-none focus:bg-gray-100 dark:focus:bg-primary-dark focus:ring-primary-darker  dark:text-white">
                                        <td class="align-middle">
                                            <span @click.prevent="showImages(item.CaNumber)"
                                                class="w-10/12 lg:text-left pr-2 whitespace-nowrap font-extralight cursor-pointer underline text-blue-600">Photos</span>
                                        </td>
                                        <template x-for="col in columns" :key="col.name">
                                            <td x-bind:class="{
                                                'td-texte-long': col.name === 'CaProblem',
                                                [col.name]: true,
                                                'hidden': !visibleColumns[col.name]
                                            }"
                                                class="align-middle">
                                                <template x-if="col.name == 'CpTitle'">
                                                    <span
                                                        class="px-2 py-1 font-extralight leading-tight rounded-lg mx-0 "
                                                        :style="backgroundcolor(item)">
                                                        <span class="whitespace-nowrap"
                                                            x-html="getColumnData(item,col)"></span>
                                                    </span>
                                                </template>
                                                <template x-if="col.name == 'CaNumber'">
                                                    <span @click.prevent="startEdit(item.CaNumber)"
                                                        x-html="getColumnData(item,col)"
                                                        x-bind:class="{ 'cursor-pointer underline': col.name === 'CaNumber' }"
                                                        class="w-10/12 lg:text-left pr-2 whitespace-nowrap font-extralight "></span>
                                                </template>
                                                <template
                                                    x-if="col.name == 'CaProblem' && getColumnData(item,col) !=='' ">
                                                    <pre x-text="getColumnData(item,col)"></pre>
                                                </template>
                                                <template
                                                    x-if="col.name != 'CaNumber' && col.name != 'CpTitle' && col.name != 'CaProblem'">
                                                    <span x-html="getColumnData(item,col)"
                                                        class="w-10/12 lg:text-left pr-2 whitespace-nowrap"></span>
                                                </template>
                                            </td>
                                        </template>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        {{-- @include('vendor.pagination.custom-pagination') --}}
                        <div x-show="checkEmptyDataSet()" class="p-4">
                            <p class="text-center">{{ __('No data to display') }}</p>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

    <script>
        window.addEventListener('openModalEvent', function(ev) {
            $(".loader").fadeOut("slow");
        });
    </script>

    <script>
        function openCall() {
            //Ouvrir le modal
            Livewire.emitTo('modal.call.new-call', 'showCallModal');
        };
    </script>


    <script>
        window.dataTableCall = function() {

            return {
                headings: [],
                isEmptyDataSet: false,
                data: @entangle('calls'),
                cols: [],
                list_cpa: [],
                columns: [],
                calls: [],
                items: [],
                visibleColumns: {},
                view: 15,
                searchInput: '',
                currentFilter: '',
                currentCpaFilter: '',
                pages: [],
                offset: 15,
                pagination: {
                    total: 0,
                    lastPage: 1,
                    perPage: 15,
                    currentPage: 1,
                    from: 1,
                    to: 1 * 15
                },
                currentPage: 1,
                sorted: {
                    field: 'CaNumber',
                    rule: 'asc'
                },
                open: false,
                initCallData() {
                    this.build(this.data.calls, this.data.columns, this.data.list_cpa, this.data.total);

                    // Initialiser toutes les colonnes comme visibles
                    this.headings.forEach(heading => {
                        this.visibleColumns[heading.name] = true;
                    });
                },
                refreshCallsTable(data) {
                    this.build(data.calls, data.columns, data.list_cpa, data.total);
                },
                build(calls, columns, listcpa, counttotal) {
                    var columnsArray = [];
                    var cols = columns;
                    this.list_cpa = listcpa;

                    const arr = cols;
                    arr.forEach(function(item) {
                        if (item.display_in_grid) {
                            var col = {
                                name: item.key,
                                title: item.title,
                                type: "text"
                            };
                            columnsArray.push(col);
                        }
                    });

                    this.headings = columnsArray;

                    this.cols = columnsArray;
                    this.columns = columnsArray;
                    this.calls = calls;

                    this.items = this.calls.sort(this.compareOnKey('CaCallDate', 'desc'));
                    this.pagination.total = counttotal;
                    this.pagination.perpage = 15;
                    this.pagination.lastPage = Math.ceil(this.pagination.total / this.pagination.perpage);

                    this.isEmptyDataSet = this.pagination.total > 0 ? false : true;
                    this.showPages();
                },
                compareOnKey(key, rule) {
                    return function(a, b) {
                        //if (key === 'name' || key === 'job' || key === 'email' || key === 'country') {
                        let comparison = 0
                        const fieldA = a[key].toUpperCase()
                        const fieldB = b[key].toUpperCase()
                        if (rule === 'asc') {
                            if (fieldA > fieldB) {
                                comparison = 1;
                            } else if (fieldA < fieldB) {
                                comparison = -1;
                            }
                        } else {
                            if (fieldA < fieldB) {
                                comparison = 1;
                            } else if (fieldA > fieldB) {
                                comparison = -1;
                            }
                        }
                        return comparison
                        /*} else {
                            if (rule === 'asc') {
                                return a.year - b.year
                            } else {
                                return b.year - a.year
                            }
                        }*/
                    }
                },
                checkView(index) {
                    return index > this.pagination.to || index < this.pagination.from ? false : true
                },
                checkPage(item) {
                    if (item <= this.currentPage + 5) {
                        return true
                    }
                    return false
                },
                search(value) {
                    if (value.length > 1) {
                        const options = {
                            shouldSort: true,
                            keys: this.cols,
                            threshold: 0.2
                        }
                        const fuse = new Fuse(this.calls, options)
                        this.items = fuse.search(value).map(elem => elem.item)
                    } else {
                        this.items = this.calls
                    }

                    this.changePage(1)
                    this.showPages()
                },
                sort(field, rule) {
                    this.items = this.items.sort(this.compareOnKey(field, rule))
                    this.sorted.field = field
                    this.sorted.rule = rule
                },
                changePage(page) {
                    if (page >= 1 && page <= this.pagination.lastPage) {
                        this.currentPage = page
                        const total = this.items.length
                        const lastPage = Math.ceil(total / this.view) || 1
                        const from = (page - 1) * this.view + 1
                        let to = page * this.view
                        if (page === lastPage) {
                            to = total
                        }
                        this.pagination.total = total
                        this.pagination.lastPage = lastPage
                        this.pagination.perPage = this.view
                        this.pagination.currentPage = page
                        this.pagination.from = from
                        this.pagination.to = to
                        this.showPages()
                    }
                },
                showPages() {
                    const pages = []
                    let from = this.pagination.currentPage - Math.ceil(this.offset / 2)
                    if (from < 1) {
                        from = 1
                    }
                    let to = from + this.offset - 1
                    if (to > this.pagination.lastPage) {
                        to = this.pagination.lastPage
                    }
                    while (from <= to) {
                        pages.push(from)
                        from++
                    }
                    this.pages = pages
                },
                changeView() {
                    this.changePage(1)
                    this.showPages()
                },
                isEmpty() {
                    return this.pagination.total ? false : true
                },
                countHeadings() {
                    return this.headings.length;
                },
                hidePagination() {
                    return this.pagination.lastPage <= 1 ? false : true
                },
                checkEmptyDataSet() {
                    return this.isEmptyDataSet;
                },
                startEdit(CaNumber) {
                    Livewire.emitTo('modal.call.edit-call', 'editCallModal', CaNumber, false);
                },
                showImages(CaNumber) {
                    Livewire.emitTo('modal.call.images', 'showCallImagesModal', CaNumber);
                },
                getColumnData(call, column) {
                    data = call[column.name];
                    return data;
                },
                truncate(call, column, max) {
                    const text = call[column.name].toString().trim();
                    return text.substr(0, max - 1) + (text.length > max ? ' ...' : '');
                },
                backgroundcolor(item) {
                    const colors = item.CPColor.split("þ");
                    const bgColor = colors[0];
                    const textColor = colors[1];
                    return 'background-color:' + bgColor + '; color:' + textColor + '; opacity: 0.75;';
                },

                toggleColumn(key) {
                    this.visibleColumns[key] = !this.visibleColumns[key];
                    // Note: All td must have the same class name as the headings key!
                    let columns = document.querySelectorAll('.' + key);

                    if (this.$refs[key].classList.contains('hidden') && this.$refs[key].classList.contains(key)) {
                        columns.forEach(column => {
                            column.classList.remove('hidden');
                        });
                    } else {
                        columns.forEach(column => {
                            column.classList.add('hidden');
                        });
                    }
                },

                changeCpaFilter() {
                    cpaFilter = this.currentCpaFilter;
                    if (cpaFilter != '') {
                        const options = {
                            shouldSort: true,
                            keys: ['CpTitle'],
                            threshold: 0.2
                        }
                        const fuse = new Fuse(this.calls, options)
                        this.items = fuse.search(cpaFilter).map(elem => elem.item)
                    } else {
                        this.items = this.calls;
                    }
                    this.changePage(1)
                    this.showPages()
                },

                changeFilter() {
                    $(".loader").fadeIn("slow");
                    let fromdate = "";
                    let todate = "";

                    switch (this.currentFilter) {
                        case "today":
                            today = new Date();
                            month = today.getMonth();
                            year = today.getFullYear();

                            fromdate = new Date(today).setHours(0, 0, 0, 0);
                            todate = new Date(today).setHours(23, 59, 59, 0);

                            break;
                        case "Current week":
                            today = new Date();

                            fromdate = new Date(today);
                            fromdate.setDate(fromdate.getDate() - fromdate.getDay() + 0);

                            todate = new Date(today);
                            todate.setDate(todate.getDate() - todate.getDay() + 6);

                            fromdate = fromdate.setHours(0, 0, 0, 0)
                            todate = todate.setHours(23, 59, 59, 0);

                            break;
                        case "Past 7 days":
                            today = new Date();
                            let lastWeek = new Date(today.getFullYear(), today.getMonth(), today.getDate() - 8);
                            let lastWeekMonth = lastWeek.getMonth();
                            let lastWeekDay = lastWeek.getDate();
                            let lastWeekYear = lastWeek.getFullYear();

                            fromdate = new Date(lastWeekYear, lastWeekMonth, lastWeek.getDate()).valueOf();
                            todate = new Date().setHours(23, 59, 59, 0);

                            break;
                        case "This Month":
                            today = new Date();
                            fromdate = new Date(today.getFullYear(), today.getMonth(), 1).setHours(0, 0, 0, 0);
                            todate = new Date(today.getFullYear(), today.getMonth() + 1, 0).setHours(23, 59, 59, 0);

                            break;
                        case "This year":
                            today = new Date();

                            fromdate = new Date(today.getFullYear(), 0, 1).setHours(0, 0, 0, 0);
                            todate = new Date(today.getFullYear(), 12, 0).setHours(23, 59, 59, 0);

                            break;
                        default:

                            break;
                    }

                    this.search(this.searchInput);

                    if (fromdate != '' && todate != '') {
                        list = this.items.filter(function(e) {
                            let cDate = new Date(e.CaCallDate).valueOf();
                            if ((cDate <= todate && cDate >= fromdate)) {
                                return e
                            }
                        });

                    } else {
                        list = this.items
                    }

                    this.items = list
                    this.changeView();
                    $(".loader").fadeOut("slow");

                },


            }
        }
    </script>

    <style scoped>
        .default-width {
            min-width: 180px;
            min-width: 180px !important;
            max-width: 180px !important;
            /* Largeur par défaut pour toutes les colonnes */
            vertical-align: middle;
        }

        .CaNumber {
            width: 100px !important;
            min-width: 100px !important;
            max-width: 100px !important;
        }

        .CpTitle {
            width: 260px !important;
            min-width: 260px !important;
            max-width: 260px !important;
        }

        .CaCallDate {
            width: 180px !important;
            min-width: 180px !important;
            max-width: 180px !important;
        }

        .CaContractNumber {
            width: 100px !important;
            min-width: 100px !important;
            max-width: 100px !important;
        }

        /* Classe à appliquer uniquement aux cellules avec du texte long */
        td.td-texte-long {
            width: auto !important;
            min-width: 400px !important;

            /* Gestion du retour à la ligne */
            word-break: break-word;
            /* Cassage des mots longs (compatibilité ancienne) */
            overflow-wrap: break-word;
            /* Standard moderne – équivalent à word-break: break-word */
            word-wrap: break-word;
            /* Ancienne propriété pour IE */

            /* Sécurité absolue contre le débordement horizontal */
            overflow: hidden;
            /* Cache tout contenu qui dépasserait */
            text-overflow: ellipsis;
            /* Optionnel : ajoute "..." si le texte déborde (utile avec truncate) */

            white-space: normal;
            /* Autorise le retour à la ligne (par défaut, mais utile à préciser) */

            vertical-align: top;
            padding: 12px !important;
            /*font-size: 0.875rem;*/
            line-height: 1.6;
        }

        td.td-texte-long pre {
            white-space: pre-wrap;
            /* Préserve les espaces et retours à la ligne, mais autorise le wrap */
            word-break: break-word;
            /* Cassage des mots très longs */
            overflow-wrap: anywhere;
            /* Cassage agressif si nécessaire */
            overflow: hidden;
            /* Sécurité absolue contre le débordement */
            margin: 0;
            /* Évite les marges par défaut de <pre> */
            font-family: inherit;
            /* Utilise la police du tableau (souvent plus lisible) */
            font-size: inherit;
        }
    </style>
</div>
