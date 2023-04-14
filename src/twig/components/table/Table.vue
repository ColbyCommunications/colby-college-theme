<template>
    <slot v-if="renderApi == false && !externalItems" />
    <div v-if="renderApi || externalItems" class="md:flex justify-between !mb-10">
        <h2
            class="font-extended font-normal text-24 leading-110 -tracking-3 text-indigo"
            v-text="heading"
        />
    </div>
    <div
        v-if="renderApi || externalItems"
        class="flex flex-wrap md:flex-nowrap justify-between md:space-x-12 mt-6 md:mt-0 !mb-8"
    >
        <label class="relative flex shrink-0 md:shrink mb-6 md:mb-0 text-[0] w-full max-w-sm">
            Search
            <svg class="absolute top-3 left-3 fill-indigo-800 w-2.5" viewBox="0 0 9.6 9.6">
                <path
                    d="M3.6 1.2c-1.3 0-2.4 1.1-2.4 2.4C1.2 4.9 2.3 6 3.6 6 4.9 6 6 4.9 6 3.6c0-1.3-1.1-2.4-2.4-2.4zM0 3.6C0 1.6 1.6 0 3.6 0s3.6 1.6 3.6 3.6c0 .8-.2 1.5-.7 2.1l2.9 2.9c.2.2.2.6 0 .8-.2.2-.6.2-.8 0L5.7 6.5c-.6.5-1.3.7-2.1.7-2 0-3.6-1.6-3.6-3.6z"
                    style="fill-rule: evenodd; clip-rule: evenodd"
                />
            </svg>
            <input
                class="w-full h-[34px] max-w-sm p-2.5 pl-7 border border-indigo-400 border-solid rounded-md font-body font-normal text-10 leading-130 text-indigo-800 placeholder-indigo-800 bg-white"
                type="text"
                name="search-input"
                placeholder="Search"
                v-model="searchTerm"
                @input="(event) => (this.currentPage = 1)"
            />
            <svg
                xmlns="http://www.w3.org/2000/svg"
                height="12"
                width="12"
                class="absolute top-3 right-3 fill-indigo-800 cursor-pointer"
                :class="{ block: searchTerm.length > 0, hidden: searchTerm.length === 0 }"
                viewBox="0 0 48 48"
                @click="(event) => (this.searchTerm = '')"
            >
                <path
                    d="m12.45 37.65-2.1-2.1L21.9 24 10.35 12.45l2.1-2.1L24 21.9l11.55-11.55 2.1 2.1L26.1 24l11.55 11.55-2.1 2.1L24 26.1Z"
                />
            </svg>
        </label>
        <select
            v-if="this.api == 'Course Catalogue'"
            v-model="selectedDepartment"
            @change="toggleTerm('SELECT', $event)"
            class="w-full max-w-[120px] font-body font-normal text-10 leading-130 text-indigo-900 hover:underline mr-5 cursor-pointer mb-6 md:mb-0"
        >
            <option v-text="'All Departments'" :value="'All Departments'" />
            <option
                v-for="(item, index) in filterDepartments"
                v-text="item.Text"
                :value="item.Dept"
            />
        </select>
        <select
            v-if="this.api != 'Department Courses' && this.api != 'Offices' && this.api != 'People'"
            v-model="selectedDivision"
            @change="toggleTermDivision('SELECT', $event)"
            class="w-full max-w-[120px] font-body font-normal text-10 leading-130 text-indigo-900 hover:underline mr-5 cursor-pointer mb-6 md:mb-0"
        >
            <option v-text="'All Divisions'" :value="'All Divisions'" />
            <option v-text="'Humanities'" :value="'Humanities'" />
            <option v-text="'Interdisciplinary Studies'" :value="'Interdisciplinary Studies'" />
            <option v-text="'Natural Sciences'" :value="'Natural Sciences'" />
            <option v-text="'Social Sciences'" :value="'Social Sciences'" />
        </select>
        <div v-if="filterOptions.length > 0" class="flex mb-6 md:mb-0">
            <button
                class="font-body font-normal text-10 leading-130 text-indigo-900 hover:underline mr-5"
                :class="{
                    '!text-indigo font-bold':
                        this.filterTerm.some((t) => this.filterOptions.includes(t)) == false,
                }"
                v-text="'All'"
                @click="toggleTermType('All', $event)"
            />
            <button
                v-for="(term, index) in filterOptions"
                class="font-body font-normal text-10 leading-130 text-indigo-900 hover:underline mr-5"
                :class="{ '!text-indigo font-bold': this.filterTerm.includes(term) }"
                v-text="term"
                @click="toggleTermType(term, $event)"
            />
        </div>
    </div>
    <table
        v-if="renderApi || externalItems"
        class="block md:table overflow-scroll md:overflow-auto w-full colby-table-block"
    >
        <tbody>
            <tr v-if="headings">
                <th
                    v-for="(heading, index) in headings"
                    v-text="heading"
                    class="h-12 md:h-11 px-6 font-body text-18 md:text-14 font-semibold leading-120 text-indigo text-left bg-indigo-200 whitespace-nowrap"
                />
            </tr>
            <tr v-for="(item, index) in paginatedItems" class="w-full h-12 md:h-10 odd:bg-gray-100">
                <td class="whitespace-nowrap md:whitespace-normal px-6 py-2">
                    <a
                        v-if="item.link.url && !item.image"
                        class="text-indigo hover:underline inline-flex items-center font-body text-16 md:text-12 font-semibold leading-140"
                        :href="item.link.url ? item.link.url : null"
                    >
                        {{ item.link.title }}
                    </a>
                    <div v-if="item.image">
                        <a :href="item.link.url" class="flex">
                            <picture>
                                <source
                                    media="(min-width:768px)"
                                    srcset="{{ item.image.srcset }}"
                                />
                                <img
                                    class="w-6 h-6 table__image hidden md:block relative mr-3 rounded-[50%] overflow-hidden"
                                    :src="
                                        item.image.src
                                            ? item.image.src
                                            : '/wp-content/uploads/2022/10/profile_placeholder.jpeg'
                                    "
                                    :alt="item.image.alt"
                                />
                            </picture>
                            <span
                                class="text-indigo hover:underline inline-flex items-center font-body text-16 md:text-12 font-semibold leading-140"
                                >{{ item.link.title }}</span
                            >
                        </a>
                    </div>
                    <modal v-if="item.description">
                        <template v-slot:content>
                            <h3
                                class="flex items-center px-5 py-2 font-body text-20 md:text-14 font-semibold leading-120 text-indigo text-left bg-indigo-200"
                                v-text="item.title"
                            />
                            <p
                                class="font-body text-20 md:text-12 font-normal leading-140 text-indigo-800 p-5"
                                v-html="item.description"
                            />
                        </template>
                        <template v-slot:button>
                            <span
                                class="text-indigo hover:underline inline-flex items-center font-body text-16 md:text-12 font-semibold leading-140"
                                v-html="item.link.title"
                            />
                        </template>
                    </modal>
                    <span
                        v-if="!item.description && !item.image && !item.link.url"
                        class="text-indigo inline-flex items-center font-body text-20 md:text-12 font-semibold leading-140"
                    >
                        {{ item.link.title }}
                    </span>
                </td>
                <td
                    v-for="(column, index) in item.columns"
                    class="px-6 font-body text-16 md:text-12 font-normal leading-140 text-indigo-800 py-2"
                    v-text="column"
                />
            </tr>
        </tbody>
    </table>
    <div v-if="totalPages > 0" class="pagination flex items-center justify-between mt-10">
        <span
            class="pagination__text font-body font-normal text-12 leading-140 text-indigo-800"
            v-text="`Showing ${paginatedItems.length} of ${filteredItems.length}`"
        />

        <div class="inline-flex items-center h-8 px-5 py-0.5 bg-gray-100 rounded-md space-x-1">
            <button
                v-if="currentPage !== 1"
                class="block p-2 font-body font-normal text-14 md:text-10 leading-140 text-indigo-800 hover:text-indigo hover:underline hover:bg-indigo-200 transition-all duration-200 ease-in-out"
                @click="navigatePages('prev')"
            >
                <svg
                    class="w-1 fill-indigo-800"
                    viewBox="0 0 4.2 7"
                    style="enable-background: new 0 0 4.2 7"
                    xml:space="preserve"
                >
                    <path
                        d="M4 .2c.3.3.3.7 0 1L1.7 3.5 4 5.8c.3.3.3.7 0 1-.3.3-.7.3-1 0L.2 4c-.3-.3-.3-.7 0-1L3 .2c.3-.3.7-.3 1 0z"
                        style="fill-rule: evenodd; clip-rule: evenodd"
                    />
                </svg>
            </button>
            <ul class="pagination__container inline-flex space-x-1">
                <li v-for="index in totalPages" class="pagination__item">
                    <button
                        class="block p-2 py-1 font-body font-normal text-14 md:text-10 leading-140 text-indigo-800 hover:text-indigo hover:underline hover:bg-indigo-200 transition-all duration-200 ease-in-out"
                        :class="{ 'bg-indigo-200': currentPage == index }"
                        v-text="index"
                        @click="currentPage = index"
                    />
                </li>
            </ul>
            <button
                v-if="currentPage !== totalPages"
                class="block p-2 font-body font-normal text-14 md:text-10 leading-140 text-indigo-800 hover:text-indigo hover:underline hover:bg-indigo-200 transition-all duration-200 ease-in-out"
                @click="navigatePages('next')"
            >
                <svg
                    class="w-1 fill-indigo-800 rotate-180"
                    viewBox="0 0 4.2 7"
                    style="enable-background: new 0 0 4.2 7"
                    xml:space="preserve"
                >
                    <path
                        d="M4 .2c.3.3.3.7 0 1L1.7 3.5 4 5.8c.3.3.3.7 0 1-.3.3-.7.3-1 0L.2 4c-.3-.3-.3-.7 0-1L3 .2c.3-.3.7-.3 1 0z"
                        style="fill-rule: evenodd; clip-rule: evenodd"
                    />
                </svg>
            </button>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Fuse from 'fuse.js';
    import _map from 'lodash/map';
    import _uniq from 'lodash/uniq';
    import Modal from '/src/twig/components/modal/Modal.vue';
    export default {
        components: {
            Modal,
        },
        data() {
            return {
                fuse: null,
                heading: undefined,
                headings: undefined,
                endpoint: undefined,
                items: [],
                currentPage: 1,
                searchTerm: '',
                filterTerm: [],
                filterOptions: [],
                filterDepartments: [],
                selectedDepartment: 'All Departments',
                selectedDivision: 'All Divisions',
            };
        },
        computed: {
            filteredItems() {
                let f = [];
                if (this.api !== 'Departments') {
                    f = this.items.filter((item) => {
                        return this.filterTerm.some((r) => item.type.includes(r));
                    });
                }
                let g;
                if (f.length == 0) {
                    g = this.items;
                } else {
                    g = this.items.filter((item) =>
                        this.filterTerm.some((r) => item.type.includes(r))
                    );
                }
                if (this.selectedDepartment !== 'All Departments') {
                    g = g.filter((item) => this.filterTerm.includes(item.department));
                }
                if (this.selectedDivision !== 'All Divisions') {
                    g = g.filter((item) => this.filterTerm.includes(item.department));
                }
                if (this.fuse) {
                    this.fuse.setCollection(g);
                }
                return g;
            },
            inputFilteredItems() {
                let u = [];
                if (this.fuse) {
                    if (this.searchTerm.length > 0) {
                        let f = this.fuse.search(this.searchTerm);
                        u = f.map((item) => item.item);
                    } else {
                        u = this.filteredItems;
                    }
                }
                return u;
            },
            pageIndexStart() {
                return (this.currentPage - 1) * this.itemsPerPage;
            },
            pageIndexEnd() {
                return this.pageIndexStart + this.itemsPerPage;
            },
            paginatedItems() {
                return this.inputFilteredItems.slice(this.pageIndexStart, this.pageIndexEnd);
            },
            totalPages() {
                return Math.ceil(this.inputFilteredItems.length / this.itemsPerPage);
            },
        },
        async mounted() {
            // Seperate call to the majors and minors api specifically to populate the Course catalog filter
            if (this.api == 'Course Catalogue') {
                await axios
                    .get('https://www.colby.edu/endpoints/v1/majorsminors/')
                    .then((outputa) => {
                        this.filterDepartments = outputa.data;
                    });
            }
            if (this.renderApi) {
                switch (this.api) {
                    case 'Department Courses':
                        this.endpoint = 'https://www.colby.edu/endpoints/v1/courses/';
                        this.heading = `Department Courses`;
                        break;
                    case 'Course Catalogue':
                        this.endpoint = 'https://www.colby.edu/endpoints/v1/courses/';
                        this.heading = this.api;
                        break;
                    case 'Majors and Minors':
                        this.endpoint = 'https://www.colby.edu/endpoints/v1/majorsminors/';
                        this.heading = this.api;
                        break;
                }
                await axios.get(this.endpoint).then((outputa) => {
                    switch (this.api) {
                        case 'Department Courses':
                            const deptItems = outputa.data.courses.filter(
                                (item) => item.dept == this.departmentCode && item.longTitle
                            );
                            this.items = deptItems.map((item) => {
                                let itemTypes = item.sessOffered.split(',');
                                itemTypes.forEach((type, index) => {
                                    switch (type) {
                                        case 'FA':
                                            itemTypes[index] = 'Fall';
                                            break;
                                        case 'SP':
                                            itemTypes[index] = 'Spring';
                                            break;
                                        case 'JP':
                                            itemTypes[index] = 'January';
                                            break;
                                    }
                                });
                                return {
                                    title: item.longTitle,
                                    type: itemTypes,
                                    link: {
                                        title: item.longTitle,
                                        url: null,
                                    },
                                    columns: [item.crsno, item.abstr],
                                };
                            });
                            this.headings = ['Name', 'Course Number', 'Description'];
                            this.filterOptions = ['Fall', 'Spring', 'January'];
                            break;
                        case 'Course Catalogue':
                            const filteredItems = outputa.data.courses.filter(
                                (item) => item.longTitle
                            );
                            this.items = filteredItems.map((item) => {
                                let itemTypes = item.sessOffered.split(',');
                                itemTypes.forEach((type, index) => {
                                    switch (type) {
                                        case 'FA':
                                            itemTypes[index] = 'Fall';
                                            break;
                                        case 'SP':
                                            itemTypes[index] = 'Spring';
                                            break;
                                        case 'JP':
                                            itemTypes[index] = 'January';
                                            break;
                                    }
                                });
                                return {
                                    title: item.longTitle,
                                    description: this.renderDesc(item),
                                    type: itemTypes,
                                    department: item.dept,
                                    link: {
                                        title: item.longTitle,
                                        url: null,
                                    },
                                    columns: [item.crsno, item.dept],
                                    faculty: this._faculty(item.sections),
                                };
                            });
                            this.headings = ['Name', 'Course Number', 'Department'];
                            this.filterOptions = ['Fall', 'Spring', 'January'];
                            break;
                        case 'Majors and Minors':
                            this.items = outputa.data.map((item) => {
                                return {
                                    title: item.Text,
                                    type: `${item.Type}s`,
                                    link: {
                                        title: item.Text,
                                        url: `/dept/${item.Dept.toLowerCase()}`,
                                    },
                                    columns: [item.Dept, item.Type],
                                    department: item.Dept,
                                };
                            });
                            this.filterOptions = ['Majors', 'Minors'];
                            this.headings = ['Name', 'Department', 'Type'];
                            break;
                    }
                    this.initFuse();
                });
            }
            if (this.externalItems) {
                this.heading = this.api;
                switch (this.api) {
                    case 'People':
                        this.items = this.externalItems.map((item) => {
                            return {
                                image: {
                                    src: item.thumbnail,
                                    alt: item.post_title,
                                },
                                title: item.post_title,
                                link: {
                                    title: item.post_title,
                                    url: `/people/people-directory/${item.post_name}`,
                                },
                                columns: [item.custom.title, item.department],
                            };
                        });
                        this.headings = ['Name', 'Title', 'Department'];
                        break;
                    case 'Offices':
                        this.items = this.externalItems.map((item) => {
                            return {
                                title: item.post_title,
                                link: {
                                    title: item.post_title,
                                    url: `/people/offices-directory/${item.post_name}`,
                                },
                                columns: [],
                            };
                        });
                        this.headings = ['Name'];
                        break;
                    case 'Departments':
                        this.heading = 'Departments and Programs';
                        this.items = this.externalItems.map((item) => {
                            return {
                                title: item.post_title,
                                link: {
                                    title: item.post_title,
                                    url: `/academics/departments-and-programs/${item.post_name}`,
                                },
                                columns: [],
                                department: item.department_code,
                            };
                        });
                        this.headings = ['Name'];
                        break;
                }
                this.initFuse();
            }
        },
        props: {
            renderApi: {
                type: Boolean,
                required: false,
                default: false,
            },
            api: {
                type: String,
                required: false,
            },
            departmentCode: {
                type: String,
                required: false,
            },
            itemsPerPage: {
                type: Number,
                required: false,
                default: 20,
            },
            externalItems: {
                type: Array,
                required: false,
            },
        },
        methods: {
            navigatePages(dir) {
                if (dir == 'prev') {
                    if (this.currentPage !== 1) {
                        this.currentPage -= 1;
                    }
                } else {
                    if (this.currentPage !== this.totalPages) {
                        this.currentPage += 1;
                    }
                }
            },
            toggleTerm(term, event) {
                if (this.selectedDivision != 'All Divisions') {
                    this.selectedDivision = 'All Divisions';
                }
                this.currentPage = 1;
                let select;
                if (term == 'SELECT') {
                    term = event.target.value;
                    select = true;
                }
                if (this.filterTerm.includes(term)) {
                    this.filterTerm.splice(this.filterTerm.indexOf(term));
                } else {
                    if (select) {
                        this.filterTerm = [];
                    }
                    if (this.filterTerm.some((t) => this.filterOptions.includes(t))) {
                        this.filterTerm.forEach((t) => {
                            if (this.filterOptions.includes(t)) {
                                this.filterTerm.splice(this.filterTerm.indexOf(t));
                            }
                        });
                    }
                    this.filterTerm.push(term);
                }
            },
            toggleTermType(term, event) {
                this.currentPage = 1;
                let select;
                if (term == 'SELECT') {
                    term = event.target.value;
                    select = true;
                }
                if (this.filterTerm.includes(term)) {
                    this.filterTerm.splice(this.filterTerm.indexOf(term));
                } else {
                    if (select) {
                        this.filterTerm = [];
                    }
                    if (this.filterTerm.some((t) => this.filterOptions.includes(t))) {
                        this.filterTerm.forEach((t) => {
                            if (this.filterOptions.includes(t)) {
                                this.filterTerm.splice(this.filterTerm.indexOf(t));
                            }
                        });
                    }
                    this.filterTerm.push(term);
                }
            },
            toggleTermDivision(term, event) {
                if (this.selectedDepartment != 'All Departments') {
                    this.selectedDepartment = 'All Departments';
                }
                this.currentPage = 1;
                let select;
                if (term == 'SELECT') {
                    term = event.target.value;
                    select = true;
                }

                switch (term) {
                    case 'Humanities':
                        this.filterTerm = [
                            'ART',
                            'CLAS',
                            'EAST',
                            'ENGL',
                            'FRIT',
                            'GMRU',
                            'MUSI',
                            'THEA',
                            'PHIL',
                            'RELG',
                            'SPAN',
                        ];
                        break;
                    case 'Interdisciplinary Studies':
                        this.filterTerm = [
                            'AFAM',
                            'AMER',
                            'INTD',
                            'EDUC',
                            'ENVS',
                            'GLST',
                            'LTAM',
                            'SCIT',
                            'WGST',
                        ];
                        break;
                    case 'Natural Sciences':
                        this.filterTerm = ['PHYS', 'BIOL', 'CHEM', 'COMP', 'GEOL', 'MATH', 'STAT'];
                        break;
                    case 'Social Sciences':
                        this.filterTerm = ['ANTH', 'ECON', 'GOVT', 'HIST', 'PSYC', 'SOCY'];
                        break;
                    default:
                    // code block
                }
            },
            onSearchChange() {
                this.currentPage = 1;
            },
            initFuse() {
                if (this.filteredItems) {
                    this.fuse = new Fuse(this.filteredItems, {
                        shouldSort: true,
                        threshold: 0.3,
                        ignoreLocation: false,
                        maxPatternLength: 32,
                        minMatchCharLength: 1,
                        keys: ['title', 'description', 'faculty'],
                    });
                }
            },
            _numberToString(i, c) {
                const numberwords = [
                    'zero',
                    'one',
                    'two',
                    'three',
                    'four',
                    'five',
                    'six',
                    'seven',
                    'eight',
                    'nine',
                    'ten',
                    'eleven',
                    'twelve',
                    'thirteen',
                    'fourteen',
                ];

                if (c) {
                    return numberwords[i].charAt(0).toUpperCase() + numberwords[i].slice(1);
                } else {
                    return numberwords[i];
                }
            },
            _reqs(area, labsci, writing, div) {
                let reqs = false;
                let toReturn = '';

                if (area !== '') {
                    toReturn = toReturn + area;
                    reqs = true;
                }

                if (labsci == 'L' || labsci == 'D') {
                    if (reqs) {
                        toReturn = toReturn + ',&nbsp;';
                    }

                    toReturn = toReturn + 'Lb';
                    reqs = true;
                }

                if (writing == 'W1' || writing == 'W2' || writing == 'W3') {
                    if (reqs) {
                        toReturn = toReturn + ',&nbsp;';
                    }

                    toReturn = toReturn + writing;
                    reqs = true;
                }
                if (div !== '') {
                    if (reqs) {
                        toReturn = toReturn + ',&nbsp;';
                    }
                    toReturn = toReturn + div;
                    reqs = true;
                }

                if (reqs) {
                    toReturn = ' <strong class="recs">' + toReturn + '.</strong>';
                }

                return toReturn;
            },
            _creditHours(crsno, minhrs, maxhrs) {
                let creditHours = '';
                if (maxhrs !== 0) {
                    if (crsno !== 'MU193') {
                        creditHours =
                            ' <em class="creditHours">' + this._numberToString(minhrs, true);
                        if (minhrs != maxhrs) {
                            if (minhrs + 1 == maxhrs) {
                                creditHours += ' or ';
                            } else {
                                if (minhrs < maxhrs) {
                                    creditHours += ' to ';
                                } else {
                                    creditHours =
                                        ' Min hrs greater than Max hrs report to Registrar ';
                                }
                            }
                            creditHours += this._numberToString(maxhrs, false);
                        }

                        creditHours += ' credit hour';

                        if (
                            this._numberToString(minhrs, true) != 'One' ||
                            this._numberToString(maxhrs, true) != 'One'
                        ) {
                            creditHours += 's';
                        }

                        creditHours += '.</em>';
                    }
                } else {
                    creditHours = '<em class="creditHours">Noncredit.</em>';
                }
                return creditHours;
            },
            _faculty(sections) {
                let faculty = [];
                sections.forEach((section) => {
                    faculty = faculty.concat(
                        _map(section.faculty, (obj) => {
                            return obj.faculty_name.split(',')[0];
                        })
                    );
                });
                let facultyStr = '';
                const uniqueFaculty = _uniq(faculty);
                uniqueFaculty.forEach((item, i) => {
                    facultyStr += item;
                    if (i !== uniqueFaculty.length - 1) {
                        facultyStr += ', ';
                    }
                });

                return facultyStr;
            },
            renderDesc(item) {
                const creditHours = this._creditHours(item.crsno, item.minhrs, item.maxhrs);
                let reqs = '';
                let prereq = '';
                if (item.prereq !== '') {
                    prereq =
                        ' <em class="prerequisite">Prerequisite:</em> <span class="prereq">' +
                        item.prereq +
                        '</span>';
                }

                if (item.crsno.substring(0, 2) !== 'IS') {
                    reqs = this._reqs(item.area, item.labsci, item.writing, item.diversity);
                }

                const faculty = this._faculty(item.sections);

                return `${item.abstr}<br/>${prereq} ${creditHours} ${reqs} <i style="text-transform: uppercase">${faculty}<i>`;
            },
        },
    };
</script>
