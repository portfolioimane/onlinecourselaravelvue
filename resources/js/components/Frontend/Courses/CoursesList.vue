<template>
  <div>
    <h1>Popular Online Courses</h1>

    <div v-if="isLoading" class="loading">Loading...</div>
    <div v-if="error" class="error">{{ error }}</div>

    <div v-if="!isLoading && !error" class="course-list">
      <div v-for="course in courses" :key="course.id" class="course-card">
        <router-link :to="{ name: 'course', params: { courseId: course.id } }" class="course-link">
          <div class="course-image">
            <img :src="course.image || 'default-image-url.jpg'" alt="Course Image" />
          </div>
          <div class="course-details">
            <h3 class="course-title">{{ course.title }}</h3>
            <p class="course-description">{{ course.description }}</p>
            <p class="course-price">{{ course.price ? `${course.price} MAD` : 'Free' }}</p>
            <p class="course-duration">{{ formatDuration(course.duration) }}</p>
            <button class="view-button">View Details</button>
          </div>
        </router-link>
      </div>
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
      formatDuration(durationInMinutes) {
      const hours = Math.floor(durationInMinutes / 60);
      const minutes = durationInMinutes % 60;
      return `${hours}h ${minutes}m`;
    },
    ...mapActions('courses', ['fetchCourses']),  // Mapping the `fetchCourses` action from Vuex
  },
};
</script>

<style scoped>
.error {
  color: red;
}

.loading {
  font-size: 1.5rem;
  color: #999;
}

.course-list {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: 20px;
  padding: 20px;
}

.course-card {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.course-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.course-link {
  display: block;
  text-decoration: none;
  color: inherit;
}

.course-image img {
  width: 100%;
  height: 200px;
  object-fit: cover;
}

.course-details {
  padding: 20px;
}

.course-title {
  font-size: 1.2rem;
  font-weight: bold;
  color: #333;
  margin-bottom: 10px;
}

.course-description {
  font-size: 1rem;
  color: #555;
  margin-bottom: 15px;
}

.course-price {
  font-size: 1rem;
  font-weight: bold;
  color: #1E90FF;
  margin-bottom: 10px;
}

.course-duration {
  font-size: 0.9rem;
  color: #777;
  margin-bottom: 15px;
}

.view-button {
  background-color: #1E90FF;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.view-button:hover {
  background-color: #187bcd;
}
</style>
