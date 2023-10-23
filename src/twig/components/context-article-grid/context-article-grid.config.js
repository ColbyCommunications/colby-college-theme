module.exports = {
  title: "Context Article Grid",
  status: "wip",
  context: {
    render_api: false,
    subheading: 'News profiles',
    heading: 'Headline lorem ipsum dolor sit amet.',
    items: [
      {
        image: {
          srcset: 'https://via.placeholder.com/600x600',
          src: 'https://via.placeholder.com/300x300',
          alt: 'Test alt',
        },
        subheading: 'Kicker',
        heading: 'Lorem ipsum dolor sit amet, consectet.',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam congue pulvinar lectus.',
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'Learn More',
          },
        ]
      },
      {
        image: {
          srcset: 'https://via.placeholder.com/600x600',
          src: 'https://via.placeholder.com/300x300',
          alt: 'Test alt',
        },
        subheading: 'Kicker',
        heading: 'Lorem ipsum dolor sit amet, consectet.',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam congue pulvinar lectus.',
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'Learn More',
          },
        ]
      },
      {
        image: {
          srcset: 'https://via.placeholder.com/600x600',
          src: 'https://via.placeholder.com/300x300',
          alt: 'Test alt',
        },
        subheading: 'Kicker',
        heading: 'Lorem ipsum dolor sit amet, consectet.',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam congue pulvinar lectus.',
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'Learn More',
          },
        ]
      },
    ]
  },
  variants: [
    {
      name: 'Render API',
      context: {
        render_api: true,
      }
    },
    {
      name: 'Render API (Alumni)',
      context: {
        render_api: true,
        api: 'Alumni',
        per_page: 3,
      }
    },
    {
      name: 'Render API (Arts)',
      context: {
        render_api: true,
        api: 'Arts',
        subheading: null,
        heading: 'Colby Arts News',
        paragraph: 'Find out what innovative forms of art Colby students are bringing to the stage (or gallery).',
      }
    },
  ]
}