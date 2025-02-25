<template>
  <div>
    <h1>Courses</h1>

    <div v-if="isLoading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="!isLoading && !error">
      <ul>
        <li v-for="course in courses" :key="course.id">
          <router-link :to="{ name: 'course', params: { courseId: course.id } }">
            {{ course.title }}
          </router-link>
          <ul>
            <li v-for="lesson in course.lessons" :key="lesson.id">
              <router-link :to="{ name: 'lesson', params: { courseId: course.id, lessonId: lesson.id } }">
                {{ lesson.title }}
              </router-link>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'Courses',
  computed: {
    ...mapGetters('courses', {
      courses: 'allCourses',  // Mapping `allCourses` from Vuex getter to `courses` in the component
      isLoading: 'isLoading',  // Mapping `isLoading` from Vuex getter to `isLoading` in the component
      error: 'error',  // Mapping `error` from Vuex getter to `error` in the component
    }),
  },
  created() {
    this.fetchCourses();  // Dispatching the action to fetch courses
  },
  methods: {
    ...mapActions('courses', ['fetchCourses']),  // Mapping the `fetchCourses` action from Vuex
  },
};
</script>

<style scoped>
.error {
  color: red;
}
</style>
