module.exports = {
  title: "Button",
  status: "wip",
  context: {
    href: 'https://welcometruth.com/',
    text: 'Active',
    type: 'light',
    size: 'xlarge',
  },
  variants: [
    {
      name: 'Large',
      context: {
        size: 'large',
      },
    },
    {
      name: 'Medium',
      context: {
        size: 'medium',
      },
    },
    {
      name: 'Small',
      context: {
        size: 'small',
      },
    },
    {
      name: 'XLarge Dark',
      context: {
        type: 'dark',
      },
    },
    {
      name: 'Large Dark',
      context: {
        type: 'dark',
        size: 'large',
      },
    },
    {
      name: 'Medium Dark',
      context: {
        type: 'dark',
        size: 'medium',
      },
    },
    {
      name: 'Small Dark',
      context: {
        type: 'dark',
        size: 'small',
      },
    },
    {
      name: 'XLarge Transparent',
      context: {
        transparentBg: true,
      },
    },
    {
      name: 'XLarge Dark Transparent',
      context: {
        type: 'dark',
        transparentBg: true,
      },
    },
    {
      name: 'XLarge Arrow',
      context: {
        arrow: true,
      },
    },
    {
      name: 'XLarge Dark Arrow',
      context: {
        type: 'dark',
        arrow: true,
      },
    },
    {
      name: 'XLarge Dark Arrow Reversed',
      context: {
        type: 'dark',
        arrow: true,
        reverse: true,
      },
    },
  ],
};