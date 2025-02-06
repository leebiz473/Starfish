import ReactDOMServer from 'react-dom/server';
import { createInertiaApp } from '@inertiajs/react';
import createServer from '@inertiajs/react/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { route } from 'ziggy-js';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: ReactDOMServer.renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
        setup: ({ App, props }) => {
            const ziggy = page.props.ziggy || window.Ziggy;

            const location = ziggy.location ? String(ziggy.location) : undefined;

            global.route = (name: string | number | symbol, params?: any, absolute?: boolean): string => {
                return String(route(name, params, absolute, { ...ziggy, location }));
            };

            return <App {...props} />;
        },
    })
);
