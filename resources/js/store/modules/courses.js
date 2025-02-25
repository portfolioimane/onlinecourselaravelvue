import axios from '../../utils/axios.js';

const state = {
    courses: [],
    selectedCourse: null,
    selectedLesson: null,
    loading: false,
    error: null,
};

const getters = {
    allCourses: (state) => state.courses,
    selectedCourse: (state) => state.selectedCourse,
    selectedLesson: (state) => state.selectedLesson,
    isLoading: (state) => state.loading,
    error: (state) => state.error,
};

const actions = {
    async fetchCourses({ commit }) {
        commit('setLoading', true);
        commit('setError', null);
        try {
            const response = await axios.get('/courses');  // The endpoint for fetching courses
            commit('setCourses', response.data);
        } catch (error) {
            commit('setError', error.response ? error.response.data.message : error.message);
        } finally {
            commit('setLoading', false);
        }
    },
    async fetchCourseById({ commit }, courseId) {
        commit('setLoading', true);
        commit('setError', null);

        try {
            const response = await axios.get(`/courses/${courseId}`);
            commit('setSelectedCourse', response.data);
        } catch (error) {
            commit('setError', error.response ? error.response.data.message : error.message);
        } finally {
            commit('setLoading', false);
        }
    },
    async fetchLesson({ commit }, { courseId, lessonId }) {
        commit('setLoading', true);
        commit('setError', null);
        console.log(courseId, lessonId);  // Check the values of courseId and lessonId


        try {
            const response = await axios.get(`/courses/${courseId}/lessons/${lessonId}`);
            commit('setSelectedLesson', response.data);
        } catch (error) {
            commit('setError', error.response ? error.response.data.message : error.message);
        } finally {
            commit('setLoading', false);
        }
    },
};

const mutations = {
    setCourses(state, courses) {
        state.courses = courses;
    },
    setSelectedCourse(state, course) {
        state.selectedCourse = course;
    },
    setSelectedLesson(state, lesson) {
        state.selectedLesson = lesson;
    },
    setLoading(state, loading) {
        state.loading = loading;
    },
    setError(state, error) {
        state.error = error;
    },
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
