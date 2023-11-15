module.exports = {
  title: 'Home Section',
  status: 'wip',
  context: {
    news: {
      subheading: 'News Feature',
      heading: 'This just in.',
      paragraph: 'Keep up to date with fresh-off-the-press stories from Colby.',
    },
    events: {
      subheading: 'Events Feature',
      heading: 'Wondering what to do?',
      paragraph: 'Stay involved with Colby by checking out our wide array of events.',
    },
    cta: {
      image: {
        srcset: 'https://via.placeholder.com/880x400',
        src: 'https://via.placeholder.com/400x400',
        alt: 'Test alt',
      },
      subheading: 'Ways to Give',
      heading: 'Dare to change the world?',
      paragraph: 'Your gift will help produce leaders to solve the world’s greatest challenges.',
      buttons: [
        {
          url: 'https://welcometruth.com/',
          title: 'Give Now',
        },
      ]
    }
  },
}