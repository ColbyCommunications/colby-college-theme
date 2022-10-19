module.exports = {
  title: 'Video',
  status: 'wip',
  context: {
    image: {
      srcset: 'https://via.placeholder.com/480x290',
      src: 'https://via.placeholder.com/480x290',
      alt: 'Test alt',
    },
    video: {
      id: '2EgmaMYxzA8',
    },
  },
  variants: [
    {
      name: 'With Loop',
      context: {
        loop: 'https://experience.brynathyn.edu/wp-content/themes/bryn-athyn/dist/videos/videoplayback2.mp4',
      },
    }
  ]
}