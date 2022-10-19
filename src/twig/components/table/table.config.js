module.exports = {
  title: "Table",
  status: "wip",
  context: {
    render_api: false,
    headings: [
      {
        heading: 'Lorem ipsum dolor',
      },
      {
        heading: 'Lorem ipsum dolor sit',
      }
    ],
    items: [
      {
        image: {
          srcset: 'https://via.placeholder.com/100x100',
          src: 'https://via.placeholder.com/100x100',
          alt: 'Test alt',
        },
        link: {
          title: 'Lorem Ipsum Dolor',
          url: 'https://welcometruth.com/',
        },
        columns: [
          {
            column: 'Lorem ipsum dolor sit',
          }
        ],
      },
      {
        image: {
          srcset: 'https://via.placeholder.com/100x100',
          src: 'https://via.placeholder.com/100x100',
          alt: 'Test alt',
        },
        link: {
          title: 'Lorem Ipsum Dolor',
          url: 'https://welcometruth.com/',
        },
        columns: [
          {
            column: 'Lorem ipsum dolor sit',
          }
        ],
      },
      {
        image: {
          srcset: 'https://via.placeholder.com/100x100',
          src: 'https://via.placeholder.com/100x100',
          alt: 'Test alt',
        },
        link: {
          title: 'Lorem Ipsum Dolor',
          url: 'https://welcometruth.com/',
        },
        columns: [
          {
            column: 'Lorem ipsum dolor sit',
          }
        ],
      },
      {
        image: {
          srcset: 'https://via.placeholder.com/100x100',
          src: 'https://via.placeholder.com/100x100',
          alt: 'Test alt',
        },
        link: {
          title: 'Lorem Ipsum Dolor',
          url: 'https://welcometruth.com/',
        },
        columns: [
          {
            column: 'Lorem ipsum dolor sit',
          }
        ],
      },
      {
        image: {
          srcset: 'https://via.placeholder.com/100x100',
          src: 'https://via.placeholder.com/100x100',
          alt: 'Test alt',
        },
        link: {
          title: 'Lorem Ipsum Dolor',
          url: 'https://welcometruth.com/',
        },
        columns: [
          {
            column: 'Lorem ipsum dolor sit',
          }
        ],
      },
      {
        image: {
          srcset: 'https://via.placeholder.com/100x100',
          src: 'https://via.placeholder.com/100x100',
          alt: 'Test alt',
        },
        link: {
          title: 'Lorem Ipsum Dolor',
          url: 'https://welcometruth.com/',
        },
        columns: [
          {
            column: 'Lorem ipsum dolor sit',
          }
        ],
      },
      {
        image: {
          srcset: 'https://via.placeholder.com/100x100',
          src: 'https://via.placeholder.com/100x100',
          alt: 'Test alt',
        },
        link: {
          title: 'Lorem Ipsum Dolor',
          url: 'https://welcometruth.com/',
        },
        columns: [
          {
            column: 'Lorem ipsum dolor sit',
          }
        ],
      },
      {
        image: {
          srcset: 'https://via.placeholder.com/100x100',
          src: 'https://via.placeholder.com/100x100',
          alt: 'Test alt',
        },
        link: {
          title: 'Lorem Ipsum Dolor',
          url: 'https://welcometruth.com/',
        },
        columns: [
          {
            column: 'Lorem ipsum dolor sit',
          }
        ],
      },
    ]
  },
  variants: [
    {
      name: 'Single Column',
      context: {
        headings: null,
        items: [
          {
            link: {
              title: 'Lorem Ipsum Dolor',
              url: 'https://welcometruth.com/',
            },
          },
          {
            link: {
              title: 'Lorem Ipsum Dolor',
              url: 'https://welcometruth.com/',
            },
          },
          {
            link: {
              title: 'Lorem Ipsum Dolor',
              url: 'https://welcometruth.com/',
            },
          },
          {
            link: {
              title: 'Lorem Ipsum Dolor',
              url: 'https://welcometruth.com/',
            },
          },
          {
            link: {
              title: 'Lorem Ipsum Dolor',
              url: 'https://welcometruth.com/',
            },
          },
          {
            link: {
              title: 'Lorem Ipsum Dolor',
              url: 'https://welcometruth.com/',
            },
          },
        ]
      },
    },
    {
      name: 'Single Column No Links',
      context: {
        headings: null,
        items: [
          {
            title: 'Lorem Ipsum Dolor',
          },
          {
            title: 'Lorem Ipsum Dolor',
          },
          {
            title: 'Lorem Ipsum Dolor',
          },
          {
            title: 'Lorem Ipsum Dolor',
          },
          {
            title: 'Lorem Ipsum Dolor',
          },
          {
            title: 'Lorem Ipsum Dolor',
          },
          {
            title: 'Lorem Ipsum Dolor',
          },
        ]
      },
    },
    {
      name: 'Department Courses (API)',
      context: {
        render_api: true,
        api: 'Department Courses',
        department_code: 'HIST',
      },
    },
    {
      name: 'Course Catalogue',
      context: {
        render_api: true,
        api: 'Course Catalogue',
        department_code: 'HIST',
      },
    },
    {
      name: 'Majors & Minors (API)',
      context: {
        render_api: true,
        api: 'Majors and Minors',
      },
    },
  ],
}