import React from 'react';
import { renderToStaticMarkup } from 'react-dom/server';
import { unescape as unescapeHtml } from 'html-escaper';

const RawHTML = (args) => {
  let html = '';

  if (args.component) {
    if (React.isValidElement(args.component)) {
      html = unescapeHtml(renderToStaticMarkup(args.component));
    } else if (typeof args.component === 'string') {
      html = unescapeHtml(args.component);
    }
  }

  if (!html && typeof args.componentFunction === 'function') {
    html = args.componentFunction({
      ...args,
    });
  }

  if (args.return === 'string') {
    if (typeof html !== 'string') {
      html = html = unescapeHtml(renderToStaticMarkup(html));
    }

    return html;
  }

  return (
    <div
      dangerouslySetInnerHTML={{
        __html: html,
      }}
    />
  );
};

export default RawHTML;
