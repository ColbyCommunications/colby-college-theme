module.exports = {
  title: 'Article',
  status: 'wip',
  context: {
    image: {
      srcset: 'https://via.placeholder.com/760x360',
      src: 'https://via.placeholder.com/180x180',
      alt: 'Test alt',
    },
    subheading: 'Natural Sciences',
    heading: 'Lorem ipsum dolor sit amet, consectet.',
    paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam congue pulvinar lectus.',
    buttons: [
      {
        url: 'https://welcometruth.com/',
        title: 'Learn More',
      }
    ],
  },
  variants: [
    {
      name: 'Portrait',
      context: {
        image: {
          srcset: 'https://via.placeholder.com/380x580',
          src: 'https://via.placeholder.com/190x290',
          alt: 'Test alt',
        },
        subheading: null,
        heading: 'Name Lorem',
        paragraph: 'Title Lorem ipsum',
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'Read Bio',
          }
        ]
      },
    }
  ],
}