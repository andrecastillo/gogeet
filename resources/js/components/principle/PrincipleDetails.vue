<template>
    <p>{{ principle.id }}</p>
</template>

<script>

  export default {
    name: 'PrincipleDetails',
    data(){
        return {
            principle: {
                id: 0,
                name: '',
                description: '',
                created_at: '',
                updated_at: ''
            }
        }
    },
    created() {
        this.$root.$on('loadDetails', this.details);
    },
    methods: {

      details: function (id)
      {
        console.log('h');
        this.principle.id = id;
      }

    }
  }

</script>

<style scoped>

</style>
