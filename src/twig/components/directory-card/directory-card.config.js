module.exports = {
  title: "Directory Card",
  status: "wip",
  context: {
    type: 'people',
    image: {
      srcset: 'https://via.placeholder.com/600x600',
      src: 'https://via.placeholder.com/600x600',
      alt: 'Test alt',
    },
    name: 'Person Name',
    pronouns: 'He, Him',
    title: 'Technical Director',
    department: 'Theater and Dance',
    phone: '(207) 859-4522',
    curriculum_vitae: 'https://welcometruth.com/',
    email: 'John.ervin@colby.edu',
    location: {
      title: 'Runnals Building',
      url: 'https://welcometruth.com/',
    },
  },
  variants: [
    {
      name: 'Offices',
      context: {
        type: 'offices',
        name: 'Office Name',
        address: '1234 Main St. Waterville, ME 123456',
        email: 'Office@colby.edu',
        pronouns: null,
        curriculum_vitae: null,
      },
    }
  ]
}