<template>
    <p>{{principle.name}}</p>
</template>

<script>
  export default {
    name: 'PrincipleDetails',
    data(){
        return {
            principle: {
                name: '',
                description: '',
                created_at: '',
                updated_at: ''
            }
        }
    },
  }
</script>

<style scoped>

</style>
