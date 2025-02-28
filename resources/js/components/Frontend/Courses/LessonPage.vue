<template>
  <div class="lesson-page container py-5">
    <div v-if="lesson" class="row">
      <!-- Left Section: Video Lesson -->
      <div class="col-md-4 lesson-list">
        <h2>All Lessons</h2>
        <ul class="list-unstyled">
          <li v-for="lessonItem in lessons" :key="lessonItem.id">
            <router-link :to="{ name: 'lesson', params: { courseId, lessonId: lessonItem.id } }" class="d-flex align-items-center">
              <i class="fa fa-play-circle"></i> {{ lessonItem.title }}
            </router-link>
          </li>
        </ul>
      </div>

      <div class="col-md-8 lesson-video">
        <h1>{{ lesson.title }}</h1>
        <p>{{ lesson.content }}</p>
        <div v-if="lesson.video_embed_code" v-html="lesson.video_embed_code"></div>

        <!-- Navigation Buttons -->
        <div class="lesson-navigation d-flex justify-content-between mt-4">
          <router-link
            v-if="previousLessonId"
            :to="{ name: 'lesson', params: { courseId, lessonId: previousLessonId } }"
            class="btn btn-primary d-flex align-items-center"
          >
            <i class="fa fa-arrow-left"></i> Previous
          </router-link>

          <router-link
            v-if="nextLessonId"
            :to="{ name: 'lesson', params: { courseId, lessonId: nextLessonId } }"
            class="btn btn-primary d-flex align-items-center"
          >
            Next <i class="fa fa-arrow-right"></i>
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  name: 'LessonPage',
  computed: {
    ...mapGetters({
      lesson: 'courses/selectedLesson',
      lessons: 'courses/lessons',
    }),

    previousLessonId() {
      const currentIndex = this.lessons.findIndex(l => l.id === this.lesson.id);
      return currentIndex > 0 ? this.lessons[currentIndex - 1].id : null;
    },

    nextLessonId() {
      const currentIndex = this.lessons.findIndex(l => l.id === this.lesson.id);
      return currentIndex < this.lessons.length - 1 ? this.lessons[currentIndex + 1].id : null;
    },
  },
  created() {
    const { courseId, lessonId } = this.$route.params;
    this.fetchLesson({ courseId, lessonId });
    this.fetchLessons(courseId);
  },
  beforeRouteUpdate(to, from, next) {
    const { lessonId } = to.params;
    this.fetchLesson({ courseId: this.$route.params.courseId, lessonId });
    next();
  },
  methods: {
    ...mapActions({
      fetchLesson: 'courses/fetchLesson',
      fetchLessons: 'courses/fetchLessons',
    }),
  },
};
</script>

<style scoped>
.lesson-page {
  max-width: 1200px;
}

.lesson-video {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
}

.lesson-video h1 {
  font-size: 2rem;
  margin-bottom: 10px;
}

.lesson-video p {
  font-size: 1.1rem;
  margin-bottom: 20px;
}

.lesson-navigation {
  display: flex;
  justify-content: space-between;
  margin-top: 20px;
}

.btn-prev, .btn-next {
  background-color: #1E90FF;
  color: white;
  padding: 10px 20px;
  border-radius: 50px;
  text-decoration: none;
}

.btn-prev:hover, .btn-next:hover {
  background-color: #187bcd;
}

.lesson-list h2 {
  font-size: 1.5rem;
  margin-bottom: 20px;
}

.lesson-list li {
  margin-bottom: 10px;
}

.lesson-list a {
  text-decoration: none;
  color: #333;
  font-size: 1.1rem;
}

.lesson-list a:hover {
  color: #1E90FF;
}

.fa {
  font-size: 1.2rem;
}
</style>
