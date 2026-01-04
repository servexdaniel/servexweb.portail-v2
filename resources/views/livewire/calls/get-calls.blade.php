<div class="min-h-screen bg-gray-50 dark:bg-darker dark:text-light">
    {{-- <x-loader /> --}}

    @dump($calls)


    <div x-data="dataTableCall()" x-init="initCallData();
    $watch('searchInput', value => search(value));
    window.addEventListener('updatecalls', e => refreshCallsTable(e.detail));
    window.addEventListener('refetch', () => Livewire.emitTo('grids.calls', 'refetch'));" x-cloak class="p-4">

        <!-- Toolbar principal -->
        <div
            class="mb-6 bg-white dark:bg-darker rounded-lg shadow-sm border border-gray-200 dark:border-primary-darker p-4">
            <div class="flex flex-wrap items-center gap-4">

                <pre x-html="JSON.stringify(items)"></pre>

                <!-- Bouton Nouveau appel -->
                <button onclick="openCall()" class="flex items-center gap-2">
                    @slot('svg')
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    @endslot
                    {{ __('MenuNewCall') }}
                </button>

                <!-- Recherche -->
                <div
                    class="flex items-center border-2 border-gray-300 dark:border-primary-dark rounded-lg overflow-hidden focus-within:ring-2 focus-within:ring-primary">
                    <button class="px-3 text-gray-500">
                        <i class="fas fa-search"></i>
                    </button>
                    <input x-model="searchInput" type="text" placeholder="{{ __('Search') }}..."
                        class="px-3 py-2 bg-transparent focus:outline-none w-64">
                </div>


                <!-- Nombre d'éléments par page -->
                <select x-model="view" @change="changeView()"
                    class="px-4 py-2 rounded-lg border border-gray-300 dark:border-primary-dark bg-white dark:bg-darker focus:ring-2 focus:ring-primary">
                    <option value="15">15</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                <!-- Filtre CPA (si pertinent) -->
                @unless (request()->routeIs('contact.products'))
                    {{-- <select x-model="currentCpaFilter" @change="changeCpaFilter()"
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-primary-dark bg-white dark:bg-darker focus:ring-2 focus:ring-primary">
                        <option value="">{{ __('All') }}</option>
                        <template x-for="cpa in list_cpa">
                            <option :value="cpa.CpTitle" x-text="cpa.CpTitle"></option>
                        </template>
                    </select> --}}
                    <select x-model="currentCpaFilter" @change="changeCpaFilter()"
                        class="w-60 my-2 mx-2 text-servex-lighter dark:text-servex-light font-normal appearance-none rounded-lg focus:outline-none focus:shadow-outline border border-primary-dark dark:border-primary-dark bg-primary-50 focus:bg-primary-50 focus:ring-transparent">
                        <option value="">{{ __('All') }}</option>
                        <template x-for="cpa in list_cpa">
                            <option :value="cpa.CpTitle">
                                <span x-text="cpa.CpTitle" class="text-left"></span>
                            </option>
                        </template>
                    </select>
                @endunless

                <!-- Filtre période -->
                @unless (request()->routeIs('contact.products'))
                    <select x-model="currentFilter" @change="changeFilter()"
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-primary-dark bg-white dark:bg-darker focus:ring-2 focus:ring-primary">
                        <option value="">{{ __('All') }}</option>
                        <option value="today">{{ __('Today') }}</option>
                        <option value="Current week">{{ __('Current week') }}</option>
                        <option value="Past 7 days">{{ __('Past 7 days') }}</option>
                        <option value="This Month">{{ __('This Month') }}</option>
                        <option value="This year">{{ __('This year') }}</option>
                    </select>
                @endunless

                <!-- Sélecteur de colonnes -->
                <div class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-2 px-4 py-2 bg-white dark:bg-darker border border-gray-300 dark:border-primary-dark rounded-lg hover:bg-gray-50 dark:hover:bg-primary-darker transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M5.5 5h13a1 1 0 0 1 0.5 1.5L14 12l0 7l-4-3v-4L5 6.5A1 1 0 0 1 5.5 5z" />
                        </svg>
                        <span>{{ __('Display') }}</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <polyline points="6 9 12 15 18 9" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-cloak
                        class="absolute right-0 mt-2 w-64 bg-white dark:bg-darker border border-gray-200 dark:border-primary-darker rounded-lg shadow-xl overflow-y-auto max-h-96 z-50">
                        <template x-for="heading in headings">
                            <label
                                class="flex items-center px-4 py-3 hover:bg-gray-50 dark:hover:bg-primary-darker cursor-pointer">
                                <input type="checkbox" checked @click="toggleColumn(heading.name)" class="mr-3 rounded">
                                <span x-text="heading.title" class="text-sm"></span>
                            </label>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tableau -->
        <div
            class="bg-white dark:bg-darker rounded-lg shadow-sm border border-gray-200 dark:border-primary-darker overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full table-auto">
                    <thead class="bg-gray-50 dark:bg-primary-darker">
                        <tr>
                            <th
                                class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-light uppercase tracking-wider">
                                Photos</th>
                            <template x-for="heading in headings" :key="heading.name">
                                <th :x-ref="heading.name"
                                    :class="{
                                        [heading.name]: true
                                    }"
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-light uppercase tracking-wider">
                                    <div class="flex items-center justify-between">
                                        <span x-text="heading.title"></span>
                                        <div class="flex flex-col ml-2">
                                            <svg @click="sort(heading.name, 'asc')" class="w-4 h-4 cursor-pointer"
                                                :class="{
                                                    'text-primary': sorted.field === heading.name && sorted
                                                        .rule === 'asc'
                                                }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M5 15l7-7 7 7" />
                                            </svg>
                                            <svg @click="sort(heading.name, 'desc')" class="w-4 h-4 cursor-pointer"
                                                :class="{
                                                    'text-primary': sorted.field === heading.name && sorted
                                                        .rule === 'desc'
                                                }"
                                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </th>
                            </template>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-primary-darker">
                        <template x-for="(item, index) in items" :key="index">
                            <tr x-show="checkView(index + 1)"
                                class="hover:bg-gray-50 dark:hover:bg-primary-darker transition">
                                <td class="px-4 py-3">
                                    <span @click.prevent="showImages(item.CaNumber)"
                                        class="text-blue-600 underline cursor-pointer hover:text-blue-800">Photos</span>
                                </td>
                                <template x-for="col in columns">
                                    {{-- <td :class="col.name" class="px-4 py-3 text-sm"> --}}
                                    <td x-bind:class="{
                                        'td-texte-long': col.name === 'CaProblem',
                                        [col.name]: true
                                    }"
                                        class="align-middle">
                                        <template x-if="col.name === 'CpTitle'">
                                            <span class="px-3 py-1 rounded-full text-xs font-medium"
                                                :style="backgroundcolor(item)"
                                                x-html="getColumnData(item, col)"></span>
                                        </template>
                                        <template x-if="col.name === 'CaNumber'">
                                            <span @click.prevent="startEdit(item.CaNumber)"
                                                class="cursor-pointer underline hover:text-primary"
                                                x-html="getColumnData(item, col)"></span>
                                        </template>
                                        <template x-if="col.name === 'CaProblem' && getColumnData(item, col)">
                                            <pre class="text-xs whitespace-pre-wrap" x-text="getColumnData(item, col)"></pre>
                                        </template>
                                        <template x-if="!['CaNumber', 'CpTitle', 'CaProblem'].includes(col.name)">
                                            <span x-html="getColumnData(item, col)"></span>
                                        </template>
                                    </td>
                                </template>
                            </tr>
                        </template>
                    </tbody>
                </table>

                <!-- Message vide -->
                <div x-show="checkEmptyDataSet()" class="p-8 text-center text-gray-500">
                    {{ __('No data to display') }}
                </div>
            </div>

            <!-- Pagination -->
            {{-- @include('vendor.pagination.custom-pagination') --}}
        </div>
    </div>

    <!-- Scripts Alpine.js (inchangés ou légèrement nettoyés) -->
    <script>
        function openCall() {
            Livewire.emitTo('modal.call.new-call', 'showCallModal');
        }

        window.dataTableCall = function() {
            return {
                // ... (toutes les propriétés et méthodes de votre script original, légèrement nettoyées si besoin)
                // Je conserve ici la logique exacte pour ne pas introduire de bugs.
                // Vous pouvez copier-coller votre script Alpine actuel à l'intérieur, il fonctionnera parfaitement avec la nouvelle structure.
                data: @entangle('calls'),
                headings: [],
                items: [],
                columns: [],
                calls: [],
                list_cpa: [],
                view: 15,
                searchInput: '',
                currentFilter: '',
                currentCpaFilter: '',
                sorted: {
                    field: 'CaNumber',
                    rule: 'asc'
                },
                open: false,
                pagination: {
                    total: 0,
                    lastPage: 1,
                    perPage: 15,
                    currentPage: 1,
                    from: 1,
                    to: 15
                },

                initCallData() {
                    this.build(this.data.calls, this.data.columns, this.data.list_cpa, this.data.total);
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

    <!-- Styles minimaux restants -->
    <style scoped>
        /* Conservation uniquement des styles vraiment nécessaires */
        pre {
            margin: 0;
            font-family: inherit;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>


    <style scoped>
        .default-width {
            min-width: 150px;
            min-width: 150px !important;
            max-width: 150px !important;
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
            width: 150px !important;
            min-width: 150px !important;
            max-width: 150px !important;
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
