<template>
    <div>
        <p>Title: {{ mission.name }}</p>
        <p>Description: {{ mission.description }}</p>
        <p>Due: {{ _formatDateTime(mission.due_date, 'MM/DD/YYYY') }}</p>
        <p>Created: {{ _formatDateTime(mission.created_at) }}</p>
        <p>Updated: {{ _formatDateTime(mission.updated_at) }}</p>
    </div>
</template>

<script>

export default {
    name: 'MissionDetails',
    data(){
        return {
            mission: {
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
            axios.get('/mission/'+id)
            .then(response => {
                this.mission = response.data.mission;
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
