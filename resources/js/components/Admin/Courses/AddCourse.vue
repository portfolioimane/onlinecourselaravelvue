<template>
  <div class="add-course">
    <h1>Add New Course</h1>
    <form @submit.prevent="submitForm" class="course-form">
      <div class="form-group">
        <label for="title">Course Title:</label>
        <input
          type="text"
          id="title"
          v-model="newCourse.title"
          required
          placeholder="e.g., Introduction to Laravel"
        />
      </div>

      <div class="form-group">
        <label for="slug">Course Slug:</label>
        <input
          type="text"
          id="slug"
          v-model="newCourse.slug"
          required
          placeholder="e.g., introduction-to-laravel"
        />
      </div>

      <div class="form-group">
        <label for="description">Description:</label>
        <textarea
          id="description"
          v-model="newCourse.description"
          required
          placeholder="Describe the course"
        ></textarea>
      </div>

      <div class="form-group">
        <label for="price">Price (in MAD):</label>
        <input
          type="number"
          id="price"
          v-model="newCourse.price"
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
          v-model="newCourse.duration"
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
      <div v-if="newCourse.image" class="image-preview">
        <img :src="imagePreview" alt="Course Image Preview" />
      </div>

      <div class="button-group">
        <button type="submit" class="btn primary">Add Course</button>
        <router-link to="/admin/courses" class="btn secondary">Cancel</router-link>
      </div>
    </form>
  </div>
</template>


<script>
export default {
  data() {
    return {
      newCourse: {
        title: '',
        description: '',
        price: '',
        image: null,
        slug: '',  // Add slug property
        duration: '', // Add duration property for the course duration
      },
      imageError: false, // Flag to track image validation error
      imagePreview: '', // URL for image preview
    };
  },
  computed: {
    // Automatically generate the slug from the title (if title is not empty)
    generatedSlug() {
      return this.newCourse.title
        .toLowerCase()
        .replace(/[^a-z0-9 -]/g, '') // Remove non-alphanumeric characters
        .replace(/\s+/g, '-') // Replace spaces with hyphens
        .substring(0, 100); // Limit to 100 characters
    },
  },
  watch: {
    'newCourse.title': function() {
      this.newCourse.slug = this.generatedSlug; // Update slug when title changes
    },
  },
  methods: {
    handleImageUpload(event) {
      const file = event.target.files[0];  // Handling image upload
      if (file) {
        // Check if file size exceeds 2MB (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
          this.imageError = true; // Set error flag to true
          this.newCourse.image = null; // Reset the image if it exceeds the size limit
          this.imagePreview = ''; // Reset preview if the image is invalid
        } else {
          this.imageError = false; // No error if file is valid
          this.newCourse.image = file; // Set the image if it's valid

          // Create a preview URL for the image
          const reader = new FileReader();
          reader.onload = (e) => {
            this.imagePreview = e.target.result; // Set the preview URL
          };
          reader.readAsDataURL(file); // Read the file as a data URL
        }
      }
    },
    async submitForm() {
      if (this.imageError) {
        // Prevent form submission if there's an image error
        alert("Please upload a valid image.");
        return;
      }

      console.log("Form submission started");
      const formData = new FormData();
      Object.entries(this.newCourse).forEach(([key, value]) => {
        console.log(`Appending ${key}: ${value}`);  // Debugging the form data
        formData.append(key, value);
      });

      try {
        console.log("Dispatching addCourse action with formData:", formData);
        await this.$store.dispatch('backendCourses/addCourse', formData);  // Dispatching action to store
        console.log("Course added successfully");
        this.$router.push('/admin/courses');  // Redirect to course list
      } catch (error) {
        console.error("Error adding course:", error);  // Handling error
      }
    },
  },
};
</script>




<style scoped>
.image-preview {
  margin-top: 1rem;
  text-align: left;
}

.image-preview img {
  width: 100px !important;
  height: 100px !important;
  border-radius: 8px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.add-course {
  margin: 1.5rem auto;
  max-width: 600px;
  color: #333;
}

.add-course h1 {
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

@media (max-height: 768px) {
  .add-course {
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
