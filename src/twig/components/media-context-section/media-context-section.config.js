module.exports = {
  title: 'Media Context Section',
  status: 'wip',
  context: {
    items: [
      {
        size: 'medium',
        image: {
          srcset: 'https://via.placeholder.com/580x430',
          src: 'https://via.placeholder.com/580x430',
          alt: 'Test alt',
        },
        subheading: 'Alumni',
        heading: 'All about your alma mater.',
        paragraph: 'Colby is what it is because of brilliant alumni like yourself. Connect with your community today.',
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'Alumni',
          },
        ],
      },
      {
        size: 'medium',
        reverse: true,
        image: {
          srcset: 'https://via.placeholder.com/580x430',
          src: 'https://via.placeholder.com/580x430',
          alt: 'Test alt',
        },
        subheading: 'Families',
        heading: 'We’re all family here.',
        paragraph: 'Families are the heart of Colby. Explore all the ways to be involved in the community.',
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'Families',
          },
        ],
      },
    ]
  },
  variants: [
    {
      name: 'Narrow',
      context: {
        type: 'narrow',
        items: [
          {
            size: 'large',
            reverse: true,
            image: {
              srcset: 'https://via.placeholder.com/460x290',
              src: 'https://via.placeholder.com/460x290',
              alt: 'Test alt',
            },
            heading: '$4 million',
            paragraph: 'provided for student internships, global experiences, and research.',
          },
          {
            size: 'large',
            image: {
              srcset: 'https://via.placeholder.com/460x290',
              src: 'https://via.placeholder.com/460x290',
              alt: 'Test alt',
            },
            heading: '100%',
            paragraph: 'of demonstrated needs met without loans.',
          },
          {
            size: 'large',
            reverse: true,
            image: {
              srcset: 'https://via.placeholder.com/460x290',
              src: 'https://via.placeholder.com/460x290',
              alt: 'Test alt',
            },
            heading: '#16',
            paragraph: 'Most Innovative School per U.S. News & World Report.',
          },
          {
            size: 'large',
            image: {
              srcset: 'https://via.placeholder.com/460x290',
              src: 'https://via.placeholder.com/460x290',
              alt: 'Test alt',
            },
            heading: '$695 million',
            paragraph: 'Raised for our Dare Northward campaign.',
          },
        ]
      }
    }
  ]
}