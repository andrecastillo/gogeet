<template>
<div class="container">

    <h4 class="mt-2" contenteditable="true" @focusout="editName" id="name">{{ principle.name }}</h4>

    <div class="row">
        <div class="col-2">Description: </div>
        <div class="col" contenteditable="true">{{ principle.description || "-----" }}</div>
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
            },
            original_name: '',
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

        editName: function ()
        {
            this.original_name = this.principle.name;
            this.new_name = 'tst';
            console.log(this.$refs.name);
            /*
            axios.patch('/principle/' + this.update_principle.id, {
                name: this.update_principle.name,
            })
            .then(response => {
                $("#update_principle_model").modal("hide");
            })
            .catch(error => {
                this.errors = [];
                if (error.response.data.errors.name) {
                    this.errors.push(error.response.data.errors.name[0]);
                }
                if (error.response.data.errors.description) {
                    this.errors.push(error.response.data.errors.description[0]);
                }
            });
            */
        },

        _formatDateTime(date, format = 'MM/DD/YYYY hh:mm A') {
            return date === null ? '' : moment(date).format(format);
        },

    }
}

</script>

<style scoped>

</style>
