import { Ziggy } from './ziggy.generated';
import route from 'ziggy-js';

export { Ziggy };
export const ZiggyVue = {
    install: (app: any) => {
        app.config.globalProperties.$route = (name: string, params?: any, absolute?: boolean) => route(name, params, absolute);
    }
};