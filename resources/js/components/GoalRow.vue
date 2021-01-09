GoalRow.vue:
<template>
    <tr>
        <td>{{ goal.name }}</td>
        <td>{{ goal.description }}</td>
        <td>{{ _formatDate(goal.due_date) }}</td>
        <td>{{ goal.created_at }}</td>
        <td>{{ goal.updated_at }}</td>
        <td><button @click="initUpdate(index)" class="btn btn-success btn-xs" style="padding:8px"><span class="glyphicon glyphicon-edit"></span></button>
            <button @click="deleteGoal(index)" class="btn btn-danger btn-xs" style="padding:8px"><span class="glyphicon glyphicon-trash"></span></button>
        </td>
    </tr>
</template>


<script>
export default {
    name: 'goal-row',
    props: {
        goal: {
            type: Object,
            required: true
        },
        index: {
            type: Number,
            required: true
        }
    },
    methods: {
      deleteGoal (index) {
        let conf = confirm("Do you really want to delete this goal?");
        if (conf === true) {
          this.$emit('delete-goal', { index });
            /*
          axios.delete('/goal/' + this.goal.id)
            .then(response => {
              this.$emit('delete-goal', { index });
            })
            .catch(error => {
            });

             */
        }
      },
      _formatDate(date, format = "MM/DD/YYYY") {
        return this.goal.due_date === null ? '' : moment(this.goal.due_date).format('MM/DD/YYYY');
      },
    }
}

</script>
