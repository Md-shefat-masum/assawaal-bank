import Vue from 'vue'
import VueRouter from 'vue-router'

import Layout from './views/Layout'
import Dashboard from './views/Dashboard'

import chapterLayout from './views/chapter/Layout'
import chapterAll from './views/chapter/All'
import chapterCreate from './views/chapter/Create'
import chapterImport from './views/chapter/Import'
import chapterEdit from './views/chapter/Edit'
import chapterDetails from './views/chapter/Details'

import examLayout from './views/exam/Layout'
import examAll from './views/exam/All'
import examCreate from './views/exam/Create'
import examImport from './views/exam/Import'
import examEdit from './views/exam/Edit'
import examDetails from './views/exam/Details'

import moduleLayout from './views/module/Layout'
import moduleAll from './views/module/All'
import moduleCreate from './views/module/Create'
import moduleImport from './views/module/Import'
import moduleEdit from './views/module/Edit'
import moduleDetails from './views/module/Details'

import profileLayout from './views/profile/Layout'
import profileAll from './views/profile/All'
import profileCreate from './views/profile/Create'
import profileEdit from './views/profile/Edit'

import questionLayout from './views/question/Layout'
import questionAll from './views/question/All'
import questionCreate from './views/question/Create'
import questionImport from './views/question/Import'
import questionEdit from './views/question/Edit'
import questionUsedDetails from './views/question/UsedDetails'

import questionPaperLayout from './views/question_paper/Layout'
import questionPaperAll from './views/question_paper/All'
import questionPaperCreate from './views/question_paper/Create'
import questionPaperImport from './views/question_paper/Import'
import questionPaperEdit from './views/question_paper/Edit'
import questionPaperDetails from './views/question_paper/Details'


Vue.use(VueRouter);
window.Fire = new Vue();

const routes = [{
    path: '/',
    component: Layout,
    children: [{
            path: '',
            name: 'Dashboard',
            component: Dashboard,
        },
        {
            path: 'chapter',
            component: chapterLayout,
            children: [
                {
                    path: '',
                    name: 'chapterAll',
                    component: chapterAll,
                },
                {
                    path: 'create',
                    name: 'chapterCreate',
                    component: chapterCreate,
                },
                {
                    path: 'import',
                    name: 'chapterImport',
                    component: chapterImport,
                },
                {
                    path: 'edit/:id',
                    name: 'chapterEdit',
                    component: chapterEdit,
                },
                {
                    path: 'details/:id',
                    name: 'chapterDetails',
                    component: chapterDetails,
                },
            ],
        },

        {
            path: 'exam',
            component: examLayout,
            children: [
                {
                    path: '',
                    name: 'examAll',
                    component: examAll,
                },
                {
                    path: 'create',
                    name: 'examCreate',
                    component: examCreate,
                },
                {
                    path: 'import',
                    name: 'examImport',
                    component: examImport,
                },
                {
                    path: 'edit/:id',
                    name: 'examEdit',
                    component: examEdit,
                },
                {
                    path: 'details/:id',
                    name: 'examDetails',
                    component: examDetails,
                },
            ],
        },
        {
            path: 'module',
            component: moduleLayout,
            children: [
                {
                    path: '',
                    name: 'moduleAll',
                    component: moduleAll,
                },
                {
                    path: 'create',
                    name: 'moduleCreate',
                    component: moduleCreate,
                },
                {
                    path: 'import',
                    name: 'moduleImport',
                    component: moduleImport,
                },
                {
                    path: 'edit/:id',
                    name: 'moduleEdit',
                    component: moduleEdit,
                },
                {
                    path: 'details/:id',
                    name: 'moduleDetails',
                    component: moduleDetails,
                },
            ],
        },

        {
            path: 'profile',
            component: profileLayout,
            children: [
                {
                    path: '',
                    name: 'profileAll',
                    component: profileAll,
                },
                {
                    path: 'create',
                    name: 'profileCreate',
                    component: profileCreate,
                },
                {
                    path: 'edit/:id',
                    name: 'profileEdit',
                    component: profileEdit,
                },
            ],
        },

        {
            path: 'question',
            component: questionLayout,
            children: [
                {
                    path: '',
                    name: 'questionAll',
                    component: questionAll,
                },
                {
                    path: 'type/:type',
                    name: 'questionType',
                    component: questionAll,
                },
                {
                    path: 'create',
                    name: 'questionCreate',
                    component: questionCreate,
                },
                {
                    path: 'import',
                    name: 'questionImport',
                    component: questionImport,
                },
                {
                    path: 'edit/:id',
                    name: 'questionEdit',
                    component: questionEdit,
                },
                {
                    path: 'question-used-details/:id',
                    name: 'questionUsedDetails',
                    component: questionUsedDetails,
                },
            ],
        },

        {
            path: 'question-paper',
            component: questionPaperLayout,
            children: [
                {
                    path: '',
                    name: 'questionPaperAll',
                    component: questionPaperAll,
                },
                {
                    path: 'create',
                    name: 'questionPaperCreate',
                    component: questionPaperCreate,
                },
                {
                    path: 'import',
                    name: 'questionPaperImport',
                    component: questionPaperImport,
                },
                {
                    path: 'edit/:id',
                    name: 'questionPaperEdit',
                    component: questionPaperEdit,
                },
                {
                    path: 'details/:id',
                    name: 'questionPaperDetails',
                    component: questionPaperDetails,
                },
            ],
        },

    ]
}, ];

const burger_maker_management_router = new VueRouter({
    routes,
    mode: 'hash',
    linkExactActiveClass: 'active',
    // scrollBehavior: function(to, from, savedPosition) {
    //     return { x: 0, y: 0 }
    // }
});

burger_maker_management_router.beforeEach((to, from, next) => {
    // let isAuthenticated = window.localStorage?.token?.length ? true : false;
    // if (isAuthenticated) {
    //     window.axios.defaults.headers.common["Authorization"] = `Bearer ${window.localStorage?.token}`;
    // } else {
    //     window.axios.defaults.headers.common["Authorization"] = null;
    // }

    // if (isAuthenticated == false) {
    //     next({
    //         to: '/login'
    //     })
    // } else {
    //     next()
    // }
    next()
})

export default burger_maker_management_router
