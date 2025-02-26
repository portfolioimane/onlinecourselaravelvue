<template>
  <div class="home-page">
    <!-- Home Page Header Section -->
    <HomePageHeader />

    <!-- Services Section -->
    <ServicesSection />


  </div>
</template>

<script>
// Import the components
import HomePageHeader from './HomePageHeader.vue';
import ServicesSection from './ServicesSection.vue';  // Import the new ServicesSection component

export default {
  name: 'Home',
  components: {
    HomePageHeader,
    ServicesSection, // Register the ServicesSection component

  },
};
</script>

<style scoped>
.home-page {
  display: flex;
  flex-direction: column;
  padding: 0;
}
</style>
