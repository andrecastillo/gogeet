<template>
<div>

    <h3>{{ principle.name }}</h3>
    <p>Description: {{ principle.description }}</p>
    <p>Created: {{ _formatDateTime(principle.created_at) }}</p>
    <p>Updated: {{ _formatDateTime(principle.updated_at) }}</p>

</div>
</template>

<script>

export default {
    name: 'PrincipleDetails',
    data(){
        return {
            principle: {
                created_at: '',
                deleted_at: '',
                description: '',
                id: 0,
                name: '',
                updated_at: '',
                user_id: '',
            }
        }
    },
    created() {
        this.$root.$on('loadDetails', this.getDetails);
    },
    methods: {

        getDetails: function (id)
        {
            axios.get('/principle/'+id)
            .then(response => {
                this.principle = response.data.principle;
            });
        },

        _formatDateTime(date, format = 'MM/DD/YYYY hh:mm A') {
            return date === null ? '' : moment(date).format(format);
        },

    }
}

</script>

<style scoped>

</style>
