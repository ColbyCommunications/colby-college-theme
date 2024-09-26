/** @type { import('@storybook/html-webpack5').StorybookConfig } */
const config = {
    stories: ['../stories/**/*.mdx', '../stories/**/*.stories.@(js|jsx|mjs|ts|tsx)'],
    addons: [
        '@storybook/addon-webpack5-compiler-swc',
        '@storybook/addon-links',
        '@storybook/addon-essentials',
        '@chromatic-com/storybook',
        '@storybook/addon-interactions',
    ],
    framework: {
        name: '@storybook/html-webpack5',
        options: {},
    },
    webpackFinal: async (config) => {
        config.module.rules.push({
            test: /\.twig$/,
            use: [
                {
                    loader: 'twig-loader',
                    options: {
                        // Add any Twig specific options if necessary
                    },
                },
            ],
        });

        return config;
    },
};
export default config;
