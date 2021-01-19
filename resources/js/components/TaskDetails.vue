<template>
    <div>
        <p>Title: {{ task.name }}</p>
        <p>Description: {{ task.description }}</p>
        <p>Due: {{ _formatDateTime(task.due_date, 'MM/DD/YYYY') }}</p>
        <p>Created: {{ _formatDateTime(task.created_at) }}</p>
        <p>Updated: {{ _formatDateTime(task.updated_at) }}</p>
    </div>
</template>

<script>

export default {
    name: 'TaskDetails',
    data(){
        return {
            task: {
                created_at: '',
                deleted_at: '',
                description: '',
                id: 0,
                name: '',
                updated_at: '',
                user_id: '',
                due_date: '',
            }
        }
    },
    created() {
        this.$root.$on('loadDetails', this.getDetails);
    },
    methods: {

        getDetails: function (id)
        {
            axios.get('/task/'+id)
            .then(response => {
                this.task = response.data.task;
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
