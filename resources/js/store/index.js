import { createStore } from 'vuex'; // Updated import
import courses from './modules/courses.js';

const store = createStore({ // Use createStore for Vue 3
  modules: {
    courses,
  },
});

export default store;

