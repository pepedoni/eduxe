import HomeComponent from './components/HomeComponent.vue';
import Empresa from './components/empresa/Empresa.vue';

const routes = [
  {
      name: 'home',
      path: '/',
      component: HomeComponent
  },
  {
      name: 'empresa',
      path: '/empresa',
      component: Empresa
  }
];

export default routes;