import { createRouter, createWebHistory } from 'vue-router';
import store from '../store/index.js';
import CoursesList from '../components/Frontend/Courses/CoursesList.vue';
import CoursePage from '../components/Frontend/Courses/CoursePage.vue';
import LessonPage from '../components/Frontend/Courses/LessonPage.vue';

// Import the layout
import AppLayout from '../components/Frontend/Layout/AppLayout.vue';
import AdminLayout from '../components/Admin/Layout/AdminLayout.vue'; // Admin Layout

// Import route components
import Login from '../components/Auth/Login.vue';
import Register from '../components/Auth/Register.vue';
import ResetPassword from '../components/Frontend/ResetPassword.vue';
import ForgotPassword from '../components/Frontend/ForgotPassword.vue';
import Profile from '../components/Frontend/Profile.vue';
import Contact from '../components/Frontend/Contact.vue';

import CustomerLayout from '../components/Frontend/CustomerLayout.vue';
import Home from '../components/Frontend/Home.vue';


// Admin Components
import AdminDashboard from '../components/Admin/Dashboard/AdminDashboard.vue';




import PaymentSetting from '../components/Admin/Settings/PaymentSetting.vue';

import GeneralCustomize from '../components/Admin/Customize/GeneralCustomize.vue';


import HomePageHeader from '../components/Admin/Customize/HomePageHeader.vue';

import Customers from '../components/Admin/Customers/Customers.vue';
import ContactMessages from '../components/Admin/Customers/ContactMessages.vue';

import Courses from '../components/Admin/Courses/Courses.vue';
import AddCourse from '../components/Admin/Courses/AddCourse.vue';
import EditCourse from '../components/Admin/Courses/EditCourse.vue';








const routes = [
  
  {
    path: '/',
    component: AppLayout,
    children: [
       { path: 'coursesList',
         name: 'coursesList',
         component: CoursesList 
       },
       { path: '/course/:courseId',
        name: 'course',
         component: CoursePage 
       },
       { path: '/course/:courseId/lesson/:lessonId',
        name: 'lesson', 
      component: LessonPage
       },
      {
        name: 'Login',
        path: '/login',
        component: Login,
      },
      {
        name: 'Register',
        path: '/register',
        component: Register,
      },
      {
        name: 'ResetPassword',
        path: '/password/reset/:token',
        component: ResetPassword,
        props: true,
      },
      {
        name: 'ForgotPassword',
        path: '/forgotpassword',
        component: ForgotPassword,
        props: true,
      },
      {
        name: 'Home',
        path: '/',
        component: Home,
      },
     
  
      {
        name: 'Contact',
        path: '/contact',
        component: Contact,
      },
      
      {
        path: '/customerdashboard',
        component: CustomerLayout,
        meta: { requiresAuth: true },
        children: [
          {
            name: 'Profile',
            path: 'profile',
            component: Profile,
          },
          
              
        ],
      },
    ],
  },
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAdmin: true },
    children: [
      {
        name: 'AdminDashboard',
        path: 'dashboard',
        component: AdminDashboard,
      },
            {
        name: 'Courses',
        path: 'courses',
        component: Courses,
      },

      {
        name: 'AddCourses',
        path: 'courses/add',
        component: AddCourse,
      },

          {
        path: 'courses/edit/:id',
        name: 'EditCourse',
        component: EditCourse,
      },
     
               {
        name: 'Customers',
        path: 'customers',
        component: Customers,
      },

{
  path: 'contact-messages',
  name: 'ContactMessages',
  component: ContactMessages,
},
      
       {
        name: 'PaymentSetting',
        path: 'paymentsetting',
        component: PaymentSetting,
      },
              {
        name: 'GeneralCustomize',
        path: 'generalcustomize',
        component: GeneralCustomize,
      },
      {
        name: 'HomePageHeader',
        path: 'customize/homepageheader',
        component: HomePageHeader,
      },


           
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
  
});

router.beforeEach(async (to, from, next) => {
  const isAuthenticated = store.getters['auth/isAuthenticated'];
  const user = store.getters['auth/user']; // Fetch the user from Vuex store
  const authChecked = store.getters['auth/authChecked']; // Get auth check status

  if (!authChecked) {
    // Wait until the auth check is finished
    return next();
  }

  if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
    next({ path: '/login' });
  } else if (to.matched.some(record => record.meta.requiresAdmin)) {
    if (!user || user.role !== 'admin') {
      next({ path: '/' });
    } else {
      next();
    }
  } else {
    next();
  }
});



export default router;

