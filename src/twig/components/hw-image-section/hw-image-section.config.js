module.exports = {
  title: 'Half Width Image Section',
  status: 'wip',
  context: {
    image: {
      srcset: 'https://via.placeholder.com/760x430',
      src: 'https://via.placeholder.com/480x480',
      alt: 'Test alt',
    },
    heading: 'Majors and Minors',
    paragraph: 'Choose from 56 majors and 35 minors, or design your own independent major. You’ll have extensive flexibility and valuable guidance when it comes to preparing for your future.',
    buttons: [
      {
        url: 'https://welcometruth.com/',
        title: 'Majors and Minor at Colby',
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
  ]
}
