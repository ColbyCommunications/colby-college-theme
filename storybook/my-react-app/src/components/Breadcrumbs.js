import breadcrumbsTemplate from '../twig/breadcrumbs.twig';

const Breadcrumbs = (args) => {
  return breadcrumbsTemplate({
    ...args,
  });
};

export default Breadcrumbs;