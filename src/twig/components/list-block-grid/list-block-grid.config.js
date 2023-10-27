module.exports = {
  title: 'List Block Grid',
  status: 'wip',
  context: {
    items: [
      {
        subheading: 'Bylaws',
        heading: 'Lorem ipsum dolor sit amet, consectet lorem ipsum',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipi elit. Nunc lobortis ullamcorper aucto.',
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'Read Bylaws',
          },
        ]
      },
      {
        subheading: 'Nominate a Council member',
        heading: 'Lorem ipsum dolor sit amet, consectet lorem ipsum',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipi elit. Nunc lobortis ullamcorper aucto.',
        buttons: [
          {
            url: 'https://welcometruth.com/',
            title: 'Nominate Someone',
          },
        ]
      }
    ]
  },
  variants: [
    {
      name: '3 Columns',
      context: {
        columns: 3,
        type: 'dark',
        items: [
          {
            heading: 'Blue Key Award',
            paragraph: 'Lorem ipsum dolor sit amet, consectetur adipi elit. Nunc lobortis ullamcorper aucto.',
            buttons: [
              {
                url: 'https://welcometruth.com/',
                title: 'More',
              },
            ]
          },
          {
            heading: 'Brick Award',
            paragraph: 'Lorem ipsum dolor sit amet, consectetur adipi elit. Nunc lobortis ullamcorper aucto.',
            buttons: [
              {
                url: 'https://welcometruth.com/',
                title: 'More',
              },
            ]
          },
          {
            heading: 'Distinguished Alumnus Award',
            paragraph: 'Lorem ipsum dolor sit amet, consectetur adipi elit. Nunc lobortis ullamcorper aucto.',
            buttons: [
              {
                url: 'https://welcometruth.com/',
                title: 'More',
              },
            ]
          },
        ]
      }
    },
    {
      name: '4 Columns',
      context: {
        columns: 4,
        type: 'dark',
        items: [
          {
            heading: 'Boston',
            paragraph: 'Contact Boston Volunteer',
          },
          {
            heading: 'New York City',
            paragraph: 'Contact New York Volunteer',
          },
          {
            heading: 'Washington DC',
            paragraph: 'Contact DC Volunteer',
          },
          {
            heading: 'Chicago',
            paragraph: 'Contact Chicago Volunteer',
          },
          {
            heading: 'San Francisco',
            paragraph: 'Contact SF Volunteer',
          },
          {
            heading: 'Portland, ME',
            paragraph: 'Contact Portland Volunteer',
          },
          {
            heading: 'Denver',
            paragraph: 'Contact Denver Volunteer',
          },
          {
            heading: 'Los Angeles',
            paragraph: 'Contact LA Volunteer',
          },
        ]
      }
    }
  ]
}