import './bootstrap';
import '@mdi/font/css/materialdesignicons.min.css';
//cSpell:ignore materialdesignicons, Alpine, alpinejs, rspedidos, movil
import * as bootstrap from 'bootstrap';
import '../sass/app.scss';
import Alpine from 'alpinejs';
import { createApp } from 'vue';
window.Alpine = Alpine;
Alpine.start();

const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
const popoverList = [...popoverTriggerList].map((popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl));

//Componentes VueJs 3
//import example from "./components/ExampleComponent.vue";
import notify from './components/notify.vue';
import clientes from './components/clientes.vue';
import precios from './components/precios.vue';
import timer from './components/timer.vue';
import comandas_movil from './components/comandas_movil.vue';
import modal_cambiar_bodega from './components/modal_cambiar_bodega.vue';
import progreso from './components/progreso.vue';
import btnTerminado from './components/btn-terminado.vue';
import card from './components/card.vue';
import evento from './components/comanda-evento.vue';
import rspedidos from './components/rspedidos.vue';
import turnos from './components/turnos.vue';
import cautela from './components/nivelCautela.vue';
import status_control from './components/statusControl.vue';
import select_control from './components/selectControl.vue';
import search_control from './components/selectSearchControl.vue';
import identificaciones from './components/identificaciones.vue';

import confirmacion_estado from './components/confirmacionEstado.vue';

window.component = {
  precios: precios,
  notify: notify,
  clientes: clientes,
  timer: timer,
  progreso: progreso,
  btnTerminado: btnTerminado,
  card: card,
  rspedidos: rspedidos,
  comandas_movil: comandas_movil,
  modal_cambiar_bodega: modal_cambiar_bodega,
  turnos: turnos,
  evento: evento,
  cautela: cautela,
  status_control: status_control,
  select_control: select_control,
  search_control: search_control,
  identificaciones: identificaciones,
  confirmacion: confirmacion_estado,
};
window.appVue = createApp;
//appVue, inicialización de app en vue
