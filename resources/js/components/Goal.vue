Goal.vue:
<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3><span class="glyphicon glyphicon-dashboard"></span> Goals </h3> <br>
                        <button @click="initAddGoal()" class="btn btn-success " style="padding:5px">
                            Add New Goal
                        </button>
                    </div>

                    <div class="panel-body">
                        <table class="table table-bordered table-striped table-responsive" v-if="goals.length > 0">
                            <tbody>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Due Date</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th>Action</th>
                                </tr>
                                <goal-row v-for="(goal, index) in goals" :key="index" :goal="goal" :index="index" @delete-goal="deleteGoal"></goal-row>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="add_goal_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Add New Goal</h4>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
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
                            <datepicker name="due_date" id="due_date" placeholder="Due Date" v-model="goal.due_date"></datepicker>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="createGoal" class="btn btn-primary">Submit</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->

        <div class="modal fade" tabindex="-1" role="dialog" id="update_goal_model">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title">Update Goal</h4>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger" v-if="errors.length > 0">
                            <ul>
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
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" @click="updateGoal" class="btn btn-primary">Submit</button>
                    </div>

                </div><!-- /.modal-content -->

            </div><!-- /.modal-dialog -->

        </div><!-- /.modal -->

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
                goal: {
                    name: '',
                    description: '',
                    created_at: '',
                    updated_at: '',
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
            initAddGoal()
            {
                $("#add_goal_model").modal("show");
            },
            createGoal()
            {
                axios.post('/goal', {
                    name: this.goal.name,
                    description: this.goal.description,
                    due_date: this.goal.due_date === '' ? null : moment(this.goal.due_date).format('YYYY-MM-DD')
                })
                .then(response => {
                    this.reset();
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
            deleteGoal(index) {
              console.log(index);
              this.goals.splice(index, 1);
            },
            reset()
            {
                this.goal.name = '';
                this.goal.description = '';
                this.goal.due_date = '';
            },
            readGoals()
            {
                axios.get('/goal')
                    .then(response => {
                        this.goals = response.data.goals;
                    });
            },
            initUpdate(index)
            {
                this.errors = [];
                $("#update_goal_model").modal("show");
                this.update_goal = this.goals[index];
            },
            updateGoal()
            {
                axios.patch('/goal/' + this.update_goal.id, {
                    name: this.update_goal.name,
                    description: this.update_goal.description,
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
            }
        }
    }

</script>
