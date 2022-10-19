module.exports = {
  title: 'Dark Interstitial',
  status: 'wip',
  context: {
    icon: true,
    subheading: 'People & Place',
    paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed nec tincidunt est, eu semper lacus. Maecenas quis pretium est. In suscipit enim vestibulum enim viverra porttitor. Etiam sodales nibh eget magna egestas pretium. Nunc nibh est, molestie nec auctor vel, iaculis a metus.',
    buttons: [
      {
        url: 'https://welcometruth.com/',
        title: 'Colby Community',
      },
      {
        url: 'https://welcometruth.com/',
        title: 'Campus & Maine',
      },
    ],
    images: [
      {
        srcset: 'https://via.placeholder.com/360x440',
        src: 'https://via.placeholder.com/360x440',
        alt: 'Test alt',
        caption: 'Main campus in Waterville',
      },
      {
        srcset: 'https://via.placeholder.com/360x440',
        src: 'https://via.placeholder.com/360x440',
        alt: 'Test alt',
        caption: 'Graduation 2022',
      },
      {
        srcset: 'https://via.placeholder.com/360x440',
        src: 'https://via.placeholder.com/360x440',
        alt: 'Test alt',
        caption: 'Retreat house in Casco Bay',
      },
    ],
  },
  variants: [
    {
      name: 'Facts & Figures',
      context: {
        icon: null,
        subheading: 'Proof',
        heading: 'Stat headline lorem ipsum dolor sit amet.',
        paragraph: 'Integer eu odio nec mauris commodo porta. Integer lobortis semper velit, id semper magna consectetur id. Donec facilisis hendrerit quam at suscipit. Suspendisse finibus iaculis massa id suscipit. Aliquam a vehicula nunc, vel interdum nisi.',
        buttons: null,
        images: null,
        facts: [
          {
            figure: '57%',
            paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam congue pulvinar lectus.',
          },
          {
            figure: '57%',
            paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam congue pulvinar lectus.',
          },
        ],
      },
    },
  ],
}