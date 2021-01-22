<template>
    <div class="container">

        <h4 class="mt-2"> {{ mission.name }}</h4>

        <div class="row">
            <div class="col-2">Description:</div>
            <div class="col"><p class="">{{ mission.description || "-----" }}</p></div>
        </div>

        <div class="row">
            <div class="col-2">Due</div>
            <div class="col"><p>{{ _formatDateTime(mission.due_date, 'MM/DD/YYYY') || '-----' }}</p></div>
        </div>

        <div class="blockquote-footer">
            <p>Last Update: {{ _formatDateTime(mission.updated_at) }}</p>
        </div>

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
