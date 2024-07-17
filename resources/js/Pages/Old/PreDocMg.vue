<template>
     <div class="row">
        <div class="col-md-6">
            <DocCat @catid="get_dcid($event)" />
        </div>
        <div class="col-md-6">
            <Doc :catid="cateid" />
        </div>
     </div>

</template>

<script>
import DocCat from '../../Components/PreDoc/DocCat.vue';
import Doc from '../../Components/PreDoc/Docu.vue';

export default {
   
    name: 'DmsPreDocMg',

    data() {
        return {
            cateid:0
        };
    },

    mounted() {
        
    },
    components: {
        DocCat, Doc
    },

    methods: {
        get_dcid(id){
            this.cateid = id
                // console.log(id);
        }
    },
};
</script>

<style lang="scss" scoped>

</style>