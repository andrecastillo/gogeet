<template>
<div class="container">

    <input
        class="form-control form-control-lg mt-2 w-100"
        @focusout="editPrinciple"
        v-model="principle.name"
    >

    <div class="ml-3">
        <div class="row mt-2">
            <div class="col-2">
                <label for="description">Description: </label>
            </div>
            <div class="col">
                <textarea
                    id="description"
                    class="form-control"
                    rows="5"
                    style="resize: none;"
                    @focusout="editPrinciple"
                    placeholder="-----"
                    v-model="principle.description">
                </textarea>
            </div>
        </div>

        <div class="blockquote-footer">
            <p>Last Update: {{ _formatDateTime(principle.updated_at) }}</p>
        </div>
    </div>

    <!-- errors -->
    <div class="alert alert-danger" v-if="errors.length > 0">
        <ul class="m-0">
            <li v-for="error in errors">{{ error }}</li>
        </ul>
    </div>
    <!-- /errors-->

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
            errors: [],
        }
    },
    created() {
        this.$root.$on('loadDetails', this.getDetails);
        this.$root.$on('principleDeleted', this.principleDeleted);
    },
    methods: {

        getDetails: function (data)
        {
            this._clearErrors();
            this.index = data.index
            axios.get('/principle/'+data.id)
            .then(response => {
                this.principle = response.data.principle;
                this.original_name = this.principle.name;
                this.original_description = this.principle.description;
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

                    this._clearErrors();

                    // now update the value in the sibling component if it was name not description
                    this.$root.$emit('updateRow', {
                        new_name: this.principle.name,
                        index: this.index
                    });
                })
                .catch(error => {
                    this._clearErrors();
                    if(error.response) {
                        this.errors[0] = 'Response error. Please try again.';
                    } else if (error.request) {
                        this.errors[0] = 'Request error. Please try again.';
                    } else {
                        this.errors[0] = 'Unknown error. Please try again.';
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

        _clearErrors() {
            this.errors = [];
        },

    }
}

</script>
