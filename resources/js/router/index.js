import { createRouter, createWebHistory } from 'vue-router';
import Courses from '../components/Courses.vue';
import CoursePage from '../components/CoursePage.vue';
import LessonPage from '../components/LessonPage.vue';

const routes = [
  { path: '/', component: Courses },
  { path: '/course/:courseId', name: 'course', component: CoursePage },
  { path: '/course/:courseId/lesson/:lessonId', name: 'lesson', component: LessonPage },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
