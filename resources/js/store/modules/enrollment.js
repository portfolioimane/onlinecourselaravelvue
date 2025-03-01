import axios from "../../utils/axios.js";

const state = {
  enrollments: [],
  totalEnrollments: 0,
};

const mutations = {
  setEnrollments(state, { enrollments, total }) {
    state.enrollments = enrollments;
    state.totalEnrollments = total;
  },
  addEnrollment(state, enrollment) {
    state.enrollments.push(enrollment);
  },
};

const actions = {
  async fetchEnrollments({ commit }) {
    try {
      const response = await axios.get("/enrollments");
      commit("setEnrollments", response.data);
    } catch (error) {
      console.error("Error fetching enrollments:", error);
    }
  },

  async submitEnrollment({ commit }, enrollmentData) {
    try {
      const response = await axios.post("/enrollments/create", enrollmentData);
      commit("addEnrollment", response.data.enrollment);
      return response.data.enrollment;
    } catch (error) {
      console.error("Error submitting enrollment:", error);
      throw error;
    }
  },

  async fetchEnrollmentById(_, enrollmentId) {
    try {
      const response = await axios.get(`/enrollments/${enrollmentId}`);
      return response.data;
    } catch (error) {
      console.error("Error fetching enrollment by ID:", error);
      throw error;
    }
  },

  // Create Stripe Payment Intent
  async createStripePayment(_, totalAmount) {
    try {
      const response = await axios.post("/enrollments/create-stripe-payment", { total: totalAmount });
      return response.data;
    } catch (error) {
      console.error("Error creating Stripe payment:", error);
      throw error;
    }
  },

  // Confirm PayPal Payment
  async confirmPayPalPayment(_, paypalOrderId) {
    try {
      const response = await axios.post("/enrollments/confirm-paypal-payment", { paypalOrderId });
      return response.data;
    } catch (error) {
      console.error("Error confirming PayPal payment:", error);
      throw error;
    }
  },
};

const getters = {
  allEnrollments: (state) => state.enrollments,
  enrollmentCount: (state) => state.totalEnrollments,
};

export default {
  namespaced: true,
  state,
  mutations,
  actions,
  getters,
};
