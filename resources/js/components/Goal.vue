Goal.vue:
<template>
    <div>

        <div class="ml-0 mt-2 mr-0 mb-2">
            <button @click="_initAddGoal()" class="btn btn-success " style="padding:5px">
                Add New Goal
            </button>
        </div>

        <table class="table table-responsive-lg table-sm table-hover" v-if="goals.length > 0">
            <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">Due Date</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(goal, index) in goals" @click="seeDetails(index)">
                <td>{{ goal.name }}</td>
                <td>{{ _formatDateTime(goal.due_date, 'MM/DD/YYYY') }}</td>
                <td class="text-center">
                    <i class="fa fa-trash fa-1 cursor-pointer" aria-hidden="true" @click.stop="deleteGoal(index)"></i>
                </td>
            </tr>
            </tbody>
        </table>

        <!-- add modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="add_goal_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header d-inline">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add New Goal</h4>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul class="m-0">
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" name="name" id="name" placeholder="Goal Name" class="form-control"
                                   v-model="new_goal.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control"
                                      placeholder="Goal Description" v-model="new_goal.description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date:</label>
                            <datepicker name="due_date" id="due_date" placeholder="Due Date" v-model="new_goal.due_date"
                                        :format="date_format" :clear-button="clear_button"></datepicker>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createGoal" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- /add modal -->

    </div>
</template>

<script>

    import Datepicker from 'vuejs-datepicker'

    export default {
        name: 'Goal',
        components: {
            Datepicker
        },
        data () {
            return {
                goals: [],
                new_goal: {
                    name: '',
                    description: '',
                    due_date: '',
                },
                errors: [],
                date_format: 'MM/dd/yyyy',
                clear_button: true,
                active: null,
            }
        },

        created () {
            this.$root.$on('updateRow', this.updateRow);
        },

        mounted () {
            this.readGoals()
        },

        methods: {

            readGoals () {
                axios.get('/goal')
                    .then(response => {
                        this.goals = response.data.goals
                    })
            },

            createGoal () {
                axios.post('/goal', {
                    name: this.goal.name,
                    description: this.goal.description,
                    due_date: this.goal.due_date === null || this.goal.due_date === '' ? null : moment(this.goal.due_date).format('YYYY-MM-DD')
                })
                    .then(response => {
                        this._reset()
                        this.goals.push(response.data.goals)
                        $('#add_goal_model').modal('hide')
                    })
                    .catch(error => {
                        this.errors = []
                        if (error.response.data.errors && error.response.data.errors.name) {
                            this.errors.push(error.response.data.errors.name[0])
                        }
                        if (error.response.data.errors && error.response.data.errors.description) {
                            this.errors.push(error.response.data.errors.description[0])
                        }
                    })
            },

            deleteGoal (index) {
                let conf = confirm('Do you really want to delete this goal?')
                if (conf === true) {
                    axios.delete('/goal/' + this.goals[index].id)
                        .then(response => {
                            this.goals.splice(index, 1)
                            this.nextOrPrevGoal(index);
                        })
                        .catch(error => {
                        })
                }
            },

            nextOrPrevGoal: function(index) {
                if (this.active !== null) {
                    // the one that was deleted was not the last one but the last one was selected
                    if(index !== this.active && this.active === this.goals.length) {
                        this.active = this.goals.length - 1;
                    }
                    // the one that was deleted was the one selected but it was not the last one
                    if (index === this.active && this.active !== this.goals.length) {
                        this.$root.$emit('goalDeleted')
                        this.seeDetails(index);
                    }
                    // the one that was deleted was the one that was selected and it was the last one
                    if(index === this.active && this.active === this.goals.length) {
                        this.$root.$emit('goalDeleted')
                        this.seeDetails(index - 1);
                    }
                }
            },

            seeDetails (index) {
                this.active = index;
                this.$root.$emit('loadDetails', {
                    id: this.goals[index].id,
                    index: index,
                });
            },

            updateRow: function(data) {
                this.goals[data.index].name = data.new_name;
                this.goals[data.index].due_date = data.new_due_date;
            },

            _initAddGoal () {
                this.errors = []
                $('#add_goal_model').modal('show')
            },

            _reset () {
                this.goal.name = ''
                this.goal.description = ''
                this.goal.due_date = ''
            },

            _formatDateTime (date, format = 'MM/DD/YYYY hh:mm A') {
                return date === null ? '' : moment(date).format(format)
            },

        }
    }

</script>
