module.exports = {
  title: 'Media Aside',
  status: 'wip',
  context: {
    image: {
      srcset: 'https://via.placeholder.com/900x430',
      src: 'https://via.placeholder.com/600x600',
      alt: 'Test alt',
      caption: 'Caption lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    },
    heading: 'Lorem ipsum dolor sit amet, consectet lorem ipsum dolor sit.',
    paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec lectus felis, interdum vel lorem vitae, imperdiet commodo nisi. Donec nec blandit enim.',
    buttons: [
      {
        url: 'https://welcometruth.com/',
        title: 'Read Story',
      },
    ],
  },
  variants: [
    {
      name: 'Carousel',
      context: {
        carousel: true,
        items: [
          {
            image: {
              srcset: 'https://via.placeholder.com/900x430',
              src: 'https://via.placeholder.com/600x600',
              alt: 'Test alt',
              caption: 'Caption lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            },
            heading: 'Lorem ipsum dolor sit amet, consectet lorem ipsum dolor sit.',
            paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec lectus felis, interdum vel lorem vitae, imperdiet commodo nisi. Donec nec blandit enim.',
            buttons: [
              {
                url: 'https://welcometruth.com/',
                title: 'Read Story',
              },
            ],
          },
          {
            image: {
              srcset: 'https://via.placeholder.com/900x430',
              src: 'https://via.placeholder.com/600x600',
              alt: 'Test alt',
              caption: 'Caption lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            },
            heading: 'Lorem II ipsum dolor sit amet, consectet lorem ipsum dolor sit.',
            paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec lectus felis, interdum vel lorem vitae, imperdiet commodo nisi. Donec nec blandit enim.',
            buttons: [
              {
                url: 'https://welcometruth.com/',
                title: 'Read Story',
              },
            ],
          },
          {
            image: {
              srcset: 'https://via.placeholder.com/900x430',
              src: 'https://via.placeholder.com/600x600',
              alt: 'Test alt',
              caption: 'Caption lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            },
            heading: 'Lorem ipsum III dolor sit amet, consectet lorem ipsum dolor sit.',
            paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec lectus felis, interdum vel lorem vitae, imperdiet commodo nisi. Donec nec blandit enim.',
            buttons: [
              {
                url: 'https://welcometruth.com/',
                title: 'Read Story',
              },
            ],
          },
        ]
      }
    }
  ]
}