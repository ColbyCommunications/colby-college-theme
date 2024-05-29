module.exports = {
  title: 'Overlay Hero',
  status: 'wip',
  context: {
    subheading: 'Person Title',
    heading: 'Person Name',
    paragraph: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam congue pulvinar lectus.',
    buttons: [
      {
        url: 'https://welcometruth.com/',
        title: 'Read Full Bio',
      },
    ],
    video_loop: 'https://experience.brynathyn.edu/wp-content/themes/bryn-athyn/dist/videos/videoplayback2.mp4',
  },
  variants: [
    {
      name: 'With Image',
      context: {
        video_loop: null,
        image: {
          srcset: 'https://scontent-lga3-1.xx.fbcdn.net/v/t1.6435-9/91178111_10163414111835245_2367827633632182272_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=e3f864&_nc_ohc=2cOMYARLyTcAX-diKsQ&_nc_ht=scontent-lga3-1.xx&oh=00_AT_RBmCeiyy73ckwKFqpYNtborIo_rLd4H20bDTgMyuXgA&oe=62EFB4F5',
          src: 'https://scontent-lga3-1.xx.fbcdn.net/v/t1.6435-9/91178111_10163414111835245_2367827633632182272_n.jpg?_nc_cat=104&ccb=1-7&_nc_sid=e3f864&_nc_ohc=2cOMYARLyTcAX-diKsQ&_nc_ht=scontent-lga3-1.xx&oh=00_AT_RBmCeiyy73ckwKFqpYNtborIo_rLd4H20bDTgMyuXgA&oe=62EFB4F5',
          alt: 'Test alt',
        }
      },
    },
    {
      name: 'With Video',
      context: {
        subheading: 'Visit',
        heading: 'Dare Northward',
        paragraph: 'Your first step on campus may be the best one you ever take.',
        image: {
          srcset: 'https://via.placeholder.com/480x290',
          src: 'https://via.placeholder.com/480x290',
          alt: 'Test alt',
        },
        video: {
          id: '2EgmaMYxzA8'
        },
      },
    },
  ]
};