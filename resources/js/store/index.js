import { createStore } from 'vuex'; // Updated import
import courses from './modules/courses.js';
import auth from './modules/auth.js';
import keys from './modules/keys.js';
import paymentSetting from './modules/backend/paymentSetting.js';
import backendHomePageHeader from './modules/backend/HomePageHeader.js';
import backendUsers from './modules/backend/users.js';
import backendGeneralCustomize from './modules/backend/generalCustomize.js';
import generalCustomize from './modules/generalcustomize.js';
import contact from './modules/contact.js';
import backendContact from './modules/backend/contact.js';
import backendCourses from './modules/backend/courses.js';
import backendLessons from './modules/backend/lessons.js';
import enrollment from './modules/enrollment.js';


const store = createStore({ // Use createStore for Vue 3
  modules: {
    courses,
    auth,
    paymentSetting,
    keys,
    backendHomePageHeader,
    backendUsers,
    backendGeneralCustomize,
    generalCustomize,
    contact,
    backendContact,
    backendCourses,
    backendLessons,
    enrollment,


  },
});

export default store;

