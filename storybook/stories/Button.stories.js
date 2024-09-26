import twigTemplate from './template.twig';

export default {
    title: 'Example/TwigComponent',
};

const Template = (args) => twigTemplate(args);

export const Primary = Template.bind({});
Primary.args = {
    title: 'Hello, Storybook!',
    content: 'This is a Twig component integrated with Storybook.',
};
