module.exports = {
    title: 'Inset Widget',
    status: 'wip',
    context: {
        size: 'medium',
        subheading: 'Submit a story',
        heading: 'Lorem ipsum dolor sit amet consectuer.',
        paragraph: 'Lorem ipsum dolor sit amet, consectetur adipi elit. Nunc lobortis ullamcorper.',
        buttons: [
            {
                url: 'https://welcometruth.com/',
                title: 'Submit a Story',
            },
        ],
    },
    variants: [
        {
            name: 'Disabled Inset',
            context: {
                inset: false,
            },
        },
    ],
};
