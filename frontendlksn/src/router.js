import Vue from 'vue'
import VueRouter from 'vue-router';
import LoginComponent from './components/Auth/LoginComponent';
import DashboardComponent from './components/Dashboard/DashboardComponent';
import MainComponent from './components/Dashboard/MainComponent';

import MainDivisionComponent from './components/Dashboard/Division/MainDivisionComponent';
import DivisionComponent from './components/Dashboard/Division/DivisionComponent';
import EditDivisionComponent from './components/Dashboard/Division/EditDivisionComponent';
import StoreDivisionComponent from './components/Dashboard/Division/StoreDivisionComponent';

import MainPollComponent from './components/Dashboard/Poll/MainPollComponent';
import PollComponent from './components/Dashboard/Poll/PollComponent';
import StorePollComponent from './components/Dashboard/Poll/StorePollComponent';
import EditPollComponent from './components/Dashboard/Poll/EditPollComponent';
import ShowPollComponent from './components/Dashboard/Poll/ShowPollComponent';

import UserManagementComponent from './components/Dashboard/UM/UserManagementComponent';

import TrashComponent from './components/Dashboard/Trash/TrashComponent';

import UserDashboardComponent from './components/User/DashboardComponent';
import VoteComponent from './components/User/Poll/VoteComponent';

Vue.use(VueRouter)
export default new VueRouter({
    mode: 'history',
    routes: [
        {
            path: "/login",
            component: LoginComponent
        },
        {
            path: "/dashboard",
            component: MainComponent,
            meta: {
                requiresAuth: true,
                isAdmin: true,
            },
            children: [
                {
                    path: "/",
                    component: DashboardComponent,
                },
                {
                    path: "division",
                    component: MainDivisionComponent,
                    children: [
                        {
                            path: '/',
                            component: DivisionComponent,
                        },
                        {
                            path: "edit/:id",
                            component: EditDivisionComponent,
                        },
                        {
                            path: "create",
                            component: StoreDivisionComponent,
                        },
                    ]
                },
                {
                    path: "poll",
                    component: MainPollComponent,
                    children: [
                        {
                            path: '/',
                            component: PollComponent,
                        },
                        {
                            path: "create",
                            component: StorePollComponent,
                        },
                        {
                            path: "edit/:id",
                            component: EditPollComponent,
                        },
                        {
                            path: "show/:id",
                            component: ShowPollComponent,
                        },
                    ]
                },
                {
                    path: "usermanagement",
                    component: UserManagementComponent,
                },
                {
                    path: "trash",
                    component: TrashComponent,
                },
            ]
        },

        {
            path: "/user",
            component: UserDashboardComponent,
            meta: {
                requiresAuth: true,
                isUser: true,
            },
        },

        {
            path: "/user/poll/:id",
            component: VoteComponent,
            meta: {
                requiresAuth: true,
                isUser: true,
            },
        },
    ]
})