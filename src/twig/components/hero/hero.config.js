module.exports = {
  title: "Hero",
  status: "wip",
  context: {
    subheading: 'Majors & Minors',
    heading: 'Lorem ipsum dolor sit amet consec dolor.',
    paragraph: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam congue pulvinar lectus.",
  },
  variants: [
    {
      name: 'Centered',
      context: {
        subheading: 'Academics',
        heading: 'Academics headline lorem ipsum dolor sit',
        paragraph: 'Educational exploration is a way of life at Colby. Academics are rigorous, and learning goes way beyond sitting in a classroom—it’s enhanced by new experiences, real-world research, and project-based problem solving across all disciplines.',
        align: 'center',
      },
    },
    {
      name: 'With Image',
      context: {
        image: {
          srcset: 'https://via.placeholder.com/600x600',
          src: 'https://via.placeholder.com/600x600',
          alt: 'Test alt',
          caption: 'Caption lorem ipsum dolor sit amet.',
        },
      },
    },
    {
      name: 'Centered with Images',
      context: {
        subheading: 'Academics',
        heading: 'Academics headline lorem ipsum dolor sit',
        paragraph: 'Educational exploration is a way of life at Colby. Academics are rigorous, and learning goes way beyond sitting in a classroom—it’s enhanced by new experiences, real-world research, and project-based problem solving across all disciplines.',
        align: 'center',
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
          {
            srcset: 'https://via.placeholder.com/600x600',
            src: 'https://via.placeholder.com/600x600',
            alt: 'Test alt',
            caption: 'Caption lorem ipsum dolor sit amet.',
          },
        ]
      },
    }
  ],
}