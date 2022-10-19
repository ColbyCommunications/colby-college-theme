module.exports = {
  title: 'Media Context',
  status: 'wip',
  context: {
    image: {
      srcset: 'https://via.placeholder.com/800x800',
      src: 'https://via.placeholder.com/400x400',
      alt: 'Test alt',
    },
    subheading: 'Research',
    heading: 'Go Deeper',
    paragraph: 'Whether you’re a fan of fish or the future of AI, we have programs dedicated solely to the deeper scholarship of subjects that are already transforming our world.',
    buttons: [
      {
        url: 'https://welcometruth.com/',
        title: 'Bigelow Laboratory for Ocean Sciences',
      },
      {
        url: 'https://welcometruth.com/',
        title: 'Davis Institute for Artificial Intelligence',
      },
    ],
  },
  variants: [
    {
      name: 'Reverse',
      context: {
        reverse: true,
      },
    },
    {
      name: 'Video',
      context: {
        image: {
          srcset: 'https://via.placeholder.com/480x290',
          src: 'https://via.placeholder.com/480x290',
          alt: 'Test alt',
        },
        video: {
          id: '2EgmaMYxzA8',
        },
        subheading: null,
        heading: 'Just how good is Colby’s Jan Plan? Only three January terms are required—90 percent of Colby students do four. ',
        paragraph: null,
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'See How',
          }
        ],
      },
    },
  ]
}
