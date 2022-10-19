module.exports = {
  title: 'Home Hero',
  status: 'wip',
  context: {
    heading: 'Colby dares to bring audacious thinking to life.',
    caption: 'Mayflower Hill',
    buttons: [
      {
        href: 'https://welcometruth.com/',
        title: 'Discover Academics',
      },
    ],
    video: 'https://experience.brynathyn.edu/wp-content/themes/bryn-athyn/dist/videos/videoplayback2.mp4',
  },
  variants: [
    {
      name: 'With Image',
      context: {
        video: null,
        image: {
          src: 'https://scontent-lga3-1.xx.fbcdn.net/v/t1.6435-9/91178111_10163414111835245_2367827633632182272_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=e3f864&_nc_ohc=2cOMYARLyTcAX-diKsQ&_nc_ht=scontent-lga3-1.xx&oh=00_AT_RBmCeiyy73ckwKFqpYNtborIo_rLd4H20bDTgMyuXgA&oe=62EFB4F5',
          alt: 'Test alt',
        }
      },
    }
  ]
};