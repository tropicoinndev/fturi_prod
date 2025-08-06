<template>
  <div>
    <h1>Agregar rol</h1>
    <agregar_Rol model="selectedRole" :role="selectedRole" />
  </div>
</template>

<script>
import agregar_Rol from '@/components/agregar_Rol.vue';

export default {
  components: {
    agregar_Rol,
  },
  data() {
    return {
      selectedRole: '',
    };
  },
};
</script>