import axios from '../../utils/axios.js';

const state = {
    courses: [],
    selectedCourse: null,
    selectedLesson: null,
    lessons: [], // Add lessons array here
    loading: false,
    error: null,
    watchedLessons: [],  // New state for watched lessons
};

const getters = {
    allCourses: (state) => state.courses,
    selectedCourse: (state) => state.selectedCourse,
    selectedLesson: (state) => state.selectedLesson,
    lessons: (state) => state.lessons,  // Add getter for lessons
    isLoading: (state) => state.loading,
    error: (state) => state.error,
    watchedLessons: (state) => state.watchedLessons,  // Getter for watched lessons
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
    // New fetchLessons action to fetch all lessons for a course
    async fetchLessons({ commit }, courseId) {
        commit('setLoading', true);
        commit('setError', null);

        try {
            const response = await axios.get(`/courses/${courseId}/lessons`); // Fetch all lessons for a specific course
            commit('setLessons', response.data);
        } catch (error) {
            commit('setError', error.response ? error.response.data.message : error.message);
        } finally {
            commit('setLoading', false);
        }
    },
   markLessonAsWatched({ commit }, lessonId) {
        commit('addWatchedLesson', lessonId);
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
    setLessons(state, lessons) {  // Add mutation to set lessons
        state.lessons = lessons;
    },
    setLoading(state, loading) {
        state.loading = loading;
    },
    setError(state, error) {
        state.error = error;
    },
    addWatchedLesson(state, lessonId) {
        if (!state.watchedLessons.includes(lessonId)) {
            state.watchedLessons.push(lessonId);
        }
    }
};

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations,
};
