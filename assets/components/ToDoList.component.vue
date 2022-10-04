<template>
  <section class="todo">
    <header class="display-flex align-items-center">
      <h1 class="white">TODO</h1>
      <img :src="require(`../img/icons/${mode_icon}`)" alt="" class=" height-100">
    </header>
    <main>

      <div class="display-flex align-items-center">
        <input type="checkbox" class="absolute" :id="`checkBox`">
        <label :for="`checkBox`"></label>
        <input type="text" placeholder="Add new todo" v-model="new_todo" @keyup.enter="addTodo">
      </div>

      <ul>
        <li v-for="(todo, index) in todos" :key="index" class="display-flex align-items-center">
          <input type="checkbox" :checked="todo.done" @change="toggleDone(index)" class="absolute"
                 :id="`checkBox${index}`">
          <label :for="`checkBox${index}`"></label>
          <input type="text" :class="{ done: todo.done }" class="left" :value="todo.text">
          <button @click="removeTodo">X</button>
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
    }
  },
  beforeMount() {
    const prefersDarkMode = window.matchMedia("(prefers-color-scheme:dark)").matches
    this.mode_icon = prefersDarkMode ? this.icons.icon_dark : this.icons.icon_light
  },
  methods: {
    addTodo() {
      this.todos.push({text: this.new_todo, done: false})
      this.new_todo = ""
    },
    removeTodo(index) {
      this.todos.splice(index, 1)
    },
    toggleDone(index) {
      this.todos[index].done = !this.todos[index].done
    }
  }
}
</script>

<style scoped>
@import url('@/styles/todo/todo.css');
</style>