import React from 'react';
import BreadcrumbsComponent from '../components/Breadcrumbs';
import RawHTML from '../utilities/RawHTML';

export default { title: 'Components/Navigation/Breadcrumbs' };

export const Breadcrumbs = (args) => {
    const breadcrumbs = <BreadcrumbsComponent {...args} />;
    return <RawHTML component={breadcrumbs} />;
};

Breadcrumbs.args = {
    links: [
        {
            title: 'Home',
            url: '#home',
        },
        {
            title: 'Admissions & Cost',
            url: '#admissions',
        },
        {
            title: 'Tertiary Link',
            url: '#tertiary',
        },
    ],
};
