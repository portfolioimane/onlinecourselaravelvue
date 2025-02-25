<template>
  <div>
    <!-- Check if course is available before rendering -->
    <div v-if="course">
      <h1>{{ course.title }}</h1>
      <p>{{ course.description }}</p>

      <h3>Lessons:</h3>
      <ul>
        <li v-for="lesson in course.lessons" :key="lesson.id">
       <router-link :to="{ name: 'lesson', params: { courseId: course.id, lessonId: lesson.id } }">
  {{ lesson.title }}
       </router-link>

        </li>
      </ul>
    </div>
    <!-- Optionally show loading or error message if course is not yet loaded -->
    <div v-else>
      <p>Loading course...</p>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'CoursePage',
  computed: {
    // Use mapGetters to get the course and loading state
    ...mapGetters({
      course: 'courses/selectedCourse',  // 'courses/selectedCourse' is the getter
      isLoading: 'courses/isLoading',    // Optional: You can also map loading state to show a loading indicator
    }),
  },
  created() {
    this.fetchCourseById(this.$route.params.courseId);  // Fetch course by ID when component is created
  },
  methods: {
    ...mapActions({
      fetchCourseById: 'courses/fetchCourseById',  // Map the action to fetch course by ID
    }),
  },
};
</script>

