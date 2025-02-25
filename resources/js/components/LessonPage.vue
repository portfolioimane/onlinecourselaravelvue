<template>
  <div>
    <!-- Check if lesson is available before rendering -->
    <div v-if="lesson">
      <h1>{{ lesson.title }}</h1>
      <p>{{ lesson.content }}</p>

      <div v-if="lesson.video_embed_code" v-html="lesson.video_embed_code"></div>
    </div>

    <!-- Show loading message if the lesson is still being fetched -->
    <div v-else>
      <p>Loading lesson...</p>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'LessonPage',
  computed: {
    // Use mapGetters to get the lesson and loading state
    ...mapGetters({
      lesson: 'courses/selectedLesson',  // Access the selected lesson from Vuex getters
    }),
  },
  created() {
    const { courseId, lessonId } = this.$route.params;  // Access the parameters from the route
    console.log(courseId, lessonId);  // Verify the values here (should print 1 1)

    // Call the Vuex action and pass both courseId and lessonId as an object
    this.fetchLesson({ courseId, lessonId });  // Pass the parameters as an object
  },
  methods: {
    ...mapActions({
      fetchLesson: 'courses/fetchLesson',  // Map the action to fetch the lesson
    }),
  },
};
</script>
