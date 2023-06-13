module.exports = {
  title: "Full Bleed Hero",
  status: "wip",
  context: {
    type: 'dark',
    subheading: 'Alumni',
    heading: 'Once students, forever alumni.',
    paragraph: "Join our alumni network to connect with fellow graduates, mentor students, and expand your professional network.",
    buttons: [
      {
        url: 'https://welcometruth.com/',
        title: 'Why Join the Network?',
      },
    ],
    image: {
      srcset: 'https://via.placeholder.com/760x430',
      src: 'https://via.placeholder.com/760x430',
      alt: 'Test alt',
      caption: 'Caption lorem ipsum dolor sit amet, consectetur adipiscing elit. ',
    }
  },
  variants: [
    {
      name: 'Light',
      context: {
        type: 'light',
        subheading: 'Alumni Awards',
        heading: 'Recognizing outstanding alumni.',
        paragraph: 'See past winners and nominate someone deserving.',
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'All Awards',
          },
          {
            url: 'https://welcometruth.com/',
            title: 'Nominate Someone',
          },
        ]
      }
    }
  ]
}