<template>
  <div class="courses">
    <h1>Manage Courses</h1>
    
    <table class="courses-table">
      <thead>
        <tr>
          <th>Course ID</th>
          <th>Title</th>
          <th>Description</th>
          <th>Price (MAD)</th>
          <th>Duration </th> <!-- New column for Duration -->
          <th>Slug</th>
          <th>Image</th>
          <th>Featured</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="course in courses" :key="course.id">
          <td>{{ course.id }}</td>
          <td>{{ course.title }}</td>
          <td>{{ course.description }}</td>
          <td>{{ parseFloat(course.price).toFixed(2) }}</td>
<td>{{ formatDuration(course.duration) }}</td> 
          <td>{{ course.slug }}</td>
          <td>
            <img 
              :src="course.image ? `${course.image}` : '/images/courses/default-course.png'" 
              alt="Course Image" 
              class="course-image"
            />
          </td>
          <td>
            <input 
              type="checkbox" 
              v-model="course.featured" 
              @change="toggleFeatured(course)" 
            />
          </td>
          <td>
            <button class="btn secondary" @click="editCourse(course)">Edit</button>
            <button class="btn danger" @click="openDeleteModal(course.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="modal-overlay">
      <div class="modal-content">
        <h3>Are you sure you want to delete this course?</h3>
        <div class="modal-actions">
          <button class="btn danger" @click="deleteCourse">Yes, Delete</button>
          <button class="btn primary" @click="closeDeleteModal">Cancel</button>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { mapGetters } from 'vuex';

export default {
  name: 'Courses',
  data() {
    return {
      showDeleteModal: false,
      courseToDelete: null,
      message: null,
    };
  },
  computed: {
    ...mapGetters('backendCourses', ['allCourses']),
    courses() {
      return this.allCourses.map(course => ({
        ...course,
        featured: Boolean(course.featured), // Ensure featured is boolean
      }));
    },
  },
  methods: {
    formatDuration(durationInMinutes) {
      const hours = Math.floor(durationInMinutes / 60);
      const minutes = durationInMinutes % 60;
      return `${hours}h ${minutes}m`;
    },
    editCourse(course) {
      this.$router.push({ name: 'EditCourse', params: { id: course.id } });
    },
    openDeleteModal(courseId) {
      this.courseToDelete = courseId;
      this.showDeleteModal = true;
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
      this.courseToDelete = null;
    },
    async deleteCourse() {
      try {
        await this.$store.dispatch('backendCourses/deleteCourse', this.courseToDelete);
        this.showDeleteModal = false;
        this.courseToDelete = null;
        this.showMessage('Course deleted successfully', 'success');
      } catch (error) {
        this.showMessage('Error deleting course', 'error');
        console.error('Error deleting course:', error);
      }
    },
    async toggleFeatured(course) {
      try {
        await this.$store.dispatch('backendCourses/toggleFeatured', course.id);
        this.$store.dispatch('backendCourses/fetchCourses');
        this.showMessage('Course featured status updated', 'success');
      } catch (error) {
        this.showMessage('Error updating featured status', 'error');
        console.error('Error updating featured status:', error);
      }
    },
    showMessage(text, type) {
      this.message = { text, type };
      setTimeout(() => {
        this.message = null;
      }, 5000);
    },
  },
  mounted() {
    this.$store.dispatch('backendCourses/fetchCourses');
  },
};
</script>

<style scoped>
.courses {
  padding: 30px;
  background-color: #f9f9f9;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

h1 {
  color: #1E90FF;
  margin-bottom: 20px;
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
}

.courses-table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

.courses-table th,
.courses-table td {
  border: 1px solid #ddd;
  padding: 15px;
  text-align: left;
}

.courses-table th {
  background-color: #1E90FF;
  color: white;
  font-weight: bold;
}

.courses-table tbody tr:nth-child(even) {
  background-color: #f2f2f2;
}

.courses-table tbody tr:hover {
  background-color: #f9f9f9;
}

.course-image {
  max-width: 100px;
  max-height: 100px;
  object-fit: cover;
}

.btn {
  padding: 12px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
  font-weight: bold;
}

.btn.secondary {
  background-color: #1E90FF;
  color: white;
}

.btn.danger {
  background-color: #e74c3c;
  color: white;
}

.btn.primary {
  background-color: #1E90FF;
  color: white;
}

.btn:hover {
  opacity: 0.9;
}

/* Modal Styles */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}

.modal-content {
  background-color: white;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
  max-width: 400px;
  width: 100%;
  text-align: center;
}

.modal-actions {
  margin-top: 20px;
}

.modal-actions button {
  margin: 0 10px;
}

.course-image {
  width: 100px !important;
  height: 100px !important;
}
</style>
