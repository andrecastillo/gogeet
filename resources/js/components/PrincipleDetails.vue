<template>
<div class="container">

    <h4 class="mt-2">{{ principle.name }}</h4>

    <div class="row">
        <div class="col-2">Description: </div>
        <div class="col">{{ principle.description || "-----" }}</div>
    </div>

    <div class="blockquote-footer">
        <p>Last Update: {{ _formatDateTime(principle.updated_at) }}</p>
    </div>

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
