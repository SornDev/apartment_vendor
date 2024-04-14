<template>
    <div>
        <h1>Report Page</h1>
        <hr>
       Token: {{ store.get_token }}
    </div>
</template>

<script>
import { useStore } from '../Store/auth'
export default {
    name: 'Minipos14Report',
    setup(){
        const store = useStore();
        return { store }
    },
    data() {
        return {
            
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