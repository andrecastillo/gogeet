<template>
    <div>
        <p>Title: {{ goal.name }}</p>
        <p>Description: {{ goal.description }}</p>
        <p>Due: {{ _formatDateTime(goal.due_date, 'MM/DD/YYYY') }}</p>
        <p>Created: {{ _formatDateTime(goal.created_at) }}</p>
        <p>Updated: {{ _formatDateTime(goal.updated_at) }}</p>
    </div>
</template>

<script>

export default {
    name: 'GoalDetails',
    data(){
        return {
            goal: {
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
            axios.get('/goal/'+id)
            .then(response => {
                this.goal = response.data.goal;
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
