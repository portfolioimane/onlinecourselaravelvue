<template>
  <div class="edit-course">
    <h1>Edit Course</h1>
    <form @submit.prevent="submitForm" class="course-form">
      <div class="form-group">
        <label for="title">Course Title:</label>
        <input
          type="text"
          id="title"
          v-model="course.title"
          required
          placeholder="e.g., Introduction to Laravel"
        />
      </div>

      <div class="form-group">
        <label for="slug">Course Slug:</label>
        <input
          type="text"
          id="slug"
          v-model="course.slug"
          required
          placeholder="e.g., introduction-to-laravel"
        />
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea
          id="description"
          v-model="course.description"
          required
          placeholder="Describe the course"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="price">Price (in MAD):</label>
        <input
          type="number"
          id="price"
          v-model="course.price"
          required
          placeholder="e.g., 1500"
          min="0"
        />
      </div>

      <!-- New Duration Field -->
      <div class="form-group">
        <label for="duration">Duration (in minutes):</label>
        <input
          type="number"
          id="duration"
          v-model="course.duration"
          required
          placeholder="e.g., 20"
          min="1"
        />
      </div>

      <div class="form-group">
        <label for="image">Upload Image (Max 2MB):</label>
        <input
          type="file"
          id="image"
          @change="handleImageUpload"
          accept="image/*"
        />
        <small class="help-text">
          <span>For optimal performance, please upload compressed images with a maximum size of 2MB. Consider using a tool to reduce the file size before uploading.</span>
        </small>
        <!-- Display error message if the image is too large -->
        <p v-if="imageError" class="error-message">The image file size exceeds the 2MB limit. Please upload a smaller image.</p>
      </div>

      <!-- Preview Section -->
      <div v-if="imagePreview" class="image-preview">
        <img :src="imagePreview" alt="Course Image Preview" class="img-fluid img-preview" />
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary">Save Changes</button>
        <router-link to="/admin/courses" class="btn secondary">Cancel</router-link>
      </div>
    </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';

export default {
  data() {
    return {
      course: {
        id: null,
        title: '',
        slug: '',
        description: '',
        price: '',
        duration: '',
        image: null,
      },
      imageError: false,  // Flag to track image validation error
      imagePreview: null, // URL for image preview
    };
  },
  computed: {
    ...mapGetters('backendCourses', ['currentCourse']),
  },
  methods: {
    ...mapActions('backendCourses', ['fetchCourseById', 'updateCourse']),

    async fetchCourse() {
      const courseId = this.$route.params.id;
      await this.fetchCourseById(courseId);
      this.course = { ...this.currentCourse };
      this.imagePreview = this.course.image ? `${this.course.image}` : null;
    },

    handleImageUpload(event) {
      const file = event.target.files[0];
      if (file) {
        if (file.size > 2 * 1024 * 1024) {
          this.imageError = true;
          this.course.image = null;
          this.imagePreview = null;
        } else {
          this.imageError = false;
          this.course.image = file;

          // Create a preview URL for the image
          this.imagePreview = URL.createObjectURL(file);
        }
      }
    },

    async submitForm() {
      if (this.imageError) {
        alert("Please upload a valid image.");
        return;
      }

      const formData = new FormData();
      formData.append('title', this.course.title);
      formData.append('slug', this.course.slug);
      formData.append('description', this.course.description);
      formData.append('price', this.course.price);
      formData.append('duration', this.course.duration);

      if (this.course.image) {
        formData.append('image', this.course.image);
      }

      await this.updateCourse({ id: this.$route.params.id, formData });
      this.$router.push('/admin/courses');
    },
  },
  mounted() {
    this.fetchCourse();
  },
};
</script>

<style scoped>
.edit-course {
  margin: 1.5rem auto;
  max-width: 600px;
  color: #333;
}

.edit-course h1 {
  color: #1E90FF;
  font-size: 1.6rem;
  font-weight: bold;
  margin-bottom: 1rem;
  text-align: center;
}

.course-form .form-group {
  margin-bottom: 0.5rem;
}

.course-form label {
  display: block;
  font-weight: bold;
  margin-bottom: 0.25rem;
  color: #555;
}

.course-form input[type="text"],
.course-form input[type="number"],
.course-form input[type="file"],
.course-form select,
.course-form textarea {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  font-size: 0.9rem;
}

.course-form input:focus,
.course-form select:focus,
.course-form textarea:focus {
  border-color: #d4af37;
  outline: none;
}

.course-form textarea {
  resize: vertical;
  min-height: 80px;
}

.button-group {
  display: flex;
  justify-content: space-between;
  margin-top: 1rem;
}

.btn {
  padding: 0.5rem 1rem;
  font-size: 0.9rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-weight: bold;
  text-transform: uppercase;
}

.btn.primary {
  background-color: #1E90FF;
  color: #fff;
}

.btn.secondary {
  background-color: #555;
  color: #fff;
}

.btn:hover {
  opacity: 0.9;
}

.help-text {
  font-size: 0.85rem;
  color: #777;
  margin-top: 0.5rem;
}

.error-message {
  color: red;
  font-size: 0.85rem;
  margin-top: 0.5rem;
}

.img-preview {
  width: 100px !important;
  height: 100px !important;
}

@media (max-height: 768px) {
  .edit-course {
    margin: 1rem auto;
  }

  .course-form input,
  .course-form textarea {
    padding: 0.4rem;
    font-size: 0.85rem;
  }

  .btn {
    padding: 0.4rem 0.8rem;
    font-size: 0.85rem;
  }
}
</style>
