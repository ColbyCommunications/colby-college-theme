module.exports = {
  title: 'Overlay Wide Image',
  status: 'wip',
  context: {
    image: {
      srcset: 'https://via.placeholder.com/880x400',
      src: 'https://via.placeholder.com/400x400',
      alt: 'Test alt',
    },
    subheading: 'Green colby',
    heading: 'Sustainability and Stewardship',
    paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam congue pulvinar lectus.',
    buttons: [
      {
        url: 'https://welcometruth.com/',
        title: 'Go Green at Colby',
      },
    ],
  },
  variants: [
    {
      name: 'Centered',
      context: {
        align: 'center',
      },
    }
  ]
}