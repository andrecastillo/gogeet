<template>
<div class="container">

    <input class="form-control form-control-lg mt-2 w-100" @focusout="editPrinciple" v-model="principle.name">

    <div class="ml-3">
        <div class="row">
            <label class="col-2">Description: </label>
            <textarea class="form-control" @focusout="editPrinciple" placeholder="-----" v-model="principle.description"></textarea>
        </div>

        <div class="blockquote-footer">
            <p>Last Update: {{ _formatDateTime(principle.updated_at) }}</p>
        </div>
    </div>

    <!-- possibly where i put errors
    <div class="alert alert-danger" v-if="errors.length > 0">
        <ul class="m-0">
            <li v-for="error in errors">{{ error }}</li>
        </ul>
    </div>
    -->

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
            index: null,
            original_name: null,
            original_description: null,
        }
    },
    created() {
        this.$root.$on('loadDetails', this.getDetails);
        this.$root.$on('principleDeleted', this.principleDeleted);
    },
    methods: {

        getDetails: function (data)
        {
            this.index = data.index
            axios.get('/principle/'+data.id)
            .then(response => {
                this.principle = response.data.principle;
            });
        },

        editPrinciple: function()
        {
            if (this.original_name !== this.principle.name || this.original_description !== this.principle.description) {
                axios.patch('/principle/' + this.principle.id, {
                    name: this.principle.name,
                    description: this.principle.description,
                })
                .then(response => {
                    // update last updated text
                    this.principle.updated_at = response.data.updated_at;

                    // now update the value in the sibling component if it was name not description
                    this.$root.$emit('updateName', {
                        new_name: this.principle.name,
                        index: this.index
                    });
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
            }
        },

        principleDeleted: function() {
            this._reset();
        },

        _reset() {
            this.principle.name = ''
            this.principle.description = ''
        },

        _formatDateTime(date, format = 'MM/DD/YYYY hh:mm A') {
            return date === null ? '' : moment(date).format(format);
        },

    }
}

</script>
