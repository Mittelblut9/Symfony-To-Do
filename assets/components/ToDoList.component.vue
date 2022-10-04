<link rel="stylesheet" href="../styles/app.css">
<template>
  <section class="todo">
    <header class="display-flex align-items-center">
      <h1 class="white">TODO</h1>
      <img :src="require(`../img/icons/${mode_icon}`)" alt="" class="height-100" @click="changeTheme">
    </header>
    <main>

      <div class="newtodo display-flex align-items-center">
        <input type="checkbox" class="absolute" :id="`checkBox`">
        <label :for="`checkBox`"></label>
        <input type="text" placeholder="Add new todo" v-model="new_todo" @keyup.enter="addTodo">
      </div>

      <ul>
        <li v-for="(todo, index) in todos" :key="index" class="display-flex align-items-center">
          <input type="checkbox" :checked="todo.done" @change="toggleDone(index)" class="absolute"
                 :id="`checkBox${index}`">
          <label :for="`checkBox${index}`"></label>
          <input type="text" :class="{ done: todo.done }" @click="selectInput(index)" class="left" @keyup.enter="updateToDo(index)" :value="todo.text">
          <button @click="removeTodo">
            <img src="@/img/icons/icon-cross.svg" alt="">
          </button>

        </li>
        <li class="filter display-flex ">
          <p>{{todos.length}} items left ({{todos.filter(i => i.done).length}} Done)</p>
          <p>
            <span>
              <a href="#" @click="filterTodos('all')" class="filter_active">All</a>
              <a href="#" @click="filterTodos('active')">Active</a>
              <a href="#" @click="filterTodos('completed')">Completed</a>
            </span>
          </p>
          <p>
            <a href="#" @click="clearCompleted">Clear Completed</a>
          </p>
        </li>
      </ul>
    </main>
  </section>
</template>

<script>
export default {
  name: "ToDoList",
  data() {
    return {
      new_todo: "",
      mode_icon: "",
      icons: {
        icon_dark: "icon-moon.svg",
        icon_light: "icon-sun.svg",
      },
      todos: [
        {text: "Learn Vue", done: false},
        {text: "Learn React", done: false},
        {text: "Learn Angular", done: false},
      ],
      unfiltered_todos: []
    }
  },
  beforeMount() {
    const prefersDarkMode = window.matchMedia("(prefers-color-scheme:dark)").matches
    this.mode_icon = prefersDarkMode ? this.icons.icon_light : this.icons.icon_dark;
  },
  mounted() {
    this.unfiltered_todos = this.todos;
  },
  methods: {
    addTodo() {
      const done = this.$el.querySelector("input[type='checkbox']").checked;

      const obj = {
        text: this.new_todo,
        done: done
      }

      this.todos.push(obj);

      this.new_todo = "";
      this.$el.querySelector("input[type='checkbox']").checked = false;
    },
    changeTheme() {
      const prefersDarkMode = localStorage.getItem("theme");

      if (prefersDarkMode === 'dark') {
        this.mode_icon = this.icons.icon_dark;
        document.documentElement.setAttribute("data-theme", "light")
        localStorage.setItem("theme", "light");
        return;
      }
      this.mode_icon = this.icons.icon_light;
      document.documentElement.setAttribute("data-theme", "dark");
      localStorage.setItem("theme", "dark");

    },
    removeTodo(index) {
      this.todos.splice(index, 1);
    },
    getTextInput(index) {
      return document.getElementById(`checkBox${index}`).nextElementSibling.nextElementSibling;
    },
    updateToDo(index) {
      const text = this.getTextInput(index).value;
      if(text === ' ' || text === '') {
        return this.removeTodo(index);
      }
      this.todos[index].text = text;
      this.getTextInput(index).blur();
    },
    toggleDone(index) {
      this.todos[index].done = !this.todos[index].done;

      this.getTextInput(index).classList.toggle("done");
    },
    selectInput(index) {
      this.getTextInput(index).setSelectionRange(0, this.getTextInput(index).value.length)
    },
    filterTodos(filter) {
      if (filter === "all") {
        this.todos = this.unfiltered_todos;
        return;
      }
      if (filter === "active") {
        this.todos = this.unfiltered_todos.filter(todo => !todo.done);
        return;
      }
      if (filter === "completed") {
        this.todos = this.unfiltered_todos.filter(todo => todo.done);
      }
    },
    clearCompleted() {
      this.todos = this.todos.filter(todo => !todo.done);
    }
  }
}
</script>

<style scoped>
@import url('@/styles/todo/todo.css');
</style>