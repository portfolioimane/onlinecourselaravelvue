<template>
  <div class="dashboard">
    <h1>Admin Dashboard</h1>

    <!-- Dashboard Stats Section -->
    <div class="stats">
      <div class="stat-card">
        <h3>Total Products</h3>
      </div>
      <div class="stat-card">
        <h3>Total Orders</h3>
      </div>
      <div class="stat-card">
        <h3>Total Categories</h3>
      </div>
    </div>

    <!-- Recent Orders Section -->
    <div class="recent-orders">
      <h2>Recent Orders</h2>
      <table>
        <thead>
          <tr>
            <th>Customer Name</th>
            <th>Date</th>
            <th>Total Amount</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  name: "Dashboard",
  computed: {





  },
  methods: {
    async fetchDashboardData() {
      try {
        // Fetch products, orders, and categories data
      } catch (error) {
        console.error("Error fetching dashboard data:", error);
      }
    },
    formatDate(date) {
      return new Date(date).toLocaleDateString("en-US"); // Format date
    },
  },
  mounted() {
    this.fetchDashboardData(); // Fetch data when the component is mounted
  },
};
</script>

<style scoped>
.dashboard {
  padding: 20px;
}

h3{
  color:#ffffff !important;
}

.stats {
  display: flex;
  gap: 20px;
  margin-bottom: 20px;
}

.stat-card {
  flex: 1;
  background-color: var(--primary-color);
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  border: 1px solid #ddd;
  transition: transform 0.3s ease, background-color 0.3s ease;
}

.stat-card:hover {
  background-color: var(--primary-color);
  transform: scale(1.05);
}

.stat-card h3 {
  margin: 0;
  color: var(--secondary-color);
}

.stat-card p {
  font-size: 24px;
  margin-top: 10px;
  font-weight: bold;
}

.recent-orders {
  margin-top: 20px;
}

.recent-orders table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 10px;
}

.recent-orders th,
.recent-orders td {
  padding: 12px;
  border: 1px solid #ddd;
  text-align: left;
}

.recent-orders th {
  background-color: #f4f4f4;
}
</style>
