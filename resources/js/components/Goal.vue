Goal.vue:
<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading ml-0 mt-2 mr-0 mb-2">
                        <button @click="_initAddGoal()" class="btn btn-success " style="padding:5px">
                            Add New Goal
                        </button>
                    </div>

                    <div class="panel-body">
                        <table class="table table-responsive-lg table-sm table-hover" v-if="goals.length > 0">
                            <thead>
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Due Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(goal, index) in goals">
                                    <td>{{ goal.name }}</td>
                                    <td>{{ _formatDateTime(goal.due_date, 'MM/DD/YYYY') }}</td>
                                    <td><button @click="_initUpdateGoal(index)" class="btn btn-success btn-xs" style="padding:8px"><span class="glyphicon glyphicon-edit"></span></button>
                                        <button @click="deleteGoal(index)" class="btn btn-danger btn-xs" style="padding:8px"><span class="glyphicon glyphicon-trash"></span></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

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
                            <input type="text" name="name" id="name" placeholder="Goal Name" class="form-control" v-model="goal.name">
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea name="description" id="description" cols="30" rows="5" class="form-control" placeholder="Goal Description" v-model="goal.description"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date:</label>
                            <datepicker name="due_date" id="due_date" placeholder="Due Date" v-model="goal.due_date" :format="date_format" :clear-button="clear_button"></datepicker>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createGoal" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <!-- update goal modal -->
        <div class="modal fade" tabindex="-1" role="dialog" id="update_goal_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header d-inline">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Update Goal</h4>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul class="m-0">
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </div>

                        <div class="form-group">
                            <label>Name:</label>
                            <input type="text" placeholder="Goal Name" class="form-control"
                                   v-model="update_goal.name">
                        </div>

                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea cols="30" rows="5" class="form-control"
                                      placeholder="Goal Description" v-model="update_goal.description"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="update_due_date">Due Date:</label>
                            <datepicker name="update_due_date" id="update_due_date" placeholder="Due Date" v-model="update_goal.due_date" :format="date_format" :clear-button="clear_button"></datepicker>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="updateGoal" class="btn btn-primary">Submit</button>
                    </div>

                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->

        </div><!-- /.modal - end update goal -->

    </div>
</template>

<script>

import Datepicker from 'vuejs-datepicker';

export default {
    name: 'goal',
    components: {
        Datepicker
    },
    data(){
        return {
            date_format: "MM/dd/yyyy",
            clear_button: true,
            goal: {
                name: '',
                description: '',
                due_date: ''
            },
            errors: [],
            goals: [],
            update_goal: {}
        }
    },

    mounted()
    {
        this.readGoals();
    },

    methods: {

        readGoals()
        {
            axios.get('/goal')
                .then(response => {
                    this.goals = response.data.goals;
                });
        },

        createGoal()
        {
            axios.post('/goal', {
                name: this.goal.name,
                description: this.goal.description,
                due_date: this.goal.due_date === null || this.goal.due_date === '' ? null : moment(this.goal.due_date).format('YYYY-MM-DD')
            })
            .then(response => {
                this._reset();
                this.goals.push(response.data.goals);
                $("#add_goal_model").modal("hide");
            })
            .catch(error => {
                this.errors = [];
                if (error.response.data.errors && error.response.data.errors.name) {
                    this.errors.push(error.response.data.errors.name[0]);
                }
                if (error.response.data.errors && error.response.data.errors.description)
                {
                    this.errors.push(error.response.data.errors.description[0]);
                }
            });
        },

        updateGoal()
        {
            axios.patch('/goal/' + this.update_goal.id, {
                name: this.update_goal.name,
                description: this.update_goal.description,
                due_date: this.update_goal.due_date === null || this.update_goal.due_date === '' ? null : moment(this.update_goal.due_date).format("YYYY-MM-DD")
            })
            .then(response => {
                $("#update_goal_model").modal("hide");
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

        deleteGoal (index) {
          let conf = confirm("Do you really want to delete this goal?");
          if (conf === true) {
            axios.delete('/goal/' + this.goals[index].id)
              .then(response => {
                this.goals.splice(index, 1);
              })
              .catch(error => {
              });
          }
        },

        _initAddGoal()
        {
          this.errors = [];
          $("#add_goal_model").modal("show");
        },

        _initUpdateGoal(index)
        {
            this.errors = [];
            $("#update_goal_model").modal("show");
            this.update_goal = this.goals[index];
            this.update_goal.due_date = this.update_goal.due_date === null ? null : moment(this.update_goal.due_date).format('MM/DD/YYYY');
        },

        _reset()
        {
            this.goal.name = '';
            this.goal.description = '';
            this.goal.due_date = '';
        },

        _formatDateTime(date, format = 'MM/DD/YYYY hh:mm A') {
          return date === null ? '' : moment(date).format(format);
        },

    }
}

</script>
