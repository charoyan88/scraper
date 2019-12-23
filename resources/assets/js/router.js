import VueRouter from 'vue-router'
// Pages
import Home from './components/Home'
import Shop from './components/Shop'
// Routes
const routes = [
    {
        path: '/',
        name: 'home',
        component: Home,
    },
    {
        path: '/shop',
        name: 'shop',
        component: Shop,
    },
];
const router = new VueRouter({
    history: true,
    mode: 'history',
    routes,
});
export default router/**
 * Created by HelpComp on 12/23/2019.
 */
