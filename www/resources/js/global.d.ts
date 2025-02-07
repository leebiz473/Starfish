/// <reference types="vite/client" />

// Import Ziggy's RouteParams and Config
import { RouteParamsWithQueryOverload, Config } from 'ziggy-js';

// Declare global window properties used in Laravel
declare global {
    interface Window {
        Ziggy: Config; // Ensures TypeScript recognizes `window.Ziggy`
        Laravel: {
            csrfToken: string; // Stores the CSRF token from Laravel
            user?: { id: number; name: string; email: string }; // Example user object (if authenticated)
        };
    }

    // Make `route()` function globally available
    function route(
        name: keyof typeof window.Ziggy.routes,
        params?: RouteParamsWithQueryOverload | string | number,
        absolute?: boolean
    ): string;
}

// Ensure TypeScript recognizes this file as a module
export {};
