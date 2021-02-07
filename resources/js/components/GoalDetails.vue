<template>
<div class="container">

    <input
        class="form-control form-control-lg mt-2 w-100"
        @focusout="editGoal"
        v-model="goal.name"
    >

    <div class="ml-3">
        <div class="row mt-2">
            <div class="col-2">
                <label class="col-2">Description: </label>
            </div>
            <div class="col">
                <textarea
                    id="description"
                    class="form-control"
                    rows="5"
                    style="resize: none;"
                    @focusout="editGoal"
                    placeholder="-----"
                    v-model="goal.description">
                </textarea>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-2">
                <label class="col-2">Complete By: </label>
            </div>
            <div class="col">
                <datepicker
                    name="due_date"
                    id="modify_due_date"
                    placeholder="Due Date"
                    @closed="editGoal"
                    @cleared="editGoal"
                    v-model="goal.due_date"
                    :format="date_format"
                    :clear-button="clear_button">
                </datepicker>
            </div>
        </div>

        <div class="row mt-2">
            <div class="col-2">
                <label class="col-2">Focus: </label>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <label for="year">Year</label>
                        <v-select :options="['2020','2021']"></v-select>
                    </div>
                    <div class="col">
                        <label for="quarter">Quarter</label>
                        <v-select :options="['Q1','Q2','Q3','Q4']"></v-select>
                    </div>
                    <div class="col">
                        <label for="month">Month</label>
                        <v-select :options="['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC']"></v-select>
                    </div>
                </div>
            </div>
        </div>

        <div class="blockquote-footer">
            <p>Last Update: {{ _formatDateTime(goal.updated_at) }}</p>
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

import Datepicker from 'vuejs-datepicker';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

export default {
    name: 'GoalDetails',
    components: {
        Datepicker,
        vSelect,
    },
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
            },
            index: null,
            original_name: null,
            original_description: null,
            original_due_date: null,
            date_format: 'MM/dd/yyyy',
            clear_button: true,
            errors: [],
        }
    },
    created() {
        this.$root.$on('loadDetails', this.getDetails);
        this.$root.$on('goalDeleted', this.goalDeleted);
    },
    methods: {

        getDetails: function (data)
        {
            this._clearErrors();
            this.index = data.index
            axios.get('/goal/'+data.id)
            .then(response => {
                this.goal = response.data.goal;
                this.goal.due_date = response.data.goal.due_date === null ? null : moment(response.data.goal.due_date).format('MM/DD/YYYY');
                this.original_name = this.goal.name;
                this.original_description = this.goal.description;
                this.original_due_date = this.goal.due_date;
            });
        },

        editGoal: function()
        {
            if (this.original_name !== this.goal.name || this.original_description !== this.goal.description || this.original_due_date !== moment(this.goal.due_date).format('MM/DD/YYYY')) {
                axios.patch('/goal/' + this.goal.id, {
                    name: this.goal.name,
                    description: this.goal.description,
                    due_date: this.goal.due_date === null || this.goal.due_date ===  '' ? null : moment(this.goal.due_date).format("YYYY-MM-DD")
                })
                .then(response => {
                    // update last updated text
                    this.goal.updated_at = response.data.updated_at;

                    this._clearErrors();

                    // now update the value in the sibling component if it was name or due date not description
                    this.$root.$emit('updateRow', {
                        new_name: this.goal.name,
                        new_due_date: this.goal.due_date,
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

        goalDeleted: function() {
            this._reset();
        },

        _reset() {
            this.goal.name = ''
            this.goal.description = ''
            this.goal.due_date = ''
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
