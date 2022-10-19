module.exports = {
  title: "Image Grid",
  status: "wip",
  context: {
    images: [
      {
        srcset: 'https://via.placeholder.com/600x600',
        src: 'https://via.placeholder.com/600x600',
        alt: 'Test alt',
        caption: 'Caption lorem ipsum dolor sit amet.',
      },
      {
        srcset: 'https://via.placeholder.com/600x600',
        src: 'https://via.placeholder.com/600x600',
        alt: 'Test alt',
        caption: 'Caption lorem ipsum dolor sit amet.',
      },
    ],
  },
  variants: [
    {
      name: 'No Caption',
      context: {
        images: [
          {
            srcset: 'https://via.placeholder.com/600x600',
            src: 'https://via.placeholder.com/600x600',
            alt: 'Test alt',
          },
          {
            srcset: 'https://via.placeholder.com/600x600',
            src: 'https://via.placeholder.com/600x600',
            alt: 'Test alt',
          },
        ],
      }
    },
    {
      name: 'Rectangle Orientation',
      context: {
        orientation: 'rectangle',
        images: [
          {
            srcset: 'https://via.placeholder.com/600x600',
            src: 'https://via.placeholder.com/600x600',
            alt: 'Test alt',
          },
          {
            srcset: 'https://via.placeholder.com/600x600',
            src: 'https://via.placeholder.com/600x600',
            alt: 'Test alt',
          },
        ],
      }
    },
    {
      name: 'Landscape Orientation',
      context: {
        orientation: 'landscape',
        images: [
          {
            srcset: 'https://via.placeholder.com/880x430',
            src: 'https://via.placeholder.com/600x600',
            alt: 'Test alt',
          },
        ],
      }
    }
  ]
}