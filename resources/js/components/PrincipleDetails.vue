<template>
<div class="container">

    <input class="form-control form-control-lg mt-2 w-100" @focusin="getOriginalName" @focusout="editPrinciple" v-model="principle.name">

    <div class="ml-3">
        <div class="row">
            <label class="col-2">Description: </label>
            <textarea class="form-control" @focusin="getOriginalDescription" @focusout="editPrinciple" placeholder="-----" v-model="principle.description"></textarea>
        </div>

        <div class="blockquote-footer">
            <p>Last Update: {{ _formatDateTime(principle.updated_at) }}</p>
        </div>
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
            original_description: '',
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

        getOriginalName: function ()
        {
            this.original_name = this.principle.name;
        },

        getOriginalDescription: function()
        {
            this.original_description = this.principle.description;
        },

        editPrinciple: function()
        {
            axios.patch('/principle/' + this.principle.id, {
                name: this.principle.name,
                description: this.principle.description,
            })
            .then(response => {
                // update last updated text
                this.principle.updated_at = response.data.updated_at;

                // now update the value in the sibling component if it was name not description
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
        },

        _formatDateTime(date, format = 'MM/DD/YYYY hh:mm A') {
            return date === null ? '' : moment(date).format(format);
        },

    }
}

</script>

<style scoped>

</style>
