<template>
  <slot
    v-if="renderApi == false"
  />
  <div
    v-if="renderApi || externalItems"
    class="flex justify-between mb-10"  
  >
    <h2
      class="font-extended font-normal text-24 leading-110 -tracking-3 text-indigo"
      v-text="heading"
    />
    <div v-if="filterTerms.length > 0" class="flex">
      <button
        v-for="(term, index) in filterTerms"
        class="font-body font-normal text-10 leading-130 text-indigo-900 hover:underline mr-5"
        :class="{'!text-indigo font-bold': term == this.filterTerm}"
        v-text="term"
        @click="toggleTerm(term)"
      />
    </div>
  </div>
  <table
    v-if="renderApi || externalItems"
    class="table w-full"
  >
    <tbody>
      <tr v-if="headings">
        <th
          v-for="(heading, index) in headings"
          v-text="heading"
          class="hidden md:table-cell h-12 md:h-11 px-6 font-body text-20 md:text-14 font-semibold leading-120 text-indigo text-left bg-indigo-200"
        />
      </tr>
      <tr
        v-for="(item, index) in paginatedItems"
        class="w-full h-12 md:h-10 odd:bg-gray-100"
      >
        <td class="px-6">
          <a
            class="inline-flex items-center font-body text-20 md:text-12 font-semibold leading-140"
            :class="{ '!text-indigo hover:underline': item.link.url, 'text-indigo-800': item.link.url != true }"
            :href="item.link.url ? item.link.url : null"
          >
            <div
              v-if="item.image"
              class="table__image hidden md:block relative mr-3 rounded-[50%] overflow-hidden">
              <picture>
                <source media="(min-width:768px)" srcset="{{ item.image.srcset }}">
                <img
                  class="w-6"
                  :src="item.image.src"
                  :alt="item.image.alt"
                />
              </picture>
            </div>
            <span v-html="item.link.title" />
          </a>
        </td>
        <td
          v-for="(column, index) in item.columns"
          class="hidden md:table-cell px-6 font-body text-20 md:text-12 font-normal leading-140 text-indigo-800 py-2"
          v-text="column"
        />
      </tr>
    </tbody>
  </table>
  <div
    v-if="totalPages > 0"
    class="pagination flex items-center justify-between mt-10"
  >
    <span
      class="pagination__text font-body font-normal text-12 leading-140 text-indigo-800"
      v-text="`Showing ${ paginatedItems.length } of ${ filteredItems.length }`"
    />

    <div class="inline-flex items-center h-8 px-5 py-0.5 bg-gray-100 rounded-md space-x-1">
      <button
        v-if="currentPage !== 1"
        class="block p-2 font-body font-normal text-10 leading-140 text-indigo-800 hover:text-indigo hover:underline hover:bg-indigo-200 transition-all duration-200 ease-in-out"
        @click="navigatePages('prev')"
      >
        <svg class="w-1 fill-indigo-800" viewBox="0 0 4.2 7" style="enable-background:new 0 0 4.2 7" xml:space="preserve">
          <path d="M4 .2c.3.3.3.7 0 1L1.7 3.5 4 5.8c.3.3.3.7 0 1-.3.3-.7.3-1 0L.2 4c-.3-.3-.3-.7 0-1L3 .2c.3-.3.7-.3 1 0z" style="fill-rule:evenodd; clip-rule:evenodd;"/>
        </svg>
      </button>
      <ul class="inline-flex space-x-1">
        <li
          v-for="index in totalPages"
        >
          <button
            class="block p-2 py-1 font-body font-normal text-10 leading-140 text-indigo-800 hover:text-indigo hover:underline hover:bg-indigo-200 transition-all duration-200 ease-in-out"
            :class="{'bg-indigo-200': currentPage == index}"
            v-text="index"
            @click="currentPage = index"
          />
        </li>
      </ul>
      <button
        v-if="currentPage !== totalPages"
        class="block p-2 font-body font-normal text-10 leading-140 text-indigo-800 hover:text-indigo hover:underline hover:bg-indigo-200 transition-all duration-200 ease-in-out"
        @click="navigatePages('next')"
      >
        <svg class="w-1 fill-indigo-800 rotate-180" viewBox="0 0 4.2 7" style="enable-background:new 0 0 4.2 7" xml:space="preserve">
          <path d="M4 .2c.3.3.3.7 0 1L1.7 3.5 4 5.8c.3.3.3.7 0 1-.3.3-.7.3-1 0L.2 4c-.3-.3-.3-.7 0-1L3 .2c.3-.3.7-.3 1 0z" style="fill-rule:evenodd; clip-rule:evenodd;"/>
        </svg>
      </button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      heading: undefined,
      headings: undefined,
      endpoint: undefined,
      items: [],
      currentPage: 1,
      filterTerm: '',
      filterTerms: [],
    };
  },
  computed: {
    filteredItems() {
      const f = this.items.filter((item) => item.type == this.filterTerm);
      let g;

      if (f.length == 0) {
        g = this.items;
      } else {
        g = this.items.filter((item) => item.type == this.filterTerm);
      }

      return g;
    },
    pageIndexStart() {
      return (this.currentPage - 1) * this.itemsPerPage;
    },
    pageIndexEnd() {
      return this.pageIndexStart + this.itemsPerPage;
    },
    paginatedItems() {
      return this.filteredItems.slice(this.pageIndexStart, this.pageIndexEnd);
    },
    totalPages() {
      return Math.ceil(this.filteredItems.length / this.itemsPerPage);
    },
  },
  async mounted() {
    if (this.renderApi) {

      switch(this.api) {
        case 'Department Courses':
          this.endpoint = 'https://author.colby.edu/courses/';
          this.heading = `${this.departmentCode} Department Courses`
          break;
        case 'Course Catalogue':
          this.endpoint = 'https://author.colby.edu/courses/';
          this.heading = this.api;
          break;
        case 'Majors and Minors':
          this.endpoint = 'https://author.colby.edu/majorsminors/';
          this.heading = this.api;
          break;
      }

      await axios.get(this.endpoint)
        .then(outputa => {
          switch(this.api) {
            case 'Department Courses':
              const deptItems = outputa.data.courses.filter((item) => item.dept == this.departmentCode);

              this.items = deptItems.map(item => {
                switch(item.sessOffered) {
                  case 'Fall':
                    item.sess = 'Fall';
                    break;
                  case 'Sprg':
                    item.sess = 'Spring';
                    break;
                  case 'Jan':
                    item.sess = 'January';
                    break;
                }

                return {
                  title: item.longTitle,
                  type: item.sess,
                  link: {
                    title: item.longTitle,
                    url: null,
                  },
                  columns: [
                    item.abstr,
                  ]
                };
              });

              this.headings = ['Name', 'Description'];

              this.filterTerms = [
                'Fall',
                'Spring',
                'January',
              ];
              break;
            case 'Course Catalogue':
              console.log(this.departmentCode);
              console.log(outputa.data.courses);

              // const filteredItems = outputa.data.courses.filter((item) => item.dept == this.departmentCode);
              const filteredItems = outputa.data.courses;

              this.items = filteredItems.map(item => {
                return {
                  title: item.longTitle,
                  link: {
                    title: item.longTitle,
                    url: null,
                  },
                  columns: [
                    item.dept,
                  ]
                };
              });

              this.headings = ['Name', 'Department'];
              break;
            case 'Majors and Minors':
              this.items = outputa.data.map(item => {
                return {
                  title: item.Text,
                  type: `${item.Type}s`,
                  link: {
                    title: item.Text,
                    url: null,
                  },
                  columns: [
                    item.Dept,
                    item.Type,
                  ]
                };
              });

              this.filterTerms = [
                'Majors',
                'Minors',
              ];

              this.headings = ['Name', 'Department', 'Type'];
              break;
          }
        });
    }

    if (this.externalItems) {
      switch(this.api) {
        case 'People':
          this.items = this.externalItems.map(item => {
            return {
              image: {
                src: item.thumbnail,
                alt: item.post_title,
              },
              title: item.post_title,
              link: {
                title: item.post_title,
                url: `/people/people-directory/${ item.post_name }`,
              },
              columns: [
                item.custom.title,
                item.department,
              ]
            };
          });

          this.headings = ['Name', 'Title', 'Department'];
          break;
        case 'Offices':
          this.items = this.externalItems.map(item => {
            return {
              title: item.post_title,
              link: {
                title: item.post_title,
                url: `/people/offices-directory/${ item.post_name }`,
              },
              columns: [
                item.custom.address,
                item.custom.phone,
              ]
            };
          });

          this.headings = ['Name', 'Address', 'Phone'];
          break;
        case 'Departments':
          this.items = this.externalItems.map(item => {
            return {
              title: item.post_title,
              link: {
                title: item.post_title,
                url: `/academics/departments-and-programs/${ item.post_name }`,
              },
              columns: [
                item.custom.heading,
              ]
            };
          });

          this.headings = ['Name', 'Description'];
          break;
      }
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
    }
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
    toggleTerm(term) {

      if (this.filterTerm == term) {
        this.filterTerm = '';
      } else {
        this.filterTerm = term;
      }
    },
  },
}
</script>