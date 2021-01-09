GoalRow.vue:
<template>
    <tr>
        <td>{{ goal.name }}</td>
        <td>{{ goal.description }}</td>
        <td>{{ _formatDateTime(goal.due_date, 'MM/DD/YYYY') }}</td>
        <td>{{ _formatDateTime(goal.created_at) }}</td>
        <td>{{ _formatDateTime(goal.updated_at) }}</td>
        <td><button @click="updateGoal(index)" class="btn btn-success btn-xs" style="padding:8px"><span class="glyphicon glyphicon-edit"></span></button>
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

      deleteGoal () {
        let conf = confirm("Do you really want to delete this goal?");
        if (conf === true) {
        axios.delete('/goal/' + this.goal.id)
          .then(response => {
            this.$emit('remove');
          })
          .catch(error => {
          });
        }
      },

      updateGoal() {
          this.$emit('update');
      },

      _formatDateTime(date, format = 'MM/DD/YYYY hh:mm A') {
        return date === null ? '' : moment(date).format(format);
      },


    }
}

</script>
