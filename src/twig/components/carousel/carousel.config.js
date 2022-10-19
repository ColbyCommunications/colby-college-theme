module.exports = {
  title: 'Carousel',
  status: 'wip',
  context: {
    render_api: false,
    heading: 'News',
    paragraph: 'Latest from Colby.',
    buttons: [
      {
        url: 'https://welcometruth.com/',
        title: 'All News',
      }
    ],
    items: [
      {
        subheading: 'Kicker 1',
        heading: 'Lorem ipsum dolor sit amet, consectet.',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam congue pulvinar lectus.',
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'Read Story',
          },
        ],
        image: {
          srcset: 'https://via.placeholder.com/760x430',
          src: 'https://via.placeholder.com/410x290',
          alt: 'Test alt',
        },
      },
      {
        subheading: 'Kicker 2',
        heading: 'Lorem ipsum dolor sit amet, consectet.',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam congue pulvinar lectus.',
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'Read Story',
          },
        ],
        image: {
          srcset: 'https://via.placeholder.com/760x430',
          src: 'https://via.placeholder.com/410x290',
          alt: 'Test alt',
        },
      },
      {
        subheading: 'Kicker 3',
        heading: 'Lorem ipsum dolor sit amet, consectet.',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam congue pulvinar lectus.',
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'Read Story',
          },
        ],
        image: {
          srcset: 'https://via.placeholder.com/760x430',
          src: 'https://via.placeholder.com/410x290',
          alt: 'Test alt',
        },
      },
    ]
  },
  variants: [
    {
      name: 'Colby News (API)',
      context: {
        render_api: true,
      },
    },
  ]
}