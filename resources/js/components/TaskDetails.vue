<template>
<div class="container">

    <h4 class="mt-2"> {{ task.name }}</h4>

    <div class="row">
        <div class="col-2">Description:</div>
        <div class="col"><p class="">{{ task.description || "-----" }}</p></div>
    </div>

    <div class="row">
        <div class="col-2">Due</div>
        <div class="col"><p>{{ _formatDateTime(task.due_date, 'MM/DD/YYYY') || '-----' }}</p></div>
    </div>

    <div class="blockquote-footer">
        <p>Last Update: {{ _formatDateTime(task.updated_at) }}</p>
    </div>

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
