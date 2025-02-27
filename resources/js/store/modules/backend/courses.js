import axios from '../../../utils/axios.js';

const state = {
  courses: [],
  course: {
    id: null,
    title: '',
    description: '',
    price: 0.0,
    slug: '',
    image: null,
    featured: false, // Added featured property
  },
};

const mutations = {
  SET_COURSES(state, courses) {
    state.courses = courses;
  },
  SET_COURSE(state, course) {
    state.course = { ...course }; // Spread the course object to ensure a fresh copy
  },
  UPDATE_COURSE(state, updatedCourse) {
    const index = state.courses.findIndex(course => course.id === updatedCourse.id);
    if (index !== -1) {
      state.courses.splice(index, 1, { ...updatedCourse });
    }
  },
  TOGGLE_FEATURED(state, courseId) {
    const course = state.courses.find(course => course.id === courseId);
    if (course) {
      course.featured = !course.featured; // Toggle featured status
    }
  },
};

const actions = {
  async fetchCourses({ commit }) {
    try {
      const response = await axios.get('/admin/courses');
      commit('SET_COURSES', response.data);
      console.log('courses', response.data);
    } catch (error) {
      console.error('Error fetching courses:', error);
    }
  },
  async fetchCourseById({ commit }, courseId) {
    try {
      const response = await axios.get(`/admin/courses/${courseId}`);
      commit('SET_COURSE', response.data);
    } catch (error) {
      console.error('Error fetching course:', error);
    }
  },
  async addCourse({ dispatch }, formData) {
    try {
      await axios.post('/admin/courses', formData, {
        headers: {
          'Content-Type': 'multipart/form-data', // Ensure the header supports FormData
        },
      });

      dispatch('fetchCourses');
    } catch (error) {
      console.error('Error adding course:', error);
    }
  },
  async updateCourse({ dispatch }, { id, formData }) {
    try {
      await axios.post(`/admin/courses/${id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        params: {
          _method: 'PUT', // Correct usage of PUT for update
        },
      });

      dispatch('fetchCourses');
    } catch (error) {
      console.error('Error updating course:', error);
    }
  },
  async deleteCourse({ dispatch }, courseId) {
    try {
      await axios.delete(`/admin/courses/${courseId}`);
      dispatch('fetchCourses');
    } catch (error) {
      console.error('Error deleting course:', error);
    }
  },
  async toggleFeatured({ commit, dispatch }, courseId) {
    try {
      // Toggle featured status on the server
      await axios.put(`/admin/courses/${courseId}/toggle-featured`);
      
      // Update the local state
      commit('TOGGLE_FEATURED', courseId);
      
      // Optionally refetch courses if the server response isn't updated
      dispatch('fetchCourses');
    } catch (error) {
      console.error('Error toggling featured course:', error);
    }
  },
};

const getters = {
  allCourses: (state) => state.courses,
  currentCourse: (state) => state.course,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
