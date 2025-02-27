<template>
  <div class="course-detail">
    <!-- Check if course is available before rendering -->
    <div v-if="course" class="course-content">
      <div class="course-header">
        <!-- Course Image -->
        <div v-if="course.image" class="course-image">
          <img :src="course.image" :alt="course.title" class="img-fluid" />
        </div>

        <div class="course-info">
          <h1 class="course-title">{{ course.title }}</h1>
          <p class="course-description">{{ course.description }}</p>

          <div class="course-meta">
            <span class="course-duration">
              <i class="fa fa-clock"></i> {{ formatDuration(course.duration) }}
            </span>
            <span class="course-price">
              <i class="fa fa-tag"></i> {{ course.price ? `${course.price} MAD` : 'Free' }}
            </span>
          </div>

          <h3 class="lessons-title">Lessons:</h3>
          <ul class="lesson-list">
            <li v-for="lesson in course.lessons" :key="lesson.id" class="lesson-item">
              <router-link :to="{ name: 'lesson', params: { courseId: course.id, lessonId: lesson.id } }" class="lesson-link">
                <i class="fa fa-play-circle"></i> {{ lesson.title }}
              </router-link>
            </li>
          </ul>

          <!-- Enroll Now Button -->
          <div class="enroll-button">
            <button @click="enrollNow" class="btn-enroll">
              <i class="fa fa-check"></i> Enroll Now
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Optionally show loading or error message if course is not yet loaded -->
    <div v-else class="loading">
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

    // Method to format the duration into a user-friendly string
    formatDuration(duration) {
      if (!duration) return 'Duration not specified';
      
      const hours = Math.floor(duration / 60);
      const minutes = duration % 60;
      let formattedDuration = '';
      
      if (hours > 0) {
        formattedDuration += `${hours} hour${hours > 1 ? 's' : ''}`;
      }
      if (minutes > 0) {
        if (formattedDuration) formattedDuration += ' ';
        formattedDuration += `${minutes} minute${minutes > 1 ? 's' : ''}`;
      }

      return formattedDuration || 'Duration not specified';
    },

    // Enroll Now action
    enrollNow() {
      alert("You have enrolled in this course!");
    },
  },
};
</script>

<style scoped>
.course-detail {
  padding: 40px;
  max-width: 100%;
  margin: 50px auto;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.course-content {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  gap: 30px;
  align-items: center;
}

.course-header {
  display: flex;
  flex-direction: row;
  align-items: center;
}

.course-image {
  flex: 1;
  margin-right: 30px;
}

.course-image img {
  width: 100%;
  max-width: 400px;
  height: auto;
  border-radius: 8px;
}

.course-info {
  flex: 2;
}

.course-title {
  font-size: 2rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 10px;
}

.course-description {
  font-size: 1.2rem;
  color: #555;
  margin-bottom: 20px;
}

.course-meta {
  display: flex;
  justify-content: space-between;
  margin-bottom: 20px;
}

.course-meta span {
  font-size: 1.1rem;
  color: #777;
}

.course-meta i {
  margin-right: 8px;
  color: #1E90FF;
}

.lessons-title {
  font-size: 1.5rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 15px;
}

.lesson-list {
  list-style-type: none;
  padding: 0;
}

.lesson-item {
  margin-bottom: 12px;
}

.lesson-link {
  text-decoration: none;
  font-size: 1.1rem;
  color: #333;
  display: flex;
  align-items: center;
}

.lesson-link i {
  margin-right: 10px;
  color: #1E90FF;
}

.lesson-link:hover {
  color: #1E90FF;
}

.enroll-button {
  margin-top: 30px;
}

.btn-enroll {
  background-color: #1E90FF;
  color: white;
  padding: 15px 25px;
  border: none;
  border-radius: 50px;
  font-size: 1.1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.3s ease;
}

.btn-enroll i {
  margin-right: 8px;
}

.btn-enroll:hover {
  background-color: #187bcd;
}

.loading {
  font-size: 1.5rem;
  color: #999;
  text-align: center;
}

.fa {
  font-size: 1.2rem;
}

</style>
