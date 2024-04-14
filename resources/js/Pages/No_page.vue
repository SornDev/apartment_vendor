<template>
    <div class="misc-wrapper text-center">
    <h2 class="mb-2 mx-2">Page Not Found :(</h2>
    <p class="mb-4 mx-2">Oops! 😖 The requested URL was not found on this server.</p>
    <router-link to="/"  class="btn btn-primary">ກັບສູ່ໜ້າ ທຳອິດ</router-link>
    <div class="mt-3">
      <img :src="url + '/assets/img/illustrations/page-misc-error-light.png'" alt="page-misc-error-light" width="500" class="img-fluid">
    </div>
  </div>
</template>

<script>

export default {
    name: 'Minipos14NoPage',

    data() {
        return {
        url: window.location.origin
        };
    },

    mounted() {
        
    },

    methods: {
        
    },
};
</script>

<style lang="scss" scoped>

</style>